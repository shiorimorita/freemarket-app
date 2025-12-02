<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sold;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function method(Request $request, $item_id)
    {
        session(["method_{$item_id}" => $request->input('method')]);
        return response()->json(['status' => 'ok']);
    }

    public function showCheckout($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->user->id === Auth::id()) {
            return redirect('/')->with('error', '自身の商品は購入することができません');
        }

        if ($item->is_sold && $item->sold->user_id === Auth::id()) {
            return redirect('/')->with('success', '既に購入が完了しております。マイページより購入した商品をご確認ください。');
        }

        if ($item->is_sold && $item->sold->user_id !== Auth::id()) {
            return redirect('/')->with('error', 'こちらの商品は売り切れのため購入できません。');
        }

        $delivery = session("delivery_temp_{$item_id}");
        $method = session("method_{$item_id}");

        if (! $delivery) {
            $user = Auth::user();
            $delivery = [
                'post_code' => $user->profile->post_code,
                'address'   => $user->profile->address,
                'building'  => $user->profile->building,
            ];
            session(["delivery_temp_{$item_id}" => $delivery]);
        }

        return view('checkout', compact('item', 'delivery', 'method'));
    }

    /* バリデーションエラー表示のため、PurchaseRequest を設定 */
    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $delivery = session("delivery_temp_{$item_id}");
        $method = session("method_{$item_id}");

        /* 購入アクセス制御 */
        if ($item->user_id === Auth::id()) {
            return redirect('/')->with('error', '自分の商品は購入できません');
        }

        if ($item->is_sold) {
            return redirect('/')->with('error', 'こちらの商品は売り切れのため購入できません');
        }

        /* カード決済 */
        if ($method === 'カード払い') {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'    => 'jpy',
                        'unit_amount' => intval($item->price),
                        'product_data' => ['name' => $item->name],
                    ],
                    'quantity' => 1,
                ]],
                'payment_intent_data' => [
                    'capture_method' => 'manual',
                ],
                'success_url' => route('card.success', ['item_id' => $item_id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => url('/purchase/' . $item_id),
            ]);

            return redirect($session->url);
        }
        // コンビニ払い
        if ($method === 'コンビニ払い') {
            Sold::create([
                'user_id' => Auth::id(),
                'item_id' => $item_id,
                'method' => $method,
                'post_code' => $delivery['post_code'],
                'address' => $delivery['address'],
                'building' => $delivery['building'],
            ]);

            session()->forget("delivery_temp_{$item_id}");
            session()->forget("method_{$item_id}");

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::create([
                'mode' => 'payment',
                'payment_method_types' => ['konbini'],
                'payment_method_options' => [
                    'konbini' => ['expires_after_days' => 7],
                ],
                'line_items' => [[
                    'price_data' => [
                        'currency'    => 'jpy',
                        'unit_amount' => intval($item->price),
                        'product_data' => ['name' => $item->name],
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => url('/'),
                'cancel_url'  => url('/purchase/' . $item_id),
            ]);

            return redirect($session->url);
        }
    }

    public function success(Request $request, $item_id)
    {
        $session_id = $request->query('session_id');

        if (!$session_id) {
            return redirect('/')->with('error', '決済情報が取得できませんでした。');
        }

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->retrieve($session_id, []);
        $paymentIntent = $stripe->paymentIntents->retrieve($session->payment_intent, []);

        $item     = Item::findOrFail($item_id);
        $delivery = session("delivery_temp_{$item_id}");
        $method   = session("method_{$item_id}");

        if ($item->is_sold) {
            $stripe->paymentIntents->cancel($paymentIntent->id, []);
            return redirect('/')
                ->with('error', '売り切れのため購入できませんでした。決済はキャンセルされました。');
        }

        try {
            $stripe->paymentIntents->capture($paymentIntent->id, []);
        } catch (\Exception $e) {
            return redirect('/')->with('error', '決済確定に失敗しました。');
        }

        // Capture 成功後に Sold登録
        Sold::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'method'  => $method,
            'post_code' => $delivery['post_code'],
            'address' => $delivery['address'],
            'building' => $delivery['building'],
        ]);

        session()->forget("delivery_temp_{$item_id}");
        session()->forget("method_{$item_id}");

        return redirect('/')
            ->with('success', '決済が完了しました！マイページより購入した商品をご確認ください。');
    }
}
