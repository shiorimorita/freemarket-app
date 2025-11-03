<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function redirectToProfileSetup()
{
    $user = Auth::user();

    // プロフィール未登録ならプロフィール画面へ
    if (!$user->profile) {
        return redirect('/mypage/profile');
    }

    // 登録済みならトップページへ
    return redirect('/');
}

    /* profile view */
    public function profileView()
    {
        return view ('profile');
    }
    /* profile create*/
    public function profileCreate(ProfileRequest $request)
    {
        $profile = $request->only(['post_code','address','building']);
        $profile['user_id'] = Auth::id();
        $image_path = $request->file('image_path')->store('images','public');
        $profile['image_path'] = $image_path;

        Profile::create($profile);
        return redirect('/');
    }

}
