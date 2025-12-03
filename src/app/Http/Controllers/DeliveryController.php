<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeliveryRequest;
use App\Models\Item;

class DeliveryController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->user->id === Auth::id()) {
            return redirect('/')->with('error', '自分の商品の配送先住所は設定することができません');
        }

        if ($item->is_sold && $item->sold->user_id === Auth::id()) {
            return redirect('/')->with('success', '既に購入が完了しております。マイページより購入した商品をご確認ください。');
        }

        if ($item->is_sold && $item->sold->user_id !== Auth::id()) {
            return redirect('/')->with('error', 'こちらの商品は売り切れのため購入できません。');
        }

        $user = Auth::user();
        $delivery = session("delivery_temp_{$item_id}");

        if (!$delivery) {
            $delivery = [
                'post_code' => $user->profile->post_code,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ];
        }

        return view('delivery_address', compact('delivery', 'item'));
    }

    public function store(DeliveryRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->user->id === Auth::id()) {
            return redirect('/')->with('error', '自分の商品の配送先住所は設定することができません');
        }

        if ($item->is_sold && $item->sold->user_id === Auth::id()) {
            return redirect('/')->with('success', '既に購入が完了しております。マイページより購入した商品をご確認ください。');
        }

        if ($item->is_sold && $item->sold->user_id !== Auth::id()) {
            return redirect('/')->with('error', 'こちらの商品は売り切れのため購入できません。');
        }

        $delivery = $request->only(['post_code', 'address', 'building']);

        session([
            "delivery_temp_{$item_id}" => $delivery
        ]);

        return redirect("/purchase/{$item_id}");
    }
}
