@extends('layout.layout')
@section('content')
  <h1>Laravel password reset</h1>
  <form action="{{url('/auth/password/reset')}}" method="post">
    @csrf
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
      <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>
    <!-- new password -->
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>
    <!-- confirm password -->
    <div class="mb-3">
      <label for="password_confirmation" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>
    <!-- token -->
    <input type="hidden" name="token" value="{{ $token }}">
    <button type="submit" class="btn btn-primary">Reset Password</button>
    @if (!empty($errors->all()))
      <div class="alert alert-danger my-3">
        <ul>
          @foreach ($errors->all() as $error)
            <li> {{ $error }} </li>
          @endforeach
        </ul>
      </div>
    @endif
    @if (!empty($successMessages))
      <div class="alert alert-success my-3">
        <ul>
          @foreach ($successMessages as $message)
            <li> {{ $message }} </li>
          @endforeach
        </ul>
      </div>
    @endif
  </form>
@endsection
