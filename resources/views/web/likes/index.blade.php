@extends('layout.layout')

@section('style')
    <style>
        .left {
            /*margin: 0 4px;*/
            padding: 30px;
            background-color: white;
        }

        .right {
            /*margin: 0 4px;*/
        }
    </style>
@endsection('style)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-9">
                <div class="left">
                    <div>
                        <span class="text-muted">关于 "</span><span class="font-weight-bold"></span><span  class="text-muted">" 我的收藏:</span>
                    </div>
                    <hr>
                    <ul class="list-unstyled">
                        @if(!empty($posts_redis))
                            @foreach($posts_redis as $post)
                                <li class="media">
                                    <a href="{{ route('posts.show', ['id'=> $post['post_id']]) }}" target="_blank" style="text-decoration:none;color:white;">
                                        <div class="row">
                                            <div class="pl-3 mr-2">
{{--                                                <img src="{{ env('CDN_DOMAIN').'/'.$post->cover}}" class="mr-3" alt="..." style="height: 10rem;">--}}
                                            </div>
                                            <div class="media-body">
                                                <h5 class="mt-0 mb-1 text-muted">{{ $post['post_title'] }}</h5>
                                                <p class="text-muted">
                                                    {{$post['post_description'] }}
                                                </p>
{{--                                                <p class="text-muted">¥ {{ $post->price / 100 }}</p>--}}
{{--                                                <p class="card-text"><small class="text-muted">{{ $post->author }} . {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }}</small></p>--}}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <hr>
                            @endforeach
                        @endif

                        @if(!empty($post_mysql))
                                @foreach($post_mysql as $post)
                                    <li class="media">
                                        <a href="{{ route('posts.show', ['id'=> $post->id]) }}" target="_blank" style="text-decoration:none;color:white;">
                                            <div class="row">
                                                <div class="pl-3 mr-2">
{{--                                                    <img src="{{ env('CDN_DOMAIN').'/'.$post->cover}}" class="mr-3" alt="..." style="height: 10rem;">--}}
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1 text-muted">{{ $post->post_title }}</h5>
                                                    <p class="text-muted">
                                                        {{$post->post_description }}
                                                    </p>
                                                    {{--<p class="text-muted">¥ {{ $post->price / 100 }}</p>--}}
                                                    {{--<p class="card-text"><small class="text-muted">{{ $post->author }} . {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }}</small></p>--}}
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <hr>
                                @endforeach
                            @endif
                    </ul>
                </div>
            </div>
            <div class="col-3">
                <div class="right">
                    <div style="background-color: white">
                        <div class="card-header bg-transparent">
                            投资平台
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item text-muted">支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。</li>
                        </ul>
                    </div>
                    <br>
                    <div style="background-color: white">
                        <div class="card-header bg-transparent">
                            公告
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item text-muted">支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop