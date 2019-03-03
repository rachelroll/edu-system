@extends('layout/layout')
<style>
    li:hover {
        background-color: #F0F0F0;
    }
</style>
@section('content')
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

@stop