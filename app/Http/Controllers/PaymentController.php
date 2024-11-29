<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function processStripePayment(Request $request)
    {
        $amount = $request->input('amount');
        $currency = $request->input('currency', 'USD');

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $charge = Charge::create([
                'amount' => $amount * 100, // Stripe expects amounts in cents
                'currency' => $currency,
                'source' => $request->input('stripeToken'),
                'description' => 'Payment Description',
            ]);
            
            Payment::create([
                'payment_method' => 'Stripe',
                'amount' => $amount,
                'currency' => $currency,
                'status' => $charge->status,
                'transaction_id' => $charge->id,
            ]);

            return response()->json(['success' => true, 'message' => 'Payment processed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}