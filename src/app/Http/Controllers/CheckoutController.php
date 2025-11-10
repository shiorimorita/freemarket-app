<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Delivery;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Sold;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;

class CheckoutController extends Controller
{
    public function showCheckout($id)
    {
        $user = Auth::user();
        $item = Item::find($id);

        if ($item->user_id == $user->id) {
            abort(403, '自分の商品は購入できません');
        }

        $isSold = DB::table('solds')
            ->where('item_id', $item->id)
            ->exists();

        if ($isSold) {
            abort(403, 'この商品はすでに売れています');
        }

        $delivery = Delivery::where('item_id', $item->id)->first();
        if (! $delivery) {
            $delivery = Profile::where('user_id', $user->id)->first();
        }

        return view('checkout', compact('item', 'delivery'));
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $user_id = Auth::id();

        $sold = $request->only(['method']);
        $sold['user_id'] = $user_id;
        $sold['item_id'] = $item_id;

        Sold::create($sold);

        $delivery = Delivery::where('item_id', $item_id)->first();

        if (!$delivery) {
            $profile = Profile::where('user_id', $user_id)->first();

            if ($profile) {
                Delivery::create([
                    'item_id' => $item_id,
                    'post_code' => $profile->post_code,
                    'address' => $profile->address,
                    'building' => $profile->building,
                ]);
            }
        }
        return redirect('/');
    }
}
