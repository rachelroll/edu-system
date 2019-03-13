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
                    <p class="text-muted text-center">私信给:</p>
                    <br>
                    <div class="text-center">
                        <img class="rounded-circle" style="width: 70px" src="{{ env('CDN_DOMAIN').'/'.$user->avatar }}" alt=""/>
                        <br>
                        <br>
                        <div class="text-muted">
                            {{ $user->name }}
                        </div>
                    </div>
                    <br>
                    <form action="{{ route('web.message.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="hidden" name="receiver_name" value="{{ $user->name }}">
                        <input type="hidden" name="receiver_avatar" value="{{ $user->avatar }}">
                        <div class="input-group">
                            <textarea class="form-control" rows="10" name="content"></textarea>
                        </div>
                        <br>
                        <div class="pl-5 pr-5">
                            <button type="submit" class="btn btn-info btn-block">发送私信</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-3">
                <div class="right">
                    <div class="list-group">
                        <a href="{{ route('web.users.edit') }}" class="list-group-item list-group-item-action">我的私信</a>
                        <a href="{{ route('web.users.edit_avatar') }}" class="list-group-item list-group-item-action">我的通知</a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>

@stop