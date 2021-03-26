@extends('layouts.app')
@section('title', 'アイテム新規作成')

@section('content')
<h1>アイテム新規作成</h1>

@include('components.errorAll')

<form action="{{route('items.store')}}" method="post" class="mt-5" enctype='multipart/form-data'>
  @csrf

  <div class="form-group">
    <label for="">{{__('validation.attributes.item_name')}}:</label>
    <input value="{{old('item_name')}}" type="text" id="item_name" class="form-control @error('item_name') is-invalid @enderror" name="item_name">
    @error('item_name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="description">{{__('validation.attributes.description')}}:</label>
    <input value="{{old('description')}}" type="text" id="description" class="form-control @error('description') is-invalid @enderror" name="description">
    @error('description')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    meta descriptionに表示されます
  </div>

  <div class="form-group">
    <label for="body">{{__('validation.attributes.body')}}:</label>
    <textarea rows="10" id="body" class="form-control @error('body') is-invalid @enderror" name="body">{{old('body')}}</textarea>
    @error('body')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    {{ $allowedTags }}タグを利用できます
  </div>

  <div class="form-group mt-5">
    <label>
      <input type="checkbox" id="upload_image" name="upload_image" value="1" {{old('upload_image') ? 'checked' : ''}}>
      画像をアップロードする
    </label>
    ※アップロードしない場合はデフォルト画像になります
  </div>

  <div class="form-group mb-5" id="uploader">
    <label for="upfile">{{__('validation.attributes.upfile')}}:</label>
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

  <button type="submit" class="btn btn-primary">登録</button>
</form>
@endsection



@section('script')
<script type="module">
function uploaderShowHide()
{
    if( $("#upload_image").prop('checked') ) {
        $("#uploader").fadeIn()
    } else {
        $("#uploader").fadeOut()
    }
}
$(function(){
    uploaderShowHide()
    $("#upload_image").click(function() {
      uploaderShowHide()
    });
});
</script>
@endsection