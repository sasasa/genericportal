@extends('layouts.app')
@section('title', 'ユーザー編集')

@section('content')
<h1>ユーザー編集</h1>

@include('components.errorAll')

<form action="{{ route('users.update', ['user' => $user]) }}" method="post" class="mt-5">
  @csrf
  @method('PATCH')
  <div class="form-group">
    <label for="name">{{__('validation.attributes.name')}}:</label>
    <input value="{{old('name', $user->name)}}" type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name">
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="email">{{__('validation.attributes.email')}}:</label>
    <input value="{{old('email', $user->email)}}" type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email">
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <button type="submit" class="btn btn-primary">更新</button>
</form>
@endsection
