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
                    <h4 class="text-muted">TA 分享的文章</h4>
                    <ul class="list-unstyled">
                        @if(!empty($posts))
                            @foreach($posts as $post)
                                <hr>
                                <li class="media">
                                    <a href="{{ route('web.posts.show', ['id'=> $post->id]) }}" target="_blank" style="text-decoration:none;color:white;" >
                                        <div class="row">
                                            <div class="pl-3 mr-2">
                                                <img src="{{ env('CDN_DOMAIN').'/'.$post->cover}}" class="mr-3" alt="..." style="height: 8rem;">
                                            </div>
                                            <div class="media-body">
                                                <h5 class="mt-0 mb-1 text-muted">{{ $post->title }}</h5>
                                                <p class="text-muted">{{$post->description }}</p>
                                                <p class="text-muted">¥ {{ $post->price / 100 }}</p>
                                                <p class="card-text"><small class="text-muted">{{ $post->author }} | 发布于 {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }} | 阅读 {{ $post->readed }} | 评论 {{ $post->comments_count }} | 点赞 {{ $post->like }}</small></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
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
@stop