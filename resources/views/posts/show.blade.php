@extends('layout/layout')

@section('content')
    <div>
        <h3 class="display-5">{{ $post->title }}</h3>
        <p class="text-muted">作者/分享人: {{ $post->author }}</p>
        <p class="text-muted">{{ $post->description }}</p>
        <hr class="my-4">
        <p>{{ $post->content }}</p>
        <div class="align-content-center">
            <a href="#" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">¥ {{ $post->price/100 }} 扫码投资</a>
        </div>
    </div>
    <hr>

    @if(!empty($post->comments))
        <p>讨论数量: </p>
        @foreach($post->comments as $comment)
            <div class="card">
                <div class="card-header">
                    {{ $comment->name }}
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                        <p>{{ $comment->comments }}</p>
                    </blockquote>
                    <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}</small>
                </div>
            </div>
            <br>
        @endforeach
    @endif
    <hr>
    <br>
    <form method="post" action="{{ route('comments.store') }}">
        <div class="form-group">
            <textarea name="comments" id="" cols="90" rows="6" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">评论</button>
    </form>
    <br>
    <br>
    @stop