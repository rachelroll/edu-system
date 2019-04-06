@extends('layout/layout')
@section('content')
    <div class="container" style="max-width: 1180px;">
        <div class="row">
            <div class="col-8">
                <div class="bd-example">
                    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                            <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner rounded">
                            <div class="carousel-item active ">
                                <img src="https://via.placeholder.com/800x300/D95353/ccc?text=ross" class="d-block w-100 rounded scale-big" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>First slide label</h5>
                                    <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                                </div>
                            </div>
                            <div class="carousel-item ">
                                <img src="https://via.placeholder.com/800x300/D95353/ccc?text=ross" class="d-block w-100 rounded scale-big" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Second slide label</h5>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://via.placeholder.com/800x300/D95353/ccc?text=ross" class="d-block w-100 rounded scale-big" alt="...">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Third slide label</h5>
                                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev " href="#carouselExampleCaptions" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next " href="#carouselExampleCaptions" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <br>
                <ul class="list-unstyled infinite-scroll">
                    @if(!empty($posts))
                        @foreach($posts as $post)
                            <li class="r-shadow-wrapper">

                                <div class="r-shadow-wrapper-card d-flex">
                                    <div class="mr-4 wrap-img">
                                        <a class="" href="{{ route('web.posts.show', ['id'=> $post->id]) }}"
                                           target="_blank"
                                           style="text-decoration:none;">
                                            <img src="{{ config('edu.cdn_domain').'/'.$post->cover}}"
                                                 class="rounded scale-big" alt="..."
                                            >
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div style="height: 104px;">
                                            <h5 class=" line-height-24"><a
                                                        class="main-text"
                                                        href="{{ route('web.posts.show', ['id'=> $post->id]) }}"
                                                        target="_blank"
                                                        style="text-decoration:none;"
                                                        class="a-hover">{{ $post->title }}</a></h5>


                                            <p class="text-desc">
                                                <a
                                                        class="main-text"
                                                        href="{{ route('web.posts.show', ['id'=> $post->id]) }}"
                                                        target="_blank"
                                                        style="text-decoration:none;">{{$post->description }}</a>
                                            </p>

                                        </div>
                                        <div>
                                            <span class="text-danger">¥ {{ $post->price }}</span>
                                            <p class="card-text float-right">
                                                <small class="text-muted">{{ $post->author }} |
                                                    发布于 {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }}
                                                    | 阅读 {{ $post->readed }} | 评论 {{ $post->comments_count }} |
                                                    点赞 {{ $post->like }}</small>
                                            </p>
                                        </div>

                                    </div>
                                </div>

                            </li>
                        @endforeach
                    @endif

                    <div class="text-center mt-3">
                        {{--判断到最后一页就终止, 否则 jscroll 又会从第一页开始一直循环--}}
                        @if( $posts->currentPage() == $posts->lastPage())
                            <span class="text-center text-muted">没有更多了</span>
                        @else
                            {{-- 这里调用 paginator 对象的 nextPageUrl() 方法, 以获得下一页的路由 --}}
                            <a class="jscroll-next btn btn-outline-secondary btn-block rounded-pill"
                               href="{{ $posts->nextPageUrl() }}">
                                加载更多....
                            </a>
                        @endif
                    </div>
                </ul>
            </div>
            <div class="col-4">
                <div>
                    <div class="card-header bg-transparent">
                        投资平台
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item main-text">
                            支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。
                        </li>
                    </ul>
                </div>
                <br>
                <div style="background-color: white">
                    <div class="card-header bg-transparent">
                        公告
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item main-text">
                            支付完成后，微信会把相关支付结果及用户信息通过数据流的形式发送给商户，商户需要接收处理，并按文档规范返回应答。
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.bootcss.com/jscroll/2.4.1/jquery.jscroll.min.js"></script>
    <script>
        $(function () {
            var options = {
                debug: true,
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



