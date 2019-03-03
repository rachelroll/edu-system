@extends('layout/layout')
<style>
    li:hover {
        background-color: #F0F0F0;
    }
</style>
@section('content')
    <div class="container">
        <div class="row no-gutters">
            <div class="col-9 col-md-9 pr-4">
                <ul class="list-unstyled">
                    @foreach($posts as $post)
                        <li class="media">
                            <img src="{{$post->cover}}" class="mr-3 img-thumbnail" alt="..." style="height: 10rem;">
                            <div class="media-body">
                                <h5 class="mt-0 mb-1 text-muted">{{ $post->title }}</h5>
                                <p class="text-muted">
                                    {{$post->description }}
                                </p>
                                <p class="text-muted">¥ {{ $post->price / 100 }}</p>
                                <p class="card-text"><small class="text-muted">{{ $post->author }} . {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }}</small></p>
                            </div>
                        </li>
                        <hr>
                    @endforeach
                </ul>
            </div>
            <div class="col-3 col-md-3">
                <div class="card" style="width: 17rem;">
                    <div class="card-header">
                        投资平台
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item text-muted">支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。</li>
                    </ul>
                </div>
                <br>
                <div class="card" style="width: 17rem;">
                    <div class="card-header">
                        公告
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item text-muted">支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop