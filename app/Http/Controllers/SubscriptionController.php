<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mollie\Laravel\Facades\Mollie;

class SubscriptionController extends Controller
{
    // Basis-Preise (Brutto)
    private $prices = [
        'starter' => '22.61',
        'team'    => '58.31',
        'pro'     => '117.81', // Basispreis für Pro (bis zu 50 User)
    ];

    private $limits = [
        'starter' => 5,
        'team'    => 20,
        'pro'     => 1000, // Theoretisches Maximum
    ];

    /**
     * Berechnet den dynamischen Preis für das Pro-Paket
     */
    private function calculateCurrentPrice($plan, $employeeCount)
    {
        if ($plan !== 'pro') {
            return $this->prices[$plan];
        }

        $basePricePro = 117.81;
        $includedUsers = 50;
        $pricePerExtraUser = 3.57; 

        if ($employeeCount <= $includedUsers) {
            return number_format($basePricePro, 2, '.', '');
        }

        $extraUsers = $employeeCount - $includedUsers;
        $totalPrice = $basePricePro + ($extraUsers * $pricePerExtraUser);

        return number_format($totalPrice, 2, '.', '');
    }

    public function index()
    {
        return view('plans.index');
    }

    public function storePlan(Request $request)
    {
        $request->validate(['plan' => 'required|in:starter,team,pro']);
        $user = Auth::user();
        $newPlan = $request->plan;
        $employeeCount = $user->employees()->count();

        try {
            // Preis dynamisch berechnen (besonders wichtig für Pro > 50 User)
            $dynamicPrice = $this->calculateCurrentPrice($newPlan, $employeeCount);

            if (!$user->has_active_subscription) {
                return $this->createNewMollieSubscription($user, $newPlan, $dynamicPrice);
            }

            return $this->updateExistingMollieSubscription($user, $newPlan, $dynamicPrice);

        } catch (\Exception $e) {
            Log::error('Mollie Fehler: ' . $e->getMessage());
            return back()->with('error', 'Es gab ein Problem: ' . $e->getMessage());
        }
    }

    private function createNewMollieSubscription($user, $plan, $price)
    {
        $customer = $this->getOrCreateMollieCustomer($user);

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value"    => $price
            ],
            "customerId"   => $customer->id,
            "sequenceType" => "first", 
            "description"  => "SignSync Aktivierung: " . ucfirst($plan),
            "redirectUrl"  => route('dashboard') . '?success=true',
            "webhookUrl"   => route('webhooks.mollie'),
            "metadata"     => [
                "user_id"   => $user->id,
                "plan_name" => $plan,
            ],
        ]);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    private function updateExistingMollieSubscription($user, $newPlan, $price)
{
    // 1. Limit Check (Wie viele Mitarbeiter hat dieser Admin schon?)
    if ($this->limits[$newPlan] < $user->employees()->count()) {
        return back()->with('error', 'Wechsel nicht möglich: Zu viele Mitarbeiter angelegt.');
    }

    // 2. Sicherheits-Check: Hat dieser Admin überhaupt ein Mollie-Kundenkonto?
    if (empty($user->mollie_customer_id)) {
        
        // AUSNAHME: Dein spezieller "Test-Admin" (z.B. ID 2 oder eine spezielle Mail)
        // Falls du das zum Testen brauchst, sonst diese 3 Zeilen löschen:
        if ($user->email === 'group@fritzler.fr') {
             $user->update(['plan_name' => $newPlan, 'max_employees' => $this->limits[$newPlan]]);
             return redirect()->route('dashboard')->with('info', 'Test-Modus: Plan lokal angepasst.');
        }

        // JEDER ANDERE ADMIN: Muss erst den ersten Bezahlvorgang machen
        // Er kann hier nicht "upgraden", weil es noch gar kein Abo zum Upgraden gibt.
        return redirect()->route('subscription.index') 
            ->with('error', 'Sie haben noch kein aktives Abonnement. Bitte wählen Sie ein Paket aus und schließen Sie die Zahlung ab.');
    }

    try {
        // 3. Mollie API: Bestehendes Abo suchen und Preis ändern
        $customer = Mollie::api()->customers->get($user->mollie_customer_id);
        $subscriptions = $customer->subscriptions();

        $updatedOnMollie = false;
        foreach ($subscriptions as $subscription) {
            if ($subscription->status === 'active') {
                $subscription->amount = [
                    "currency" => "EUR",
                    "value"    => $price // Der neue Preis (z.B. "29.00")
                ];
                $subscription->description = "SignSync Abo Wechsel: " . ucfirst($newPlan);
                $subscription->update();
                $updatedOnMollie = true;
            }
        }

        // Falls der Admin zwar eine Mollie-ID hat, aber sein Abo gekündigt/ausgelaufen ist
        if (!$updatedOnMollie) {
            return redirect()->route('subscription.index')
                ->with('error', 'Kein aktives Abonnement gefunden. Bitte buchen Sie das Paket neu.');
        }

    } catch (\Exception $e) {
        return back()->with('error', 'Verbindung zu Mollie fehlgeschlagen: ' . $e->getMessage());
    }

    // 4. Erfolg: Datenbank lokal aktualisieren
    $user->update([
        'plan_name' => $newPlan,
        'max_employees' => $this->limits[$newPlan],
        'has_active_subscription' => true,
    ]);

    return redirect()->route('dashboard')->with('success', 'Paket erfolgreich auf ' . ucfirst($newPlan) . ' umgestellt.');
}

    private function getOrCreateMollieCustomer($user)
    {
        if ($user->mollie_customer_id) {
            try {
                return Mollie::api()->customers->get($user->mollie_customer_id);
            } catch (\Exception $e) {
                Log::warning("Mollie ID nicht gefunden, erstelle neu.");
            }
        }

        $customer = Mollie::api()->customers->create([
            "name"  => $user->name,
            "email" => $user->email,
        ]);

        $user->update(['mollie_customer_id' => $customer->id]);
        return $customer;
    }

    public function cancel()
    {
        $user = Auth::user();
        if ($user->mollie_customer_id) {
            try {
                $customer = Mollie::api()->customers->get($user->mollie_customer_id);
                foreach ($customer->subscriptions() as $subscription) {
                    if ($subscription->isValid()) {
                        $subscription->cancel();
                    }
                }
            } catch (\Exception $e) {
                Log::error("Mollie Kündigungsfehler: " . $e->getMessage());
            }
        }
        $user->update(['plan_name' => null, 'max_employees' => 0, 'has_active_subscription' => false]);
        return redirect()->route('plans.index')->with('info', 'Abonnement beendet.');
    }
}