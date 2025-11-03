<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(Request $request,$id)
    {
        Comment::create([
        'content' => $request->content,
        'user_id' => Auth::id(),
        'item_id' => $id,
        ]);

        return redirect("/item/{$id}");
    }

}
