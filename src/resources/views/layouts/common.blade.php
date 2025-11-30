<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>freemarket-app</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    @yield('js')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <h1 class="header__logo">
                <a href="/" class="header__logo-link">
                    <img src="{{ asset('storage/images/logo.svg') }}" alt="Coachtech freemarket ロゴ" class="header__logo-img">
                </a>
            </h1>
            @unless (request()->is('login', 'register', 'verify-email'))
            <form class="header__search-form" action="/" method="get">
                <input id="keyword" type="search" name="keyword" class="header__search-input" placeholder="なにをお探しですか？" value="{{request('keyword')}}">
                @if(!empty($tab)&& $tab !=='recommend')
                <input type="hidden" name="tab" value="{{ $tab }}">
                @endif
            </form>
            <nav class="header__nav">
                <ul class="header__nav-list">
                    @if (Auth::check())
                    <li class="header__nav-item">
                        <form action="/logout" method="post" class="header__logout-form">
                            @csrf
                            <button type="submit" class="header__nav-button">ログアウト</button>
                        </form>
                    </li>
                    @else
                    <li class="header__nav-item">
                        <a href="/login" class="header__nav-link">ログイン</a>
                    </li>
                    @endif
                    <li class="header__nav-item">
                        <a href="/mypage" class="header__nav-link">マイページ</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="/sell" class="header__sell-link">出品</a>
                    </li>
                </ul>
            </nav>
            @endunless
        </div>
    </header>
    <div class="content">
        @yield('content')
    </div>
</body>

</html>