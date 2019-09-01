<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="borderbox">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>֪知识付费平台-@yield('title')</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}"
          crossorigin="anonymous">
    <style>
        .swal2-modal {
            top:-140px;
        }
    </style>
    @yield('css')
    @yield('style')

</head>
<body>
{{--导航--}}
<nav class=" navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('web.posts.index') }}">投资</a>
        <div class="collapse navbar-collapse flex-row" id="navbarSupportedContent">
            <ul class="navbar-nav mr-5">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('web.posts.index') }}">首页 <span class="sr-only">(current)</span></a>
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
                    <a class="nav-link" href="{{ route('web.posts.create') }}" tabindex="-1"
                       aria-disabled="true">写文章</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" id="elasticScout" action="/SearchQuery" method="get">
                <input class="form-control mr-sm-2 form-control-sm" type="search" placeholder="搜索" aria-label="Search"
                       name="search">
                <button class="btn btn-info btn-sm text-white" type="submit">搜索</button>
            </form>
        </div>
        <div class="flex-row">
            <ul class="navbar-nav mr-auto">
                @if(! \Auth::user())
                    <li class="nav-item active">
                        <a class="nav-link" id="nav-login" >微信登录 <span
                                class="sr-only">(current)</span></a>
                        <a class="nav-link"  href="{{ route('wechat.login') }}" >测试登录 <span
                                class="sr-only">(current)</span></a>
                    </li>
                @else
                    <li class="nav-item align-self-center">
                        <div class="notes-count rounded">
                            <a href="{{ route('web.notifications.index') }}" class="mr-3"
                               style="color: rgb(51, 51, 51)">{{ $notifications_count }}</a>
                        </div>

                    </li>
                    <div class="dropdown">
                        <a class="btn dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            @if(Auth::user()->avatar)
                                <img class="rounded" style="width:30px; height:30px;"
                                     src="{{ env('CDN_DOMAIN').'/'.Auth::user()->avatar }}" alt=""/>
                                @else
                                <img class="rounded" style="width:30px; height:30px;"
                                     src="{{ Auth::user()->headimgurl }}" alt=""/>
                            @endif
                            <span class="text-muted ml-1">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item text-muted" href="{{ route('web.users.posts') }}">我的文章</a>
                            <a class="dropdown-item text-muted" href="{{ route('web.likes') }}">我的赞(收藏)</a>
                            <a class="dropdown-item text-muted" href="{{ route('web.users.edit') }}">编辑资料</a>
                            <a class="dropdown-item text-muted" href="{{ route('logout') }}">退出</a>
                        </div>
                    </div>
                @endif
            </ul>
        </div>
    </div>
</nav>

{{--.end 导航--}}
<br>

@yield('content')
</body>
<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script>

        @foreach(['success','info','error','warning'] as $type)
        @if(session($type))
    var $type = '{{ $type }}';
    var message = '{{ session($type) }}';
    toastr.options.progressBar = true;
    toastr['success']('nihskdfjskd');
    // toastr[$type](message);
    @endif
        @endforeach

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.options.progressBar = true;
    toastr['error']('{{ $error }}');
    @endforeach
    @endif
    $('#nav-login').on('click',function(){
        Swal.fire({
            // imageUrl: 'https://unsplash.it/800/800',
            // imageWidth: 600,
            // imageHeight: 400,
            // imageAlt: 'Custom image',
            animation: false,
            html:'<div id="login_container"></div>',
            showConfirmButton: false
        })
        var query_string = '{{ request()->getQueryString() }}';
        var url_path = '{{ request()->path() }}';
        if (query_string) {
            url_path = url_path + '?' + url_path
        }
        var redirect_uri = 'https://www.jkwedu.net/call-back?redirect=http://edu-system.test/auth/oauth-callback?path=' + url_path;
        var obj = new WxLogin({
            self_redirect: false,
            id:"login_container",
            appid: "wx8027a980640d6d72",
            scope: "snsapi_login",
            redirect_uri: encodeURI(redirect_uri),
            state: "wechat_redirect",
            style: "",
            href: ""
        });
    })



</script>
@yield('js')
@yield('script')

</html>
