@extends('layouts.app')
@section('title', 'コメント管理')

@section('content')

<form action="{{route('comments.index')}}" method="get" class="mb-5">
  <div class="form-group">
    <label for="title">{{__('validation.attributes.title')}}:</label>
    <input type="text" id="title" name="title" value="{{$title}}" class="form-control">
  </div>

  <div class="form-group">
    <label for="body">{{__('validation.attributes.body')}}:</label>
    <input type="text" id="body" name="body" value="{{$body}}" class="form-control">
  </div>

  <div class="form-group">
    <label for="item_name">{{__('validation.attributes.item_name')}}:</label>
    <input type="text" id="item_name" name="item_name" value="{{$item_name}}" class="form-control">
  </div>
  <input type="submit" value="検索" class="btn btn-primary">
</form>


<a href="{{route('comments.create')}}" class="btn btn-primary mb-3">コメント新規作成</a>
<table class="table">
  @foreach ($comments as $comment)
  <tr>
    <th>{{__('validation.attributes.title')}}</th>
    <td>{{$comment->title}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.image_path')}}</th>
    <td><img src="/storage/{{$comment->image_path}}"></td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.thumbnail_path')}}</th>
    <td><img src="/storage/{{$comment->thumbnail_path}}"></td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.body')}}</th>
    <td>{!! nl2br(e($comment->body)) !!}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.item_name')}}</th>
    <td>
      <ul>
        <li>{{$comment->item->item_name}}</li>
      </ul>
    </td>
  </tr>
  <tr>
    <th>操作</th>
    <td>
      <a class="btn btn-info" href="{{route('comments.edit', ['comment' => $comment])}}">更新</a>
      <form action="{{route('comments.destroy', ['comment' => $comment])}}" method="post">
        @csrf
        @method('delete')
        <input type="submit" value="削除する" class="btn btn-danger btn-del">
      </form>
    </td>
  </tr>
  @endforeach
</table>
{{ $comments->appends(request()->input())->links() }}
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