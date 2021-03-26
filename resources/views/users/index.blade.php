@extends('layouts.app')
@section('title', 'ユーザー管理')

@section('content')
<h1>ユーザー管理</h1>
<a href="{{ route('users.create') }}" class="btn btn-primary mb-3">ユーザー新規作成</a>
<table class="table">
  @foreach ($users as $user)
  <tr>
    <th>{{__('validation.attributes.name')}}</th>
    <td>{{$user->name}}</td>
  </tr>
  <tr>
    <th>{{__('validation.attributes.email')}}</th>
    <td>{{$user->email}}</td>
  </tr>
  <tr>
    <th></th>
    <td>
      <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-sm btn-primary">更新</a>
      <a href="{{ route('users.edit_pass', ['user' => $user]) }}" class="btn btn-sm btn-primary">パスワード変更</a>

      <form action="{{ route('users.destroy', ['user' => $user]) }}" method="post">
        @csrf
        @method('delete')
        <input type="submit" value="削除する" class="btn btn-sm btn-danger btn-del">
      </form>
    </td>
  </tr>
  <tr>
    <th></th>
    <td></td>
  </tr>
  @endforeach
</table>
{{ $users->appends(request()->input())->links() }}
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