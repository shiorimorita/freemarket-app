<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemRequest;
use App\Models\Category;

class ItemController extends Controller
{

    public function createView(){
        $categories = Category::all();
        return view('sell',compact('categories'));
    }

    public function create(ItemRequest $request)
    {
        $data = $request->only(['name', 'brand', 'description', 'price', 'condition']);
        $data['user_id'] = Auth::id();
        $data['image_path'] = $request->file('image_path')->store('images','public');

        $item = Item::create($data);
        $item->categories()->attach($request->category_ids);

        return redirect('/');
    }

    public function indexView(){
        $items = Item::all();
        return view('index',compact('items'));
    }

}
