@extends('layouts.app')
@section('title', 'パスワード変更')

@section('content')
<h1>パスワード変更</h1>

@include('components.errorAll')

<form action="{{ route('users.update_pass', ['user' => $user]) }}" method="post" class="mt-5" autocomplete="off">
  @csrf
  @method('PATCH')

  <div class="form-group">
    <label for="password">{{__('validation.attributes.password')}}:</label>
    <input value="{{old('password')}}" type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="off">
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


  <button type="submit" class="btn btn-primary">変更</button>
</form>
@endsection

@section('script')
<script type="module">
</script>
@endsection