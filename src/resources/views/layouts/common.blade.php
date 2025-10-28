<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coachtech freemarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz@0,14..32;1,14..32&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <h1 class="header-logo">
                <img src="{{ asset('storage/images/logo.svg') }}" alt="Coachtech freemarket ロゴ" class="header__logo-img">
            </h1>
            <form class="search-form" action="/search" method="get">
                <input id="keyword" type="search" name="keyword" class="header__search-input" placeholder="なにをお探しですか？">
            </form>
            <nav class="header-nav">
                <ul class="nav__list">
                    <li class="header-nav__item">
                        <form action="/logout" method="post">
                            @csrf
                            <button type="submit" class="nav__logout-button">ログアウト</button>
                        </form>
                    </li>
                    <li class="header-nav__item"><a href="/mypage" class="nav__link">マイページ</a></li>
                    <li class="header-nav__item"><a href="/items/new" class="nav__link">出品</a></li>
                </ul>
            </nav>
        </div>
    </header>
    @yield('content')
</body>

</html>