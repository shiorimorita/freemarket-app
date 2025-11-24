<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sold;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function method(Request $request, $id)
    {
        session(["method_{$id}" => $request->input('method')]);
        return response()->json(['status' => 'ok']);
    }

    public function showCheckout($id)
    {
        $item = Item::findOrFail($id);
        $delivery = session("delivery_temp_{$id}");
        $method = session("method_{$id}");

        if (! $delivery) {
            $user = Auth::user();
            $delivery = [
                'post_code' => $user->profile->post_code,
                'address'   => $user->profile->address,
                'building'  => $user->profile->building,
            ];
            session(["delivery_temp_{$id}" => $delivery]);
        }

        return view('checkout', compact('item', 'delivery', 'method'));
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        /* 自分の商品は購入禁止 */
        if ($item->user_id === Auth::id()) {
            return abort(403, '自分が出品した商品は購入できません');
        }

        /* 売り切れ商品の場合 */
        if ($item->is_sold) {
            return abort(403, 'この商品はすでに売れています');
        }

        $delivery = session("delivery_temp_{$item_id}");
        $method = session("method_{$item_id}");

        Sold::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'method' => $method,
            'post_code' => $delivery['post_code'],
            'address' => $delivery['address'],
            'building' => $delivery['building'],
        ]);

        if ($request->input('method') === 'カード払い') {
            return redirect()->route('stripe.card', ['id' => $item_id]);
        }
        // JS にて stripe 決済へつながるため、/ へリダイレクト設定
        if ($request->input('method') === 'コンビニ払い') {
            return redirect('/');
        }
    }

    public function purchaseCard($item_id)
    {
        $item = Item::findOrFail($item_id);
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
            'success_url' => url('/'),
            'cancel_url'  => url('/purchase/' . $item_id),
        ]);

        return redirect($session->url);
    }

    public function purchaseKonbini($item_id)
    {
        $item = Item::findOrFail($item_id);
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
