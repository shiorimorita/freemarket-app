<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeliveryRequest;
use App\Models\Item;

class DeliveryController extends Controller
{
    public function create($item_id)
    {
        $user = Auth::user();
        $item = Item::find($item_id);
        $delivery = Delivery::where('item_id', $item_id)->first();
        if (! $delivery) {
            $delivery = new Delivery();
            $delivery->post_code = $user->profile->post_code;
            $delivery->address = $user->profile->address;
            $delivery->building = $user->profile->building;
        }

        return view('delivery_address', compact('delivery', 'item_id', 'item'));
    }

    public function store(DeliveryRequest $request, $item_id)
    {
        $item = Item::find($item_id);

        if ($item->is_sold) {
            abort(403, 'この商品はすでに売れているため配送先を変更できません');
        }

        session([
            "delivery_temp_{$item_id}" => [
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect("/purchase/{$item_id}");
    }
}
