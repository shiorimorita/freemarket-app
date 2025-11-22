<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /* profile view */
    public function create()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /* profile store*/
    public function store(ProfileRequest $request)
    {
        $user = Auth::user();
        $profileData = $request->only(['post_code', 'address', 'building']);
        $profileData['user_id'] = $user->id;

        if ($request->hasFile('image_path')) {
            $profileData['image_path'] = $request->file('image_path')->store('images', 'public');
        }

        $user->name = $request->name;
        $user->save();

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect('/');
    }

    /* mypage view */
    public function mypage(Request $request)
    {
        if (! $request->has('page')) {
            return redirect('/mypage?page=sell');
        }

        $user = Auth::user();
        $page = $request->page;

        if ($page === 'sell') {
            $items = $user->items()->orderBy('created_at', 'desc')->get();
        } else {
            $items = $user->solds()
                ->with('item')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($sold) => $sold->item);
        }

        return view('mypage', compact('user', 'items', 'page'));
    }
}
