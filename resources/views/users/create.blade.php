@extends('layouts.app')
@section('title', 'ユーザー新規作成')

@section('content')
<h1>ユーザー新規作成</h1>

@include('components.errorAll')

<form action="{{ route('users.store') }}" method="post" class="mt-5" autocomplete="off">
  <input type="password" name="dummy_pass1" style="position:absolute;top:-200px;" disabled>
  <input type="email" name="dummy_email1" style="position:absolute;top:-200px;" disabled>
  @csrf
  <div class="form-group">
    <label for="name">{{__('validation.attributes.name')}}:</label>
    <input value="{{old('name')}}" type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name">
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="email">{{__('validation.attributes.email')}}:</label>
    <input value="{{old('email')}}" type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" autocomplete="new-email">
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="password">{{__('validation.attributes.password')}}:</label>
    <input value="{{old('password')}}" type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="password_confirmation">{{__('validation.attributes.password_confirmation')}}:</label>
    <input value="{{old('password_confirmation')}}" type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
    @error('password_confirmation')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>


  <button type="submit" class="btn btn-primary">登録</button>
</form>
@endsection

