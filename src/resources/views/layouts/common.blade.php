<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coachtech freemarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <h1 class="header-logo">
                <a href="/" class="header-logo__link">
                    <img src="{{ asset('storage/images/logo.svg') }}" alt="Coachtech freemarket ロゴ" class="header__logo-img">
                </a>
            </h1>
            <form class="search-form" action="/search" method="get">
                <input id="keyword" type="search" name="keyword" class="header__search-input" placeholder="なにをお探しですか？">
            </form>
            <nav class="header-nav">
                <ul class="header-nav__list">
                    @if(Auth::check())
                    <li class="header-nav__item">
                        <form action="/logout" method="post" class="logout__form">
                            @csrf
                            <button type="submit" class="header-nav__form">ログアウト</button>
                        </form>
                    </li>
                    @else
                    <li class="header-nav__item">
                        <a href="/login" class="header-nav__link">ログイン</a>
                    </li>
                    @endif
                    <li class="header-nav__item">
                        <a href="/mypage" class="header-nav__link">マイページ</a>
                    </li>
                    <li class="header-nav__item">
                        <a href="/sell" class="header-nav__link--sell">出品</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    @yield('content')
</body>

</html>