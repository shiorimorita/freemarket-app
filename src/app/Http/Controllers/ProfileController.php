<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        $user = Auth::user();
        return view ('profile',compact('user'));
    }
    /* profile create*/
    public function profileCreate(ProfileRequest $request)
    {
        $profile = $request->only(['post_code','address','building']);
        $profile['user_id'] = Auth::id();
        $image_path = $request->file('image_path')->store('images','public');
        $profile['image_path'] = $image_path;

        Profile::updateOrCreate(
            ['user_id' =>$profile['user_id']],
            $profile
        );
        return redirect('/');
    }

    public function mypage(Request $request)
    {
        if(! $request->has('page')){
            return redirect('/mypage?page=sell');
        }

        $user = Auth::user();
        $items = $user->items;

        $page = $request->page;

        if($page === 'sell'){
            $items = $user->items;
            /* 未実装 */
        }else{
            $items = collect([]);
        }

        return view('mypage',compact('user','items','page'));
    }

}
