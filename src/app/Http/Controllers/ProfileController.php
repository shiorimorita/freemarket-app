<?php

namespace App\Http\Controllers;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function afterLogin()
    {
        
        $user=Auth::user();

        if(!$user->profile){
            return redirect('/mypage/profile');
        }

        return view('index');
    }

    /* profile view */
    public function profileView()
    {
        return view ('profile');
    }
    /* profile create*/
    public function profileCreate(Request $request)
    {
        $profile = $request->only(['post_code','address','building']);
        $profile['user_id'] = Auth::id();
        $image_path = $request->file('image_path')->store('images','public');
        $profile['image_path'] = $image_path;

        Profile::create($profile);
        return redirect('/');
    }

}
