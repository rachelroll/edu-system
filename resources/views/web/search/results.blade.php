@extends('layout.layout')

@section('content')
    <div class="container">
        <div class="col-9 pr-4">
            <ul class="list-unstyled">
                @if(!empty($posts))
                    @foreach($posts as $post)
                        <li class="media">
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
                @endif
            </ul>
        </div>
    </div>
    @stop