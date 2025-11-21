<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function  like($id)
    {
        $user = Auth::user();

        $like = $user->likes()->whereItemId($id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $id,
            ]);
        }
        return redirect("/item/{$id}");
    }
}
