<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 未ログインは完全スルー（/ は誰でも見れる）
        if (!$user) {
            return $next($request);
        }

        // メール未認証
        if (!$user->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }

        if (!$user->profile) {

            if ($request->is('mypage/profile*')) {
                return $next($request);
            }

            return redirect('/mypage/profile');
        }

        return $next($request);
    }
}
