@extends('layout/layout')
<style>
    li:hover {
        background-color: #F0F0F0;
    }
    a:link {
        text-decoration: none;
    }
    a:visited {
        text-decoration: none;
    }
    a:hover {
        text-decoration: none;
    }
    a:active {
        text-decoration: none;
    }
</style>
@section('content')
    <div class="container">
        <div class="row no-gutters">
            <div class="col-9 col-md-9 pr-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted">所有文章</h5>
                        <hr>
                    </div>
                    <ul class="list-unstyled">
                        @foreach($posts as $post)
                            <li class="media card-body">
                                <a href="{{ route('posts.show', ['id'=> $post->id]) }}">
                                    <div class="row">
                                        <img src="{{$post->cover}}" class="mr-3 img-thumbnail" alt="..." style="height: 10rem;">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1 text-muted">{{ $post->title }}</h5>
                                            <p class="text-muted">
                                                {{$post->description }}
                                            </p>
                                            <p class="text-muted">¥ {{ $post->price / 100 }}</p>
                                            <p class="card-text"><small class="text-muted">{{ $post->author }} . {{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() }}</small></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <hr>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-3 col-md-3">
                <div class="card" style="width: 17rem;">
                    <div class="card-body">
                        投资平台
                        <hr>
                        <span>文章</span>
                    </div>
                </div>
                <br>
                <div class="card" style="width: 17rem;">
                    <div class="card-body text-center">
                        <a href="{{ route('web.posts.create') }}">撰写文章</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop