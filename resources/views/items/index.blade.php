@extends('layouts.app')
@section('title', 'アイテム管理')

@section('content')
@if (session('message'))
<div class="alert alert-danger mt-5">
  <h3>削除に失敗しました</h3>
  <ul>
    <li>{{ session('message') }}</li>
  </ul>
</div>
@endif

<form action="{{route('items.index')}}" method="get" class="mb-5">
  <div class="form-group">
    <label for="item_name">{{__('validation.attributes.item_name')}}:</label>
    <input type="text" id="item_name" name="item_name" value="{{$item_name}}" class="form-control">
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


<a href="{{route('items.create')}}" class="btn btn-primary mb-3">アイテム新規作成</a>
<table class="table">
  @foreach ($items as $item)
  <tr>
    <th>{{__('validation.attributes.item_name')}}</th>
    <td>{{$item->item_name}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.image_path')}}</th>
    <td><img src="/storage/{{$item->image_path}}"></td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.thumbnail_path')}}</th>
    <td><img src="/storage/{{$item->thumbnail_path}}"></td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.description')}}</th>
    <td>{{$item->description}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.body')}}</th>
    <td>{!! strip_tags($item->body, $allowedTags) !!}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.tag')}}</th>
    <td>
      <ul>
        @foreach ($item->tags as $tag)
          <li>
            <a href="{{ route('tags.edit', ['tag' => $tag]) }}">{{$tag->tag}}</a>
          </li>
        @endforeach
      </ul>
    </td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.comment')}}</th>
    <td>
      <ul>
        @foreach ($item->comments as $comment)
          <li>
            <a href="{{ route('comments.edit', ['comment' => $comment]) }}">
              {{$comment->title}}
            </a>
          </li>
        @endforeach
      </ul>
    </td>
  </tr>
  <tr>
    <th>操作</th>
    <td>
      <a class="btn btn-info" href="{{route('items.edit', ['item' => $item])}}">更新</a>
      <form action="{{route('items.destroy', ['item' => $item])}}" method="post">
        @csrf
        @method('delete')
        <input type="submit" value="削除する" class="btn btn-danger btn-del">
      </form>
    </td>
  </tr>
  @endforeach
</table>
{{ $items->appends(request()->input())->links() }}
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