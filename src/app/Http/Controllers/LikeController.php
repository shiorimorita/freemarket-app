<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function  like($id)
    {
        $user_id = Auth::id();

        $like = Like::where('user_id', $user_id)
            ->where('item_id', $id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user_id,
                'item_id' => $id,
            ]);
        }
        return redirect("/item/{$id}");
    }
}
