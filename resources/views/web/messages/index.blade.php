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
                    <h5 class="text-muted">我的私信</h5>
                    <hr>
                    @if($messages)
                        @foreach($messages as $message)
                            @if($message->sender_id == \Auth::user()->id)
                                <span class="text-muted">我发送给</span> <img class="rounded-circle" style="width:30px" src="{{ env('CDN_DOMAIN').'/'.$message->receiver_avatar }}" alt=""/> <span class="text-muted">{{ $message->receiver_name }}</span><small class="text-muted"> | {{ \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() }}:</small>
                                <div class="text-muted">
                                    {{ $message->content }}
                                <br>
                            </div>
                            @else
                                <img class="rounded-circle" style="width:30px" src="{{ env('CDN_DOMAIN').'/'.$message->receiver_avatar }}" alt=""/>
                                <span>{{ $message->receiver_name }} | </span>
                                <span class="text-muted">发送给我</span>
                                <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($message->created_at))->diffForHumans() }}</small>
                            @endif
                            <hr>
                        @endforeach
                    @endif
                    <br>
                </div>
            </div>
            <div class="col-3">
                <div class="right">
                    <div class="list-group">
                        <a href="{{ route('web.messages') }}" class="list-group-item list-group-item-action">我的私信</a>
                        <a href="{{ route('web.users.edit_avatar') }}" class="list-group-item list-group-item-action">我的通知</a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>

@stop