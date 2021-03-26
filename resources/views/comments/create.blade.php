@extends('layouts.app')
@section('title', 'コメント新規作成')

@section('content')
<h1>コメント新規作成</h1>

@include('components.errorAll')
<form action="{{ route('comments.store') }}" method="POST" enctype='multipart/form-data'>
  @csrf

  <div class="form-group">
    <label for="item_id">{{__('validation.attributes.item_id')}}:</label>
    {{ Form::select('item_id', \App\Models\Item::optionsForSelect(), old('item_id'), empty($errors->first('item_id')) ? ['class'=>"form-control", 'id'=>'item_id'] : ['class'=>"form-control is-invalid", 'id'=>'item_id']) }}
    @error('item_id')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="">{{__('validation.attributes.title')}}:</label>
    <input value="{{old('title')}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title">
    @error('title')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>

  <div class="form-group">
    <label for="body">{{__('validation.attributes.body')}}:</label>
    <textarea rows="10" id="body" class="form-control @error('body') is-invalid @enderror" name="body">{{old('body')}}</textarea>
    @error('body')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
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