<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemRequest;

class ItemController extends Controller
{
    public function create(ItemRequest $request)
    {
        $item = $request->only(['name', 'brand', 'description', 'price', 'condition']);
        $item['user_id'] = Auth::id();
        $image_path = $request->file('image_path')->store('images','public');
        $item['image_path'] = $image_path;

        Item::create($item);
        return redirect('/');
    }
}
