@extends('layouts.app')
@section('title', 'タグ新規作成')

@section('content')
<h1>タグ新規作成</h1>

@include('components.errorAll')
<form method="POST" action="{{route('tags.store')}}">
  @csrf

  <div class="form-group">
    <label for="tag">{{__('validation.attributes.tag')}}:</label>
    <input value="{{old('tag')}}" type="text" id="tag" class="form-control @error('tag') is-invalid @enderror" name="tag">
    @error('tag')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div>
    <button type="submit" class="btn btn-primary">登録</button>
  </div>
</form>
@endsection
