@extends('layouts.base')
@section('title', 'トップページ')

@section('description', '汎用CMSのトップページです。ここに要約を書きます')

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">Home</li>
  </ol>
</nav>
@endsection

@section('content')
<form action="{{route('root')}}" method="get" class="my-5">
  <div class="form-group">
    <label for="keyWord">検索:</label>
    <input type="search" id="keyWord" name="keyWord" value="{{$keyWord}}" class="form-control">
  </div>
  <input type="submit" value="検索" class="btn btn-primary">
</form>



@if ($tags->isNotEmpty())
  <h2>タグ一覧</h2>
  <ul>
  @foreach ($tags as $tag)
    <li>
    <a href="{{ route('root.tag', ['tag'=>$tag]) }}">
      {{ $tag->tag }}
    </a>
    </li>
  @endforeach
  </ul>
@endif

@if ($items->isNotEmpty())
  <h2>アイテム一覧</h2>
  <ul>
  @foreach ($items as $item)
    <li>
    <a href="{{ route('root.item', ['item'=>$item]) }}">
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
    <a href="{{ route('root.article', ['article'=>$article]) }}">
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