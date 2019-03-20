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
            /*margin: 0 4px;*/
        }
    </style>

@endsection('style')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-9">
                <div class="left">
                    <h5 class="text-muted">我的通知</h5>
                    <hr>
                    @if(!empty($notifications))
                        @foreach($notifications as $notification)
                            <div class="row">
                                <div class="col-1">
                                    <img class="rounded-circle" style="width:37px" src="{{ env('CDN_DOMAIN').'/'.$notification->sender_portrait }}" alt=""/>
                                </div>
                                <div class="col-11 pl-0">
                                    <div class="d-flex">
                                        @if($notification->post_author_id == $notification->user_id)
                                            <div class="mr-auto">
                                                <span>{{ $notification->sender_name }} | </span>
                                                <span class="text-muted">回复了你的话题: </span>
                                                <a href="{{ route('web.posts.show', ['id' => $notification->post_id]) }}">{{ $notification->post_title }}</a>
                                            </div>
                                        @else
                                            <div class="mr-auto">
                                                <span>{{ $notification->sender_name }} | </span>
                                                <span class="text-muted">在 </span>
                                                <a href="{{ route('web.posts.show', ['id' => $notification->post_id]) }}">{{ $notification->post_title }}</a>
                                                <span> 的评论中提到了你</span>
                                            </div>
                                        @endif
                                        <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans() }}</small>
                                    </div>
                                    <div>{{ $notification->content }}</div>
                                </div>
                            </div>

                            <hr>
                        @endforeach
                    @else
                        <div>尚无通知</div>
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