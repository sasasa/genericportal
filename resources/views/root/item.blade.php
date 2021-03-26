@extends('layouts.base')
@section('title', 'アイテムページ')

@section('description', $item->description)


@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Home</a>
      </li>
      @if (session('tags') && session('fullUrl'))
        <li class="breadcrumb-item">
          <a href="{{ session('fullUrl') }}">
            @foreach (session('tags') as $tagItemId)
              {{ \App\Models\Tag::find($tagItemId)->tag }}
              @if (!$loop->last)
                +
              @endif
            @endforeach
          </a>
        </li>
      @elseif ($tag && $tag->id)
        <li class="breadcrumb-item">
          <a href="{{ route('root.tag', ['tag' => $tag]) }}">
            {{ $tag->tag }}
          </a>
        </li>
      @endif
      <li class="breadcrumb-item active" aria-current="page">{{ $item->item_name }}</li>
  </ol>
</nav>
@endsection


@section('content')

<h2>アイテム</h2>
<ul>
  <li>
    アイテム名：{{ $item->item_name }}
  </li>
  <li>
    本文：{!! strip_tags($item->body, $allowedTags) !!}
  </li>
  <li>
    画像：<img src="/storage/{{$item->image_path}}" alt="{{ $item->item_name }}">
  </li>
  <li>
    サムネイル：
    <a href="/storage/{{$item->image_path}}">
      <img src="/storage/{{$item->thumbnail_path}}" alt="{{ $item->item_name }}">
    </a>
  </li>

  <li>
    タグ一覧：
    @if ($item->tags->isNotEmpty())
      <ul>
        @foreach ($item->tags as $tag_item)
          <li>
            <a href="{{ route('root.tag', ['tag' => $tag_item]) }}">
              {{ $tag_item->tag }}
            </a>
          </li>
        @endforeach
      </ul>
    @endif
  </li>
</ul>


@if ($comments->isNotEmpty())
  <h2>コメント一覧</h2>
  <ul>
  @foreach ($comments as $comment)
    <li>
      タイトル：{{ $comment->title }}
    </li>
    <li>
      本文：{!! nl2br(e($comment->body)) !!}
    </li>
    <li>
      サムネイル：
      <a href="/storage/{{$comment->image_path}}">
        <img src="/storage/{{$comment->thumbnail_path}}" alt="{{ $comment->title }}">
      </a>
    </li>
    {{-- <li>
      画像：<img src="/storage/{{$comment->image_path}}" alt="{{ $comment->title }}">
    </li> --}}
  @endforeach
  </ul>
  {{ $comments->appends(request()->input())->links() }}
@endif
<div class="my-5">
  <h2>コメント入力</h2>
  @include('components.errorAll')
  <form action="{{ route('comments.user_store', ['item' => $item]) }}" method="POST" enctype='multipart/form-data'>
    @csrf

    <input value="{{ $item->id }}" type="hidden" name="item_id">

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
</div>


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
if ($('a[name="error"]').length) {
  location.hash = "error"
}
$(function(){
    uploaderShowHide()
    $("#upload_image").click(function() {
      uploaderShowHide()
    });
});
</script>
@endsection