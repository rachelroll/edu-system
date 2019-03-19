@extends('layout/layout')
@section('style')
    <link href="https://cdn.bootcss.com/highlight.js/9.15.6/styles/a11y-dark.min.css" rel="stylesheet">
    <style>
        .left {
            /*margin: 0 1px;*/
            padding: 30px;
            background-color: white;
        }

        .right {
            /*margin: 0 2px;*/
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        #heart {
            position: relative;
            width: 20px;
            height: 20px;
            margin: 0 12px 0 10px;
        }

        #heart:before, #heart:after {
            content: "";
            width: 12px;
            height: 20px;
            background: grey;
            border-radius: 32px 32px 0 0;
            position: absolute;
            left: 0;
            transform: rotate(-45deg);
            -moz-transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg);
        }

        #heart:after {
            left: 6px;
            transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
        }

    </style>
@endsection('style)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-9">
                <div class="left">
                    <div>
                        <h3 class="display-5 text-muted">{{ $post->title }}</h3>
                        <small class="text-muted">
                            创建于 {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }}
                            / 阅读数 {{ $readed }} / 评论数 {{ $post->comments_count }} /
                            更新于 {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->updated_at))->diffForHumans() }}</small>
                        <hr class="my-4">
                        <p class="text-muted">{{ $post->description }}</p>
                        <p class="p-2 text-muted">
                            {!! $post->content !!}
                        </p>

                        @if($post->is_free != 1)
                            <br>
                            <div class="center">
                                <button type="button" id="order" class="btn btn-secondary btn-block">
                                    扫码投资
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <br>
                <div class="left" style="padding:16px 30px">
                    <div class="row" id="like">
                        <div id="heart"></div>
                        <span class="text-muted"><span>{{ $like_counts }}</span> 人点赞</span>
                    </div>
                </div>
                <br>
                <div class="text-muted text-center">
                    <span>讨论数量: {{ $post->comments_count }}</span>
                </div>
                <hr>
                <br>
                @if($post->comments_count != 0)
                    @foreach($post->comments as $comment)
                        <div class="left">
                            <div class="row">
                                <div class="col-1">
                                    <img class="rounded-circle" style="width:50px; height:50px;"
                                         src="{{ env('CDN_DOMAIN').'/'.$comment->portrait }}" alt=""/>
                                </div>
                                <div class="card col-10 ml-3">
                                    <div class="d-flex pt-2 pb-2 border-bottom">
                                        <div class="mr-auto">{{ $comment->name }}</div>
                                        <div class="reply">回复</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-0">
                                            <p>{{ $comment->comments }}</p>
                                        </div>
                                        <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    @endforeach
                @endif
                <div class="left">
                    <form method="post" action="{{ route('comments.store') }}">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="form-group">
                            <textarea name="comments" id="comment" cols="90" rows="4" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary">发表评论</button>
                    </form>
                </div>
                <br>
                <br>
            </div>
            <div class="col-3">
                <div class="right">
                    <div class="pt-5" style="background-color: white">
                        <img src="{{ env('CDN_DOMAIN').'/'.$post->user->avatar }}" style="width:90px; height:90px;"
                             class="card-img-top rounded-circle center" alt="...">
                        <br>
                        <div class="text-muted text-center">{{ $post->user->name }}</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    @if($bool)
                                        <form action="{{ route('web.fans.cancel') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="author_id" value="{{ $post->user->id }}">
                                            <button type="submit" class="btn btn-secondary btn-sm btn-block">已关注
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('web.fans.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="author_id" value="{{ $post->user->id }}">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm btn-block">
                                                关注
                                            </button>
                                        </form>
                                    @endif
                                    <br>
                                    <a class="btn btn-outline-secondary btn-sm btn-block"
                                       href="{{ route('web.message.to', ['id' => $post->user->id]) }}"
                                       role="button">私信</a>
                                    <br>
                                        <a class="btn btn-outline-secondary btn-sm btn-block"
                                           href="{{ route('web.posts.user_collection', ['id' => $post->user->id]) }}"
                                           role="button">TA 的文章</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 二维码 -->
    <div class="modal fade" id="qrcode" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content bg-transparent" style="border:none">
                <div class="modal-body align-items-center text-center">
                    <p class="modal-title" id="exampleModalLabel" style="color:white">微信扫码支付</p>
                    <br>
                    {{--生成的二维码会放在这里--}}
                    <div id="qrcode2"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.bootcss.com/highlight.js/9.12.0/highlight.min.js"></script>
    <script>
        hljs.initHighlightingOnLoad();
        hljs.configure({useBR: true});

        $('div.code').each(function (i, block) {
            hljs.highlightBlock(block);
        });

        // 点赞
        $('#like').click(function () {
            // 指定 get 请求, 及请求的 url
            axios.post('/like', {
                //设置请求参数, 被赞文章的 ID
                id: "{{ $post->id }}",
                post_title: "{{ $post->title }}",
                post_description: "{{ $post->description }}",
            }).then(function (response) {
                var a = $('#like span span').text();
                if (response.data.code == 200) {
                    //如果返回 200, 则表示点赞成功, 将页面现实的点赞数 +1
                    $('#like span span').text(++a);
                    $('#heart:before').addClass('text-danger');
                    $('#heart:after').attr('background', '#fff');
                } else if (response.data.code == 202) {
                    //如果返回 200, 则表示取消点赞, 将页面现实的点赞数 -1
                    $('#like span span').text(--a);
                }
            }).catch(function (error) {
                console.log(error);
            });
        });

        // 点击购买
        $('#order').click(function () {
            axios.get("/payment/place_order", {
                params: {
                    id: "{{ $post->id }}"
                }
            }).then(function (response) {
                    console.log(response);
                if (response.data.code == 200) {
                    $('#qrcode2').html(response.data.html);
                    $('#qrcode').modal('show');
                    var timer = setInterval(function () {
                        axios.get('/payment/paid', {
                            params: {
                                'out_trade_no': response.data.order_sn,
                            }
                        })
                            .then(function (response) {
                                if (response.data.code == 200) {
                                    /** 取消定时器 */
                                    window.clearInterval(timer);
                                    window.location.reload();
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }, 300);
                }
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        // 指定回复某人
        $('.reply').click(function () {
            $('#comment').val('@'+ $(this).siblings().text() + ' ');
            $('#comment').focus();
        });
    </script>
@endsection























