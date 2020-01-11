<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}"
          crossorigin="anonymous">
    <script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
    <title>微信登录</title>
</head>
<body>
<div style="height: 60px;"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">登录</div>
                {{--                微信登录--}}

                <div class="row">
                    <div class="d-block mx-auto">
                        <div id="login_container"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var redirect_uri = 'https://www.jkwedu.net/callback?redirect=http://edu-system.test/auth/oauth-callback';
    var obj = new WxLogin({
        self_redirect: false,
        id:"login_container",
        appid: "wx8027a980640d6d72",
        scope: "snsapi_login",
        redirect_uri: encodeURI(redirect_uri),
        state: "#wechat_redirect",
        style: "",
        href: ""
    });
</script>

</body>
</html>

