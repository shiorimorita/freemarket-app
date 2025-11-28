<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($item_id)
    {
        $user = Auth::user();
        $like = $user->likes()->whereItemId($item_id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item_id,
            ]);
        }

        return redirect("/item/{$item_id}");
    }
}
