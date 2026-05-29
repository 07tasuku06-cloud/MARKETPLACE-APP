<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>coachtech</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>

    <header class="header">
        <div class="header__inner">

            <!-- logo -->
            <h1 class="header__logo">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </a>
            </h1>

            <!-- search -->
            <div class="header__search">
                <form action="/" method="GET">
                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="なにをお探しですか？">
                </form>
            </div>

            <!-- nav -->
            <nav class="header__nav">

                <div class="header__logout">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="logout-button">
                            ログアウト
                        </button>
                    </form>
                </div>

                <div class="header__mypage">
                    <a href="/mypage">マイページ</a>
                </div>

                <div class="header__sell">
                    <a href="/sell">出品</a>
                </div>

            </nav>

        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

</body>

</html>