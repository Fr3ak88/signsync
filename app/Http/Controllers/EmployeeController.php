<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Position;
use App\Models\Schueler; 
use App\Mail\EmployeeInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mollie\Laravel\Facades\Mollie;

class EmployeeController extends Controller
{
    /**
     * Liste aller Mitarbeiter (aktiv oder Archiv)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $positions = Position::where('admin_id', $user->id)->get();
        
        $status = $request->get('status');

        if ($status === 'archiv') {
            $query = Employee::onlyTrashed()->with('user')->where('admin_id', $user->id);
        } else {
            $query = Employee::with('user')->where('admin_id', $user->id);
        }

        if ($request->has('position') && $request->position != '') {
            $query->where('position', $request->position);
        }

        $employees = $query->get();
        
        return view('admin.employees.index', compact('employees', 'positions', 'status'));
    }

    /**
     * Formular zum Anlegen eines neuen Mitarbeiters
     */
    public function create()
    {
        $positions = Position::where('admin_id', auth()->id())->get();
        $schueler = Schueler::where('admin_id', auth()->id())->orderBy('name', 'asc')->get();
        
        return view('admin.employees.create', compact('positions', 'schueler'));
    }

    /**
     * Speichern eines neuen Mitarbeiters inkl. Mollie-Preis-Sync
     */
    public function store(Request $request)
    {
        $admin = Auth::user();
        $currentEmployeeCount = $admin->employees()->count();

        // 1. Limit-Prüfung
        // Starter & Team werden bei Erreichen des Limits geblockt.
        // Pro darf über 50 hinaus (Pay-per-Seat), wird aber am technischen Limit (1000) geblockt.
        if ($admin->plan_name !== 'pro' && $currentEmployeeCount >= $admin->max_employees) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Limit erreicht: Ihr aktuelles Paket erlaubt maximal ' . $admin->max_employees . ' Begleiter. Bitte führen Sie ein Upgrade auf das Pro-Paket durch.');
        }

        if ($admin->plan_name === 'pro' && $currentEmployeeCount >= $admin->max_employees) {
            return redirect()->back()->with('error', 'Maximales technisches Limit von 1000 Begleitern erreicht.');
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'position'   => 'required|string|max:255',
            'schueler'   => 'nullable|array', 
        ]);

        // 2. User-Account & Mitarbeiter-Datensatz erstellen
        $user = User::create([
            'name'     => $data['first_name'] . ' ' . $data['last_name'],
            'email'    => $data['email'],
            'password' => Hash::make(Str::random(32)),
            'role'     => 'employee',
            'company'  => $admin->company,
            'admin_id' => $admin->id,
        ]);

        $employee = new Employee();
        $employee->user_id     = $user->id;
        $employee->admin_id    = $admin->id;
        $employee->first_name  = $data['first_name'];
        $employee->last_name   = $data['last_name'];
        $employee->email       = $data['email'];
        $employee->position    = $data['position'];
        $employee->save();

        if ($request->has('schueler')) {
            $employee->schueler()->sync($request->schueler);
        }

        // 3. Automatisches Mollie-Update bei Pro-Plan über 50 User
        $newCount = $currentEmployeeCount + 1;
        if ($admin->plan_name === 'pro' && $newCount > 50) {
            $this->syncMollieSubscriptionPrice($admin, $newCount);
        }

        // 4. Einladungs-Logik
        $token = \Illuminate\Support\Facades\Password::createToken($user);
        $url = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

        try {
            Mail::to($user->email)->send(new EmployeeInvitationMail($user->name, $url));
            $message = 'Mitarbeiter erfolgreich angelegt.';
            if ($newCount > 50 && $admin->plan_name === 'pro') {
                $message .= ' Ihr Abo wurde automatisch um einen Zusatz-Slot erweitert.';
            }
        } catch (\Exception $e) {
            Log::error("Mail-Versand fehlgeschlagen: " . $e->getMessage());
            $message = 'Mitarbeiter angelegt, aber E-Mail-Versand fehlgeschlagen.';
        }

        return redirect('/admin/employees')->with('success', $message);
    }

    /**
     * Mitarbeiter bearbeiten
     */
    public function edit($id)
    {
        $employee = Employee::with('schueler')
            ->where('admin_id', Auth::id())
            ->findOrFail($id);

        $positions = Position::where('admin_id', Auth::id())->get();
        $schueler = Schueler::where('admin_id', Auth::id())->orderBy('name', 'asc')->get();

        return view('admin.employees.edit', compact('employee', 'positions', 'schueler'));
    }

    /**
     * Mitarbeiter aktualisieren
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::where('admin_id', Auth::id())->findOrFail($id);
        
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'position'   => 'required|string|max:255',
            'schueler'   => 'nullable|array', 
        ]);

        $employee->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'position'   => $data['position'],
        ]);

        if ($employee->user) {
            $employee->user->update([
                'name'  => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email']
            ]);
        }

        $employee->schueler()->sync($request->schueler ?? []);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Mitarbeiter wurde erfolgreich aktualisiert.');
    }

    /**
     * Mitarbeiter reaktivieren
     */
    public function restore($id)
    {
        $admin = Auth::user();
        $employee = Employee::onlyTrashed()->where('admin_id', $admin->id)->findOrFail($id);
        
        // Prüfung, ob Reaktivierung das Limit sprengt
        $currentEmployeeCount = $admin->employees()->count();
        if ($admin->plan_name !== 'pro' && $currentEmployeeCount >= $admin->max_employees) {
            return redirect()->back()->with('error', 'Limit erreicht. Reaktivierung nicht möglich.');
        }

        $employee->restore();
        if ($employee->user) {
            $employee->user->restore();
        }

        // Preis-Update bei Reaktivierung im Pro-Plan
        $newCount = $currentEmployeeCount + 1;
        if ($admin->plan_name === 'pro' && $newCount > 50) {
            $this->syncMollieSubscriptionPrice($admin, $newCount);
        }

        return redirect()->route('admin.employees.index')->with('success', 'Mitarbeiter erfolgreich reaktiviert.');
    }

    /**
     * Mitarbeiter deaktivieren (Soft Delete) inkl. Mollie-Preis-Sync
     */
    public function destroy($id)
    {
        $admin = Auth::user();
        $employee = Employee::where('admin_id', $admin->id)->findOrFail($id);
        
        $employee->user->delete(); 
        $employee->delete(); 

        // Wenn Pro-Plan und nach Löschung immer noch über 50 User -> Preis senken
        $remainingCount = $admin->employees()->count();
        if ($admin->plan_name === 'pro' && $remainingCount >= 50) {
            $this->syncMollieSubscriptionPrice($admin, $remainingCount);
        }

        return redirect()->back()->with('success', 'Mitarbeiter wurde erfolgreich deaktiviert.');
    }

    /**
     * Hilfsmethode: Synchronisiert den Abo-Preis bei Mollie
     */
    private function syncMollieSubscriptionPrice($admin, $employeeCount)
    {
        try {
            if (!$admin->mollie_customer_id) return;

            $basePricePro = 117.81;
            $pricePerExtraUser = 3.57; // 3,00 € Netto + 19% MwSt
            
            $extraUsers = max(0, $employeeCount - 50);
            $newTotalPrice = number_format($basePricePro + ($extraUsers * $pricePerExtraUser), 2, '.', '');

            $customer = Mollie::api()->customers->get($admin->mollie_customer_id);
            $subscriptions = $customer->subscriptions();

            foreach ($subscriptions as $subscription) {
                if ($subscription->status === 'active') {
                    $subscription->amount = [
                        "currency" => "EUR",
                        "value"    => $newTotalPrice
                    ];
                    $subscription->description = "SignSync Abo: Pro ({$employeeCount} User)";
                    $subscription->update();
                    
                    Log::info("Mollie: Preis-Sync für Admin {$admin->id} auf {$newTotalPrice}€ (User: {$employeeCount}).");
                }
            }
        } catch (\Exception $e) {
            Log::error("Mollie Preis-Sync fehlgeschlagen: " . $e->getMessage());
        }
    }
}