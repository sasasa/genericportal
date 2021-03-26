<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name=”robots” content=”noindex,nofollow”>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description')">

    <title>@yield('title') | 汎用CMS</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <a href="/">汎用CMS</a>
        <a href="{{ route('login') }}">ログイン</a>
    </header>

    <main class="container">
        @yield('breadcrumb')
        @yield('content')
    </main>

    <footer>footer</footer>
    @yield('script')
</body>
</html>
