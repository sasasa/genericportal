@extends('layouts.app')
@section('title', 'ホーム')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a class="btn btn-info mb-2" href="{{route('articles.index')}}">記事管理</a>
                    <a class="btn btn-info mb-2" href="{{route('users.index')}}">ユーザー管理</a>
                    <a class="btn btn-info mb-2" href="{{route('tags.index')}}">タグ管理</a>
                    <a class="btn btn-info mb-2" href="{{route('items.index')}}">アイテム管理</a>
                    <a class="btn btn-info mb-2" href="{{route('comments.index')}}">コメント管理</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
