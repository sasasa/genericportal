@extends('layouts.app')
@section('title', '記事管理')

@section('content')
<h1>記事管理</h1>
<form action="{{route('articles.index')}}" method="get" class="mb-5">
  <div class="form-group">
    <label for="title">{{__('validation.attributes.title')}}:</label>
    <input type="text" id="title" name="title" value="{{$title}}" class="form-control">
  </div>

  <div class="form-group">
    <label for="article_type">{{__('validation.attributes.article_type')}}:</label>
    {{ Form::select('article_type', \App\Models\Article::$types, $article_type, ['class'=>"form-control", 'id'=>'article_type']) }}
  </div>

  <div class="form-group">
    <label for="description">{{__('validation.attributes.description')}}:</label>
    <input type="text" id="description" name="description" value="{{$description}}" class="form-control">
  </div>

  <div class="form-group">
    <label for="body">{{__('validation.attributes.body')}}:</label>
    <input type="text" id="body" name="body" value="{{$body}}" class="form-control">
  </div>

  <div class="form-group">
    <label for="tag">{{__('validation.attributes.tag')}}:</label>
    <input type="text" id="tag" name="tag" value="{{$tag}}" class="form-control">
  </div>
  <input type="submit" value="検索" class="btn btn-primary">
</form>


<a class="btn btn-primary mb-3" href="{{ route('articles.create') }}">記事新規作成</a>
<table class="table">
  @foreach ($articles as $article)
  <tr>
    <th>{{__('validation.attributes.title')}}</th>
    <td>{{$article->title}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.article_type')}}</th>
    <td>{{$article->type}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.description')}}</th>
    <td>{{$article->description}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.body')}}</th>
    <td>{!! strip_tags($article->body, $allowedTags) !!}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.image_path')}}</th>
    <td><img src="/storage/{{$article->image_path}}"></td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.thumbnail_path')}}</th>
    <td><img src="/storage/{{$article->thumbnail_path}}"></td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.tag')}}</th>
    <td>
      <ul>
        @foreach ($article->tags as $tag)
        <li>
          <a href="{{ route('tags.edit', ['tag' => $tag]) }}">{{$tag->tag}}</a>
        </li>
        @endforeach
      </ul>
    </td>
  </tr>
  <tr>
    <th>操作</th>
    <td>
      <a class="btn btn-info" href="{{route('articles.edit', ['article' => $article])}}">更新</a>
      <form action="{{route('articles.destroy', ['article' => $article])}}" method="post">
        @csrf
        @method('delete')
        <input type="submit" value="削除する" class="btn btn-danger btn-del">
      </form>
    </td>
  </tr>
  @endforeach
</table>
{{ $articles->appends(request()->input())->links() }}
@endsection

@section('script')
<script type="module">
$(function(){
    $(".btn-del").click(function() {
        if(confirm("本当に削除してもよろしいですか？")) {
        } else {
            //cancel
            event.preventDefault();
            return false;
        }
    });
});
</script>
@endsection