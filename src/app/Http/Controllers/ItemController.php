<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemRequest;
use App\Models\Category;
use App\Models\Like;

class ItemController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(ItemRequest $request)
    {
        $data = $request->only(['name', 'brand', 'description', 'price', 'condition']);
        $data['user_id'] = Auth::id();
        $data['image_path'] = $request->file('image_path')->store('images', 'public');

        $item = Item::create($data);
        $item->categories()->attach($request->category_ids);

        return redirect('/')->with('success', '出品が完了しました。出品した商品はマイページよりご確認ください。');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->tab ?? 'recommend';

        if ($request->has('keyword')) {
            if ($request->keyword === '') {
                session()->forget('search.keyword');
            } else {
                session(['search.keyword' => $request->keyword]);
            }
        } elseif (!$request->has('tab')) {
            session()->forget('search.keyword');
        }

        $keyword = session('search.keyword');

        if ($tab === 'mylist') {
            if (!$user) {
                $items = collect();
            } else {
                $items = $user->likesItem()
                    ->with('sold')
                    ->orderByPivot('created_at', 'desc')
                    ->searchKeyword($keyword)
                    ->get();
            }
        } else {
            $items = Item::with(['sold'])
                ->withCount('likes')
                ->when($user, fn($q) => $q->where('user_id', '!=', $user->id))
                ->orderBy('likes_count', 'desc')
                ->searchKeyword($keyword)
                ->get();
        }

        return view('index', compact('items', 'tab', 'keyword'));
    }

    public function detail($item_id)
    {
        $item = Item::with(['categories', 'comments.user.profile', 'sold'])
            ->withCount('likes', 'comments')
            ->findOrFail($item_id);

        if (Auth::user()) {
            $liked = Like::where('user_id', Auth::id())
                ->where('item_id', $item_id)
                ->exists();
        } else {
            $liked = false;
        }

        return view('detail', compact('item', 'liked'));
    }
}
