@extends('layouts.app')
@section('title', 'タグ編集')

@section('content')
<h1>タグ編集</h1>

@include('components.errorAll')
<form method="POST" action="{{route('tags.update', ['tag'=>$tag])}}">
  @csrf
  @method('PATCH')

  <div class="form-group">
    <label for="tag">{{__('validation.attributes.tag')}}:</label>
    <input value="{{old('tag', $tag->tag)}}" type="text" id="tag" class="form-control @error('tag') is-invalid @enderror" name="tag">
    @error('tag')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div>
    <button type="submit" class="btn btn-primary">更新</button>
  </div>
</form>
@endsection
