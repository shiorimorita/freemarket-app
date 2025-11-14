<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Stripe\Stripe;
use App\Models\Sold;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;

class StripePaymentController extends Controller
{
    // StripeClientを初期化するヘルパーメソッド
    private function getStripeClient()
    {
        $secretKey = config('services.stripe.secret');
        if (empty($secretKey)) {
            throw new \Exception('Stripe Secret Key is not configured in config/services.php');
        }
        return new StripeClient($secretKey);
    }

    // 既存の payByCard メソッドを修正
    public function payByCard($item_id)
    {
        // 修正: \Stripe\Stripe::setApiKey(env('STRIPE_SECRET')); を削除し、StripeClientを使用
        try {
            $stripe = $this->getStripeClient();
        } catch (\Exception $e) {
            return back()->withErrors(['stripe_error' => 'Stripe設定エラー: ' . $e->getMessage()]);
        }

        $item = Item::find($item_id);

        // Checkout Sessionの作成は、StripeClientではなく、静的メソッドで行うため、
        // ここで再度 setApiKey を呼び出すか、Checkout Sessionの作成もStripeClient経由に修正する必要があります。
        // 既存のコードを活かすため、ここでは setApiKey を使用します。
        // ただし、config('services.stripe.secret') を使用するように修正します。
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

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

    public function payKonbini(Request $request, $item_id)
    {
        // Stripe秘密鍵設定
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // 商品取得（必要に応じて）
        $item = Item::find($item_id);
        if (!$item) {
            abort(404, 'Item not found');
        }

        // ★ コンビニ払いの場合も Sold を作成
        Sold::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'method'  => 'コンビニ払い'
        ]);

        // Checkout セッション（コンビニ専用）
        $session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',

            // コンビニ専用（カードを含めない）
            'payment_method_types' => ['konbini'],

            'payment_method_options' => [
                'konbini' => [
                    'expires_after_days' => 7, // 任意
                ],
            ],

            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'unit_amount' => intval($item->price),
                        'product_data' => [
                            'name' => $item->name,
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],

            // 成功後（※コンビニはここが即発火しない点は理解済み）
            'success_url' => url('/'),

            // キャンセル時
            'cancel_url'  => url('/purchase/' . $item_id),
        ]);

        return redirect($session->url);
    }
}
