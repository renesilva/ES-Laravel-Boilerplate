@extends('layout.layout')
@section('content')
  <h1>Posts</h1>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

    @foreach($posts as $post)
      <div class="col">
        <div class="card shadow-sm">
          <div class="card-body">
            <p class="card-text">{{$post->title}}</p>
            <div class="d-flex justify-content-between align-items-center">
              <div class="btn-group">
                <a href="{{ url('/blog/'.$post->id.'/'.$post->slug) }}"
                   class="btn btn-sm btn-outline-secondary">Ver</a>
              </div>
              <small class="text-body-secondary">
                <i>By {{$post->user->name}}</i>
                {{$post->created_at->diffForHumans()}}
              </small>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection
