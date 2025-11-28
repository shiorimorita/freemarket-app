<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return redirect("/item/{$item_id}");
    }
}
