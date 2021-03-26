@extends('layouts.app')
@section('title', 'コメント編集')

@section('content')
<h1>コメント編集</h1>

@include('components.errorAll')
<form action="{{ route('comments.update', ['comment' => $comment]) }}" method="POST" enctype='multipart/form-data'>
  @csrf
  @method('PATCH')

  <div class="form-group">
    <label for="item_id">{{__('validation.attributes.item_id')}}:</label>
    {{ Form::select('item_id', \App\Models\Item::optionsForSelect(), old('item_id', $comment->item_id), empty($errors->first('item_id')) ? ['class'=>"form-control", 'id'=>'item_id'] : ['class'=>"form-control is-invalid", 'id'=>'item_id']) }}
    @error('item_id')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="">{{__('validation.attributes.title')}}:</label>
    <input value="{{old('title', $comment->title)}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title">
    @error('title')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="body">{{__('validation.attributes.body')}}:</label>
    <textarea rows="10" id="body" class="form-control @error('body') is-invalid @enderror" name="body">{{old('body', $comment->body)}}</textarea>
    @error('body')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group mt-5">
    <img src="/storage/{{$comment->image_path}}?{{ \Str::random(8) }}">
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