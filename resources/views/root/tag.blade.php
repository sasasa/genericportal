@extends('layouts.base')
@section('title', 'トップページ')

@section('description')
@if(session('tags'))
@foreach(session('tags') as $tagItemId)
{{App\Models\Tag::find($tagItemId)->tag}}@if(!$loop->last)+@endif @endforeach の一覧@else{{$tag->tag}}の一覧@endif @endsection
{{--
  meta description を一行に収めるためにはインデントも改行も無くさないといけない
  書き換えた後で一行にするとよい
  --}}


@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Home</a>
      </li>
      @if (session('tags'))
        <li class="breadcrumb-item active" aria-current="page">
        @foreach (session('tags') as $tagItemId)
          {{ App\Models\Tag::find($tagItemId)->tag }}
          @if (!$loop->last)
            +
          @endif
        @endforeach
        </li>
      @else
        <li class="breadcrumb-item active" aria-current="page">{{ $tag->tag }}</li>
      @endif
  </ol>
</nav>
@endsection

@section('content')
<h1>{{ $tag->tag }}</h1>
<form action="{{route('root.tag', ['tag'=>$tag])}}" method="get" class="my-5">
  <div class="form-check border py-5">
    @foreach ($tags as $tagItem)
    <label class="mr-5 mb-3 form-check-label" for="tag_{{$tagItem->id}}">
      <input
        name="tags[]"
        class="form-check-input"
        type="checkbox"
        value="{{$tagItem->id}}"
        id="tag_{{$tagItem->id}}"
        {{in_array($tagItem->id, $tagIds) ? "checked" : null}}
        {{-- {!! $tag->id == $tagItem->id ? "onclick='return false'" : null !!} --}}
      >
      {{$tagItem->tag}}
    </label>
    @endforeach
  </div>
  <button type="submit" class="btn btn-primary">絞り込む</button>
</form>
{{--
  検索したいタグのIDをtags[]というパラメーターで複数個を送ることによって
  コントローラー側では配列として受け取れる
--}}

@if ($items->isNotEmpty())
  <h2>アイテム一覧</h2>
  <ul>
  @foreach ($items as $item)
    <li>
    <a href="{{ route('root.tag.item', ['tag'=>$tag, 'item'=>$item]) }}">
      <img src="/storage/{{$item->thumbnail_path}}" alt="{{ $item->item_name }}">
      {{ $item->item_name }}
    </a>
    </li>
  @endforeach
  </ul>
  {{ $items->appends(request()->input())->links() }}
@else
  <h2>アイテムがありません。</h2>
@endif

@if ($articles->isNotEmpty())
  <h2>記事一覧</h2>
  <ul>
  @foreach ($articles as $article)
    <li>
    <a href="{{ route('root.tag.article', ['tag'=>$tag, 'article'=>$article]) }}">
      <img src="/storage/{{$article->thumbnail_path}}" alt="{{ $article->title }}">
      {{ $article->title }}
    </a>
    </li>
  @endforeach
  </ul>
  {{ $articles->appends(request()->input())->links() }}
@else
  <h2>記事がありません。</h2>
@endif





@endsection