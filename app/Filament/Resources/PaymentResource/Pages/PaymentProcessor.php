<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use Filament\Pages\Page;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentProcessor extends Page
{
    protected static ?string $navigationLabel = 'Process Payment';

    public $amount;
    public $currency = 'USD';
    public $stripeToken;

    public function processStripePayment()
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $charge = Charge::create([
                'amount' => $this->amount * 100, // Stripe expects amounts in cents
                'currency' => $this->currency,
                'source' => $this->stripeToken,
                'description' => 'Admin Panel Payment',
            ]);

            Payment::create([
                'payment_method' => 'Stripe',
                'amount' => $this->amount,
                'currency' => $this->currency,
                'status' => $charge->status,
                'transaction_id' => $charge->id,
            ]);

            $this->notify('success', 'Payment processed successfully!');
        } catch (\Exception $e) {
            $this->notify('danger', 'Payment failed: ' . $e->getMessage());
        }
    }

    protected static string $view = 'filament.pages.payment-processor';
}