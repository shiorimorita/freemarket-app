<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;

class ProfileController extends Controller
{
    /* profile view */
    public function create()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }
    /* profile create*/
    public function store(ProfileRequest $request)
    {
        $profileData = $request->only(['post_code', 'address', 'building']);
        $profileData['user_id'] = Auth::id();

        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('images', 'public');
            $profileData['image_path'] = $image_path;
        } else {
            $image_path = null;
        }

        $user = Auth::user();
        $user->name = $request->name;
        /** @var \App\Models\User $user */
        $user->save();

        Profile::updateOrCreate(
            ['user_id' => $profileData['user_id']],
            $profileData
        );

        return redirect('/?tab=mylist');
    }

    public function mypage(Request $request)
    {
        $user = Auth::user();

        if (!$user->profile) {
            return view('mypage', [
                'user' => $user,
                'items' => [],
                'page' => null,
            ]);
        }

        if (! $request->has('page')) {
            return redirect('/mypage?page=sell');
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $items = $user->items;

        $page = $request->page;

        if ($page === 'sell') {
            $items = $user->items()->orderBy('created_at', 'desc')->get();
        } else {
            $user_id = $user->id;

            $items = Item::join('solds', 'solds.item_id', '=', 'items.id')
                ->where('solds.user_id', $user_id)
                ->orderBy('solds.created_at', 'desc')
                ->select('items.*')
                ->get();
        }

        return view('mypage', compact('user', 'items', 'page'));
    }
}
