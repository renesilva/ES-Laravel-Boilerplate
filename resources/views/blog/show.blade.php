@extends('layout.layout')
@section('content')
  <h1>{{$post->title}}</h1>
  <p>{{$post->content}}</p>
  <h2>Comentarios</h2>
  <form action="{{url('/api/comments')}}" method="POST">
    <div class="mb-3">
      <label for="name" class="form-label">Nombre</label>
      <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="mb-3">
      <label for="comment" class="form-label">Tu comentario</label>
      <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
    </div>
    <div class="mb-3">
      <input type="hidden" name="post_id" value="{{$post->id}}">
      <input type="submit" value="Enviar comentario" class="btn btn-primary">
    </div>
  </form>
  @foreach($post->comments->reverse() as $comment)
    <div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">{{$comment->name}}</h5>
        <p class="card-text">
          {{$comment->comment}}
        </p>
        <span class="card-link">
          <small>
            {{$comment->created_at->diffForHumans()}}
          </small>
        </span>
      </div>
    </div>
  @endforeach
@endsection
