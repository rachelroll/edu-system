@extends('layout.layout')
@section('style')
    <style>
        .left {
            margin: 0 4px;
            padding: 34px;
            background-color: white;
        }

        .right {
            background-color: white;
            margin: 0 4px;
        }
    </style>

    @endsection('style')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-9">
                <div class="left">
                    <h5 class="text-muted">修改资料</h5>
                    <hr>
                    <form action="{{ route('web.users.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="user_name" class="text-muted">用户名</label>
                            <input type="text" class="form-control" name="user_name" id="user_name" value="{{ $user_info->name }}">
                            <small class="form-text text-muted">用户名只能修改一次, 请谨慎操作</small>
                        </div>
                        <div class="form-group">
                            <label for="sex" class="text-muted">性别</label>
                            <select class="form-control" id="sex" name="sex" value="{{ $user_info->sex }}">
                                <option value=0>男</option>
                                <option value=1>女</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city" class="text-muted">城市</label>
                            <input type="text" name="city" class="form-control" id="city" value="{{ $user_info->city }}">
                            <small class="form-text text-muted">如: 北京, 广州</small>
                        </div>
                        <div class="form-group">
                            <label for="self_intro" class="text-muted">个人简介</label>
                            <input type="text" name="self_intro" class="form-control" id="self_intro" value="{{ $user_info->self_intro }}">
                            <small class="form-text text-muted">请一句话介绍你自己</small>
                        </div>

                        <img src="{{ 'http://' .env('CDN_DOMAIN').'/'. $user_info->payee_code }}" alt=""/>
                        <br>

                        <div class="form-group">
                            <label for="payee_code" class="text-muted">收款二维码</label>
                            <input type="file" name="payee_code" class="form-control" id="payee_code" value="{{ $user_info->payee_code }}">
                            <small class="form-text text-muted">微信支付二维码</small>
                        </div>
                        <button type="submit" class="btn btn-primary">提交</button>
                    </form>
                </div>
            </div>
            <div class="col-3">
                <div class="right">
                    <div class="list-group">
                        <a href="{{ route('web.users.edit') }}" class="list-group-item list-group-item-action">个人信息</a>
                        <a href="{{ route('web.users.edit_avatar') }}" class="list-group-item list-group-item-action">修改头像</a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>

    @stop