<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Exception $e) {
            return response('invalid', 400);
        }

        Log::info("Stripe Webhook: " . $event->type);

        // ★ テストで succeed_immediately@xxx + 22222222220 の場合
        //   → コンビニ払いでもここが即呼ばれる
        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            // ここに好きな処理を書く（メール送信など）
            Log::info("コンビニ支払い成功！ payment_intent=" . $intent->id);
        }

        return response('ok', 200);
    }
}
