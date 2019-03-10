@extends('layout/layout')
@section('style')
    <style>
        .left {
            margin: 0 4px;
            padding: 34px;
            background-color: white;
        }

        .right {
            margin: 0 4px;
        }

    </style>
@endsection('style)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-9">
                <div class="left">
                    <div>
                        <h3 class="display-5">{{ $post->title }}</h3>
                        <p class="text-muted">作者/分享人: {{ $post->author }}</p>
                        <p class="text-muted">{{ $post->description }}</p>
                        <hr class="my-4">
                        <p class="p-2">{{ $post->content }}</p>
                        <div class="align-content-center">
                            <a href="#" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">¥ {{ $post->price/100 }} 扫码投资</a>
                        </div>
                    </div>
                    <hr>
                    @if(!empty($post->comments))
                        <p>讨论数量: </p>
                        @foreach($post->comments as $comment)
                            <div class="row">
                                <div class="col-1">
                                    <img class="rounded-circle" style="width:50px; height:50px;" src="{{ env('CDN_DOMAIN').'/'.$comment->portrait }}" alt=""/>
                                </div>
                                <div class="card col-10 ml-3">
                                    <div class="card-header bg-transparent">
                                        {{ $comment->name }}
                                    </div>
                                    <div class="card-body">
                                        <blockquote class="blockquote mb-0">
                                            <p>{{ $comment->comments }}</p>
                                        </blockquote>
                                        <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                    @endif
                    <hr>
                    <br>
                    <form method="post" action="{{ route('comments.store') }}">
                        @csrf
                        <div class="form-group">
                            <textarea name="comments" id="" cols="90" rows="6" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">发表评论</button>
                    </form>
                    <br>
                    <br>
                </div>
            </div>
            <div class="col-3">
                <div class="right">
                    <div class="card" style="width: 17rem;">
                        <div class="card-header bg-transparent">
                            投资平台
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item text-muted">支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。</li>
                        </ul>
                    </div>
                    <br>
                    <div class="card" style="width: 17rem;">
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