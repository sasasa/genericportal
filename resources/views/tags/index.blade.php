@extends('layouts.app')
@section('title', 'タグ管理')

@section('content')
@if (session('message'))
<div class="alert alert-danger mt-5">
  <h3>削除に失敗しました</h3>
  <ul>
    <li>{{ session('message') }}</li>
  </ul>
</div>
@endif

<form action="{{route('tags.index')}}" method="get" class="mb-5">
  <div class="form-group">
    <label for="tag">{{__('validation.attributes.tag')}}:</label>
    <input type="text" id="tag" name="tag" value="{{$tag}}" class="form-control">
  </div>

  <div class="form-group">
    <label for="item_name">{{__('validation.attributes.item_name')}}:</label>
    <input type="text" id="item_name" name="item_name" value="{{$item_name}}" class="form-control">
  </div>
  <input type="submit" value="検索" class="btn btn-primary">
</form>

<a href="{{ route('tags.create') }}" class="btn btn-primary mb-3">タグ新規作成</a>
<table class="table">
  @foreach ($tags as $tag)
  <tr>
    <th>{{__('validation.attributes.tag')}}</th>
    <td>{{$tag->tag}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.item_name')}}</th>
    <td>
      <ul>
        @foreach ($tag->items as $item)
          <li>{{$item->item_name}}</li>
        @endforeach
      </ul>
    </td>
  </tr>
  <tr>
    <th>記事の{{__('validation.attributes.title')}}</th>
    <td>
      <ul>
        @foreach ($tag->articles as $article)
          <li>{{$article->title}}</li>
        @endforeach
      </ul>
    </td>
  </tr>
  <tr>
    <th>操作</th>
    <td>
      <a class="btn btn-info" href="{{route('tags.edit', ['tag' => $tag])}}">更新</a>
      <form action="{{route('tags.destroy', ['tag' => $tag])}}" method="post">
        @csrf
        @method('delete')
        <input type="submit" value="削除する" class="btn btn-danger btn-del">
      </form>
    </td>
  </tr>
  @endforeach
</table>
{{ $tags->appends(request()->input())->links() }}
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