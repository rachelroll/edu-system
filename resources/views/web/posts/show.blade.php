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

        .heart {
            position: relative;
            width: 20px;
            height: 20px;
            margin: 0 12px 0 10px;
        }
        .heart:before, .heart:after {
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
        .heart:after {
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
                        <small class="text-muted">创建于 {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }} / 阅读数 {{ $post->readed }} / 评论数 {{ $post->comments_count }} / 更新于 {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->updated_at))->diffForHumans() }}</small>
                        <hr class="my-4">
                        <p class="text-muted">{{ $post->description }}</p>
                        <p class="p-2 text-muted">
                            {!! $post->content !!}
                        </p>

                        @if($post->is_free != 1)
                            <br>
                            <div class="center">
                                <a href="#" class="btn btn-secondary btn-block" role="button" aria-pressed="true">¥ {{ $post->price/100 }} 扫码投资</a>
                            </div>
                        @endif
                    </div>
                </div>
                <br>
                <div class="left" style="padding:16px 30px">
                    <div class="row" id="like">
                        <div class="heart"></div>
                        <span class="text-muted"><span >{{ $like_counts }}</span> 人点赞</span>
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
                        </div>
                        <br>
                    @endforeach
                @endif
                <div class="left">
                    <form method="post" action="{{ route('comments.store') }}">
                        @csrf
                        <div class="form-group">
                            <textarea name="comments" id="" cols="90" rows="6" class="form-control"></textarea>
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
                        <img src="{{ env('CDN_DOMAIN').'/'.$post->user->avatar }}" style="width:90px; height:90px;" class="card-img-top rounded-circle center" alt="...">
                        <br>
                        <div class="text-muted text-center">{{ $post->user->name }}</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    @if($bool)
                                        <form action="{{ route('web.fans.cancel') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="author_id" value="{{ $post->user->id }}">
                                            <button type="submit" class="btn btn-secondary btn-sm btn-block">已关注</button>
                                        </form>
                                    @else
                                        <form action="{{ route('web.fans.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="author_id" value="{{ $post->user->id }}">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm btn-block">关注</button>
                                        </form>
                                    @endif
                                        <br>
                                        <a class="btn btn-outline-secondary btn-sm btn-block" href="{{ route('web.message.to', ['id' => $post->user->id]) }}" role="button">私信</a>
                                        <br>
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-block">TA 的文章</button>
                                </li>
                            </ul>
                        </div>
                    </div>
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

        $('div.code').each(function(i, block) {
            hljs.highlightBlock(block);
        });

        $('#like').click(function() {
            axios.get('/like', {
                params: {
                    id: "{{ $post->id }}"
                }
            }).then(function (response) {
                if (response.data.code == 200) {
                    var a = $('#like span span').text();
                    $('#like span span').text(++a);
                } else if (response.data.code == 202) {
                    var a = $('#like span span').text();
                    $('#like span span').text(--a);
                }}).catch(function (error) {
                    console.log(error);
                });
        });

    </script>

    @endsection