<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionFailed;
use Mollie\Laravel\Facades\Mollie;

class MollieWebhookController extends Controller
{
    public function handle(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json(['error' => 'Missing ID'], 400);
        }

        try {
            $payment = Mollie::api()->payments->get($request->id);
            $userId = $payment->metadata->user_id ?? null;
            $user = User::find($userId);

            if (!$user) {
                Log::error("Mollie Webhook: Kein User zu ID {$userId} gefunden.");
                return response('OK', 200);
            }

            // 1. FALL: Zahlung erfolgreich
            if ($payment->isPaid() && !$payment->hasRefunds()) {
                $planLimits = ['starter' => 5, 'team' => 20, 'pro' => 50];
                $planName = $payment->metadata->plan_name ?? $user->plan_name;

                $user->update([
                    'plan_name' => $planName,
                    'max_employees' => $planLimits[$planName] ?? $user->max_employees,
                    'has_active_subscription' => true,
                    'mollie_customer_id' => $user->mollie_customer_id ?? $payment->customerId,
                ]);

                if ($payment->sequenceType === 'first') {
                    $this->createMollieSubscription($payment, $user);
                }

                Log::info("Mollie Webhook: Zahlung für User {$user->id} erfolgreich.");
            } 
            
            // 2. FALL: Zahlung fehlgeschlagen / abgebrochen / abgelaufen
            elseif ($payment->isFailed() || $payment->isExpired() || $payment->isCanceled()) {
                
                // Nur wenn er vorher aktiv war, schicken wir eine Mail (verhindert Spam bei Mehrfach-Fehlern)
                if ($user->has_active_subscription) {
                    $user->update(['has_active_subscription' => false]);
                    
                    try {
                        Mail::to($user->email)->send(new SubscriptionFailed($user));
                        Log::info("Mollie Webhook: Fehler-Mail an {$user->email} gesendet.");
                    } catch (\Exception $e) {
                        Log::error("Mailversand an {$user->email} fehlgeschlagen: " . $e->getMessage());
                    }
                }

                Log::warning("Mollie Webhook: Status '{$payment->status}' für User {$user->id}.");
            }

        } catch (\Exception $e) {
            Log::error("Mollie Webhook Fehler: " . $e->getMessage());
            return response('OK', 200); 
        }

        return response('OK', 200);
    }

    /**
     * Erstellt das dauerhafte monatliche Abo bei Mollie
     */
    protected function createMollieSubscription($payment, $user)
    {
        try {
            $customer = Mollie::api()->customers->get($payment->customerId);
            
            // Prüfung: Hat der Kunde bereits ein aktives Abo?
            $existingSubscriptions = $customer->subscriptions();
            foreach ($existingSubscriptions as $subscription) {
                if ($subscription->status === 'active' || $subscription->status === 'pending') {
                    Log::info("Mollie: Abo für Kunde {$user->id} existiert bereits.");
                    return; 
                }
            }

            // --- FIX FÜR DEN TYPE-ERROR ---
            // Wir müssen das amount-Objekt explizit in ein Array umwandeln
            $amountArray = [
                "currency" => $payment->amount->currency,
                "value"    => $payment->amount->value,
            ];
            
            // Das Abo bei Mollie erstellen
            $customer->createSubscription([
                "amount"      => $amountArray, // Hier das konvertierte Array nutzen
                "interval"    => "1 month",
                "description" => "SignSync Abo: " . ucfirst($payment->metadata->plan_name),
                "webhookUrl"  => route('webhooks.mollie'),
            ]);
            
            Log::info("Mollie: Neues Dauer-Abo für Kunde {$user->id} angelegt.");
        } catch (\Exception $e) {
            Log::error("Mollie Abo-Erstellung fehlgeschlagen: " . $e->getMessage());
        }
    }
}