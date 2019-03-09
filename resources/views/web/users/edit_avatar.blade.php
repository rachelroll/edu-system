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
                    <h5 class="text-muted">修改头像</h5>
                    <hr>
                    <img src="{{ 'http://' .env('CDN_DOMAIN').'/'.\Illuminate\Support\Facades\Auth::user()->avatar }}" alt=""/>
                    <br>
                    <br>
                    <form action="{{ route('web.users.store_avatar') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="avatar" class="text-muted">请选择图片：</label>
                            <input type="file" name="avatar" class="form-control" id="avatar">
                        </div>
                        <button type="submit" class="btn btn-primary">上传头像</button>
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