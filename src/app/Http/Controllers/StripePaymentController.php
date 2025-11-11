<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class StripePaymentController extends Controller
{
    public function payByCard($item_id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = Item::find($item_id);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => intval($item->price),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/'),
            'cancel_url' => url('/purchase/' . $item_id),
        ]);

        return redirect($session->url);
    }

    public function payByKonbini($item_id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = Item::find($item_id);
        $user = Auth::user();
        $delivery = Delivery::where('item_id', $item_id)->first();

        $pi = \Stripe\PaymentIntent::create([
            'amount' => intval($item->price),
            'currency' => 'jpy',
            'payment_method_types' => ['konbini'],

            // 請求先
            'payment_method_data' => [
                'type' => 'konbini',
                'billing_details' => [
                    'name'  => $user->name,
                    'email' => $user->email,
                    'address' => [
                        'postal_code' => $user->profile->post_code,
                        'line1'       => $user->profile->address,
                        'line2'       => $user->profile->building,
                    ],
                ],
            ],

            // 配送先
            'shipping' => [
                'name' => $user->name,
                'address' => [
                    'postal_code' => $delivery->post_code,
                    'line1'       => $delivery->address,
                    'line2'       => $delivery->building,
                ],
            ],

            'confirm' => true,
        ]);

        $url = $pi->next_action->konbini_display_details->hosted_voucher_url;

        return redirect()->away($url);
    }
}
