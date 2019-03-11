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
                        <p class="p-2">
                            {!! $post->content !!}
                        </p>

                        @if($post->is_free != 1)
                            <br>
                            <div class="center">
                                <a href="#" class="btn btn-outline-primary btn-block" role="button" aria-pressed="true">¥ {{ $post->price/100 }} 扫码投资</a>
                            </div>
                        @endif
                    </div>
                </div>
                <br>
                <br>
                <div class="text-muted text-center">
                    <span>讨论数量: {{ $post->comments_count }}</span>
                </div>
                <br>
                <div class="left">
                    @if(!empty($post->comments))
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
                    <br>
                    <form method="post" action="{{ route('comments.store') }}">
                        @csrf
                        <div class="form-group">
                            <textarea name="comments" id="" cols="90" rows="6" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">发表评论</button>
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
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-block">关注</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-block">私信</button>
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
    </script>

    @endsection