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
        $tab = $request->tab ?? 'recommend';

        if ($request->has('keyword')) {
            // keyword パラメータが存在する

            if ($request->keyword === '') {
                // 空 → リセット
                session()->forget('search.keyword');
            } else {
                // 空でなければ保存
                session(['search.keyword' => $request->keyword]);
            }
        }

        // ▼ 3. セッションの keyword を取得
        $keyword = session('search.keyword', null);

        // ▼ 4. 商品取得
        if ($tab === 'mylist') {
            if (!$user) {
                $items = collect();
            } else {
                $items = Item::whereHas('likes', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                    ->searchKeyword($keyword)
                    ->get();
            }
        } else {
            $items = Item::withCount('likes')
                ->orderBy('likes_count', 'desc')
                ->searchKeyword($keyword)
                ->get();
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
