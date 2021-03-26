@extends('layouts.base')
@section('title', '記事ページ')

@section('description', $article->description)

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
      <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
  </ol>
</nav>
@endsection

@section('content')
<h2>記事</h2>
<ul>
  <li>
    タイトル：{{ $article->title }}
  </li>
  <li>
    記事タイプ：{{$article->type}}
  </li>
  <li>
    本文：{!! strip_tags($article->body, $allowedTags) !!}
  </li>
  <li>
    画像：<img src="/storage/{{$article->image_path}}" alt="{{ $article->title }}">
  </li>
  <li>
    サムネイル：
    <a href="/storage/{{$article->image_path}}">
      <img src="/storage/{{$article->thumbnail_path}}" alt="{{ $article->title }}">
    </a>
  </li>
  <li>
    タグ一覧：
    @if ($article->tags->isNotEmpty())
      <ul>
        @foreach ($article->tags as $tag_item)
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

@endsection