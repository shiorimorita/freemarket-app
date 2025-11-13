<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ItemRequest;
use App\Models\Category;
use App\Models\Like;
use Illuminate\Support\Facades\DB;

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

        return redirect('/');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $keyword = session('search.keyword');
        $tab = $request->tab ?? 'recommend';

        if ($tab !== 'mylist') {
            session()->forget('search.keyword');
            $keyword = null;
        } else {
            $keyword = session('search.keyword');
        }

        if ($tab === 'mylist') {

            if (!$user) {
                $items = collect();
            } else {
                $items = Item::whereHas('likes', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                    ->searchKeyword($keyword)   // ★ 検索適用
                    ->get();                    // ★ createget → get
            }
        } else {

            if ($user) {
                $items = Item::where('user_id', '!=', $user->id)
                    ->withCount('likes')
                    ->orderBy('likes_count', 'desc')
                    ->searchKeyword($keyword)   // ★ 検索適用
                    ->get();
            } else {
                $items = Item::withCount('likes')
                    ->orderBy('likes_count', 'desc')
                    ->searchKeyword($keyword)   // ★ 検索適用
                    ->get();
            }
        }

        return view('index', compact('items', 'tab', 'keyword'));
    }

    public function detail($id)
    {

        $item = Item::with(['categories', 'comments.user.profile'])->withCount('likes')->find($id);

        $isSold = DB::table('solds')
            ->where('item_id', $item->id)
            ->exists();

        $liked = Like::where('user_id', Auth::id())
            ->where('item_id', $id)
            ->exists();

        return view('detail', compact('item', 'isSold', 'liked'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $tab = $request->tab;

        session(['search.keyword' => $keyword]);
        $items = Item::searchKeyword($keyword)->get();

        return view('index', compact('items', 'keyword'));
    }
}
