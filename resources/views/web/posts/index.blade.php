@extends('layout/layout')
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

    a {
        /*text-decoration: none;*/
        /*color: white;*/
    }
</style>
@endsection('style)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-9">
                <div class="left">
                    <ul class="list-unstyled infinite-scroll">
                        @if(!empty($posts))
                            @foreach($posts as $post)
                                <li class="media d-flex align-items-end">
                                    <a href="{{ route('web.posts.show', ['id'=> $post->id]) }}" target="_blank" style="text-decoration:none;">
                                        <div class="row">
                                            <div class="pl-3 mr-2">
                                                <img src="{{ env('CDN_DOMAIN').'/'.$post->cover}}" class="mr-3" alt="..." style="height: 8rem;">
                                            </div>
                                            <div class="media-body">
                                                <h5 class="mt-0 mb-1 main-text">{{ $post->title }}</h5>
                                                <p class="main-text">{{$post->description }}</p>
                                                <p class="main-text">¥ {{ $post->price / 100 }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="ml-auto">
                                        <p class="card-text"><small class="text-muted">{{ $post->author }} | 发布于 {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }} | 阅读 {{ $post->readed }} | 评论 {{ $post->comments_count }} | 点赞 {{ $post->like }}</small></p>
                                    </div>
                                </li>
                                <hr>
                            @endforeach
                        @endif

                        <div class="text-center">
                            {{--判断到最后一页就终止, 否则 jscroll 又会从第一页开始一直循环--}}
                            @if( $posts->currentPage() == $posts->lastPage())
                                <span class="text-center text-muted">没有更多了</span>
                            @else
                                {{-- 这里调用 paginator 对象的 nextPageUrl() 方法, 以获得下一页的路由 --}}
                                <a class="jscroll-next btn btn-outline-secondary btn-block rounded-pill" href="{{ $posts->nextPageUrl() }}">
                                    加载更多....
                                </a>
                            @endif
                        </div>
                    </ul>
                </div>
            </div>
            <div class="col-3">
                <div class="right">
                    <div>
                        <div class="card-header bg-transparent">
                            投资平台
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item main-text">支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。</li>
                        </ul>
                    </div>
                    <br>
                    <div style="background-color: white">
                        <div class="card-header bg-transparent">
                            公告
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item main-text">支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.bootcss.com/jscroll/2.4.1/jquery.jscroll.min.js"></script>
    <script>
        $(function() {
            var options = {
                debug:true,
                // 当滚动到底部时,自动加载下一页
                autoTrigger: true,
                // 限制自动加载, 仅限前两页, 后面就要用户点击才加载
                autoTriggerUntil: 1,
                // 设置加载下一页缓冲时的图片
                loadingHtml: '<img class="align-self-center" src="/img/loading.jpg" alt="Loading..." style="width: 80px"/>',
                //设置距离底部多远时开始加载下一页
                padding: 0,
                // nextSelector: '.pagination li.active + li a',
                nextSelector: 'a.jscroll-next:last',
                // 下一个自动加载的位置
                contentSelector: 'ul.infinite-scroll',
            };
            $('.infinite-scroll').jscroll(options);
        });
    </script>

    @stop



