<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_TEST_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100, // $10.00 = 1000
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }
    public function createPaymentSheet(Request $request)
    {
       
        Stripe::setApiKey(config('services.stripe.secret'));

        // 1) Create or retrieve a Customer
        //    If you have your own users table, you might store the stripe_customer_id there
        $email = User::where('email', $request->email)->first();
        $customer = Customer::create([
            'email' => $email, 
        ]);

       

        // 2) Create an Ephemeral Key
        $ephemeralKey = EphemeralKey::create(
            ['customer' => $customer->id],
            ['stripe_version' => '2022-11-15']  // match Stripe-React-Native supported version
        );

        // 3) Create a PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => 29999,          // e.g. $299.99 in cents
            'currency' => 'usd',
            'customer' => $customer->id,
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        // 4) Return the client_secret, ephemeral_key, and customer ID
        return response()->json([
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey'  => $ephemeralKey->secret,
            'customer'      => $customer->id,
        ]);
    }

    public function updateDates(Request $request)
    {
        $request->validate([
            'payment_date'       => 'required|date_format:Y-m-d H:i:s',
            'next_payment_date'  => 'required|date_format:Y-m-d H:i:s',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->payment_date      = $request->payment_date;
        $user->next_payment_date = $request->next_payment_date;
        $user->save();

        return response()->json([
            'message' => 'Payment dates updated',
            'payment_date' => $user->payment_date,
            'next_payment_date' => $user->next_payment_date,
        ]);
    }
}
