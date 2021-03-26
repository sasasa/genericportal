@extends('layouts.app')
@section('title', 'アイテム編集')

@section('content')
<h1>アイテム編集</h1>

@include('components.errorAll')

<form action="{{route('items.update', ['item'=>$item])}}" method="post" class="mt-5" enctype='multipart/form-data'>
  @csrf
  @method('PATCH')

  <div class="form-group">
    <label for="item_name">{{__('validation.attributes.item_name')}}:</label>
    <input value="{{old('item_name', $item->item_name)}}" type="text" id="item_name" class="form-control @error('item_name') is-invalid @enderror" name="item_name">
    @error('item_name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="description">{{__('validation.attributes.description')}}:</label>
    <input value="{{old('description', $item->description)}}" type="text" id="description" class="form-control @error('description') is-invalid @enderror" name="description">
    @error('description')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    meta descriptionに表示されます
  </div>

  <div class="form-group">
    <label for="body">{{__('validation.attributes.body')}}:</label>
    <textarea rows="10" id="body" class="form-control @error('body') is-invalid @enderror" name="body">{{old('body', $item->body)}}</textarea>
    @error('body')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    {{ $allowedTags }}タグを利用できます
  </div>

  <div class="form-group mt-5">
    <img src="/storage/{{$item->image_path}}?{{ \Str::random(8) }}">
    <label>
      <input type="checkbox" id="delete_image" name="delete_image" value="1" {{old('delete_image') ? 'checked' : ''}}>
      削除する
    </label>
  </div>

  <div class="form-group mb-5" id="uploader">
    <label for="upfile">{{__('validation.attributes.image_path')}}:</label>
    <input type="file" id="upfile" class="form-control-file @error('upfile') is-invalid @enderror" name="upfile">
    @error('upfile')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-check border py-5">
    @foreach ($tags as $tag)
    <label class="mr-5 mb-3 form-check-label" for="tag_{{$tag->id}}">
      <input name="tags[]" class="form-check-input" type="checkbox" value="{{$tag->id}}" id="tag_{{$tag->id}}" {{in_array($tag->id, $tagIds) ? "checked" : null}}>
      {{$tag->tag}}
    </label>
    @endforeach
  </div>

  <button type="submit" class="btn btn-primary">更新</button>
</form>
@endsection

@section('script')
<script type="module">
function uploaderShowHide()
{
    if( $("#delete_image").prop('checked') ) {
        $("#uploader").fadeIn()
    } else {
        $("#uploader").fadeOut()
    }
}
$(function(){
    uploaderShowHide()
    $("#delete_image").click(function() {
      uploaderShowHide()
    });
});
</script>
@endsection