<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($id)
    {
        $user_id = Auth::id();

        $like = Like::firstOrNew([
            'user_id' => $user_id,
            'item_id' => $id,
        ]);

        if ($like->exists) {
            $like->delete();
        } else {
            $like->save();
        }

        return redirect("/item/{$id}");
    }
}
