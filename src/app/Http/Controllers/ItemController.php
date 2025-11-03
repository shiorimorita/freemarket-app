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

    public function indexView(Request $request)
    {
        $user = Auth::user();
        $tab  = $request->tab;

        if (!$tab) {
        $tab = 'recommend';
    }

    if ($tab === 'mylist') {
        if (!$user) return redirect('/login');
        
        $items = Item::whereHas('likes', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

    }else 
    {
        if ($user) 
            {
            // ログイン時は他人の出品のみ
            $items = Item::where('user_id', '!=', $user->id)->get();
            } else {
            // 未ログイン時は全商品
            $items = Item::all();
        }
    }

    return view('index', compact('items', 'tab'));

    }

    public function detailView($id){

        $item = Item::with(['categories','comments.user.profile'])->withCount('likes')->find($id);
        return view('detail',compact('item'));
    }

    public function search(Request $request){
        $keyword = $request->keyword;
        $items = Item::searchKeyword($keyword)->get();
        return view('index',compact('items'));
    }

}
