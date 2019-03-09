<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>֪知识付费平台-@yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background-color: whitesmoke;
        }
        .triangle-down  {
            width: 0;
            height: 0;
            border-top: 5px solid dimgrey;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
        }

        nav {
            background-color: white;
        }
    </style>
    @yield('style')

</head>
<body>
{{--导航--}}
<nav class=" navbar navbar-expand-lg navbar-light">
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
            <form class="form-inline my-2 my-lg-0" id="elasticScout" action="/SearchQuery" method="get">
                <input class="form-control mr-sm-2 form-control-sm" type="search" placeholder="搜索" aria-label="Search" name="search">
                <button class="btn btn-outline-secondary btn-sm my-2 my-sm-0" type="submit">搜索</button>
            </form>
        </div>
        <div class="flex-row">
            <ul class="navbar-nav mr-auto">
                @if(! Illuminate\Support\Facades\Auth::user())
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('wechat.login') }}">微信登录 <span class="sr-only">(current)</span></a>
                </li>
                @else
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><img class="img-circle" style="width:30px; height:30px;" src="{{ 'http://' .env('CDN_DOMAIN').'/'.Illuminate\Support\Facades\Auth::user()->avatar }}" alt=""/></span>
                            <span>{{ Illuminate\Support\Facades\Auth::user()->name }}</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('web.users.posts') }}">我的文章</a>
                            <a class="dropdown-item" href="{{ route('web.users.edit') }}">编辑资料</a>
                            <a class="dropdown-item" href="{{ route('logout') }}">退出</a>
                        </div>
                    </div>
                @endif
            </ul>
        </div>
    </div>

</nav>
{{--.end 导航--}}
<br>
@if (session('success'))
    <div class="container">
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
<br>
@yield('content')
</body>
<script src="{{ asset('js/app.js') }}"></script>
@yield('js')

</html>