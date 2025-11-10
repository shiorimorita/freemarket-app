<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeliveryRequest;

class DeliveryController extends Controller
{
    public function create($item_id)
    {
        $user = Auth::user();
        $delivery = Delivery::where('item_id', $item_id)->first();
        if (! $delivery) {
            $delivery = new Delivery();
            $delivery->post_code = $user->profile->post_code;
            $delivery->address = $user->profile->address;
            $delivery->building = $user->profile->building;
        }

        return view('delivery_address', compact('delivery', 'item_id'));
    }

    public function store(DeliveryRequest $request, $item_id)
    {

        $delivery = $request->only(['post_code', 'address', 'building']);
        $delivery['item_id'] = $item_id;

        Delivery::updateOrCreate(
            ['item_id' => $item_id],
            $delivery
        );

        return redirect("/purchase/{$item_id}");
    }
}
