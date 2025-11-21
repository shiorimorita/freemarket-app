<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeliveryRequest;
use App\Models\Item;

class DeliveryController extends Controller
{
    public function create($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $delivery = session("delivery_temp_{$item_id}");

        if (!$delivery) {
            $delivery = [
                'post_code' => $user->profile->post_code,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ];
        }

        $delivery = (object)$delivery;
        return view('delivery_address', compact('delivery', 'item'));
    }

    public function store(DeliveryRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        if ($item->is_sold || $item->user_id === Auth::id()) {
            abort(403, 'こちらの商品の配送先は変更できません');
        }

        $delivery = $request->only(['post_code', 'address', 'building']);

        session([
            "delivery_temp_{$id}" => $delivery
        ]);

        return redirect("/purchase/{$id}");
    }
}
