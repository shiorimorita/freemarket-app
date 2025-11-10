<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(CommentRequest $request, $id)
    {
        Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'item_id' => $id,
        ]);

        return redirect("/item/{$id}");
    }
}
