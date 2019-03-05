<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>֪知识付费平台-@yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
{{--导航--}}
<nav class=" navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">投资</a>
        <div class="collapse navbar-collapse flex-row" id="navbarSupportedContent">
            <ul class="navbar-nav mr-5">
                <li class="nav-item active">
                    <a class="nav-link" href="#">首页 <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">好文</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        系列课程
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('posts.create') }}" tabindex="-1" aria-disabled="true">写文章</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 ">
                <input class="form-control mr-sm-2 form-control-sm" type="search" placeholder="搜索" aria-label="Search">
                <button class="btn btn-outline-secondary btn-sm my-2 my-sm-0" type="submit">搜索</button>
            </form>
        </div>
        <div class="flex-row">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('wechat.login') }}">微信登录 <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </div>

</nav>
{{--.end 导航--}}
<br>
<br>
<br>
@yield('content')
</body>
<script src="{{ asset('js/app.js') }}"></script>
@yield('js')

</html>