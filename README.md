
<!--
```
mysql -u root -p
CREATE DATABASE genericportal CHARACTER SET utf8;
GRANT ALL PRIVILEGES ON genericportal.* TO root@localhost IDENTIFIED BY '';


php artisan make:seeder UsersTableSeeder
php artisan make:seeder ArticlesTableSeeder


php artisan make:controller RootController
php artisan make:controller ArticlesController --resource
php artisan make:controller CommentsController --resource

php artisan make:migration article_tag_table

php artisan migrate:refresh
php artisan db:seed
php artisan storage:link
php artisan migrate:refresh --seed
```
-->
# genericportal
ポータルサイト量産のベースとなる汎用CMS。基本機能のみ備える。

[フロント画面](http://genericportal.grow-up-webmarketing.co.jp/)
[管理画面](http://genericportal.grow-up-webmarketing.co.jp/login)

| email | pass |
| --- | --- |
| grow-up123@gmail.com | grow-up123 |
| masaakisaeki@gmail.com | hogemoge |


## ローカル環境インストール
サーバーでOneThird-CMSと同居させるために
環境構築はPHP 7.3.xxで行ってください。


[XAMP](https://www.apachefriends.org/jp/download.html)や[MAMP](https://www.mamp.info/en/downloads/)を入れてPHP、Apache、MYSQL、[Node.js](https://nodejs.org/ja/)などを準備する。

[この記事](http://vdeep.net/laravel-git-clone)を参考に`php artisan migrate`まで行う


以下のコマンドを実行したのち画像を用意する
```
php artisan storage:link
```

用意する画像
* storage/app/public/article.png
* storage/app/public/comment.png
* storage/app/public/item.png

画像を用意したら以下のコマンドを実行する
これでダミーデータが用意される。
```
php artisan db:seed
```

上記にプラスして以下のコマンドも実施する
必要なJSライブラリをインストールする
```
npm install
```

SCSSやJSの開発のために必要なビルドコマンド
```
npm run watch　※終了させるまで変更を待ち受ける、変更があればその都度ビルドする
or
npm run dev　※開発用のビルド
or
npm run prod　※本番用のビルド、ファイルを圧縮する
```
以下のファイルを変換してくれる
* resources/sass/*
* resources/js/*
↓変換後に保存される
* public/css/app.css
* public/js/app.js

ビルド情報は以下のファイルで設定する
webpack.mix.js
```
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');
```


# ソースコードの確認の仕方
[vscode](https://code.visualstudio.com/download) を使用してください。

## vscodeにプラグインを入れる
PHP(Laravel)で書かれたソースコードを確認するにはvscodeに 以下の拡張機能をインストールしてください。

* Japanese Language Pack for Visual Studio Code
* PHP Intelephense
* Laravel Snippets
* Laravel Blade Snippets
* Laravel Artisan
* Laravel Extra Intellisense
* Laravel goto view
* laravel-goto-controller

Laravel goto viewは便利なので必ず入れてください。Ctrl+クリックでviewにジャンプできます。

## vscode組み込みの機能を無効にする
PHP Intelephenseとかぶるのでvscode組み込みのPHPの機能を外す。
ファイル→ユーザー設定→設定を選んで検索欄に以下の設定を入力してチェックを外す

* php.suggest.basicのチェックを外す
* php.validate.enableのチェックを外す


# 各機能の概要説明

## ユーザー管理
ユーザーを追加・編集・削除可能、パスワード変更機能がある。

カラムの項目は　`database/migrations/2014_10_12_000000_create_users_table.php`　で定義されている。

各ビューは `resources/view/users` に存在している

モデルは　`app/Models/User.php`

コントローラーは　`app/Http/Controllers/UsersController.php`

シーダーは `database/seeders/UsersTableSeeder.php`

ルーティングは
```
Route::resource('/users', UsersController::class);
Route::get('/users/{user}/edit_pass', [UsersController::class, 'edit_pass'])->name('users.edit_pass');
Route::patch('/users/{user}/update_pass', [UsersController::class, 'update_pass'])->name('users.update_pass');
```


## アイテム管理
画像と本文を投稿・編集・削除可能、本文はタグを許可している。許可するタグは以下の設定

Item.php
```
public static $allowedTags = '<h1><h2><h3><h4><h5><h6><img><br><p><a>';
```

カラムの項目は　`database/migrations/2020_12_11_053121_create_items_table.php`　で定義されている。

各ビューは

* `resources/view/items`
* `resources/view/root/item.blade.php`

モデルは　`app/Models/Item.php`

コントローラーは　`app/Http/Controllers/ItemsController.php`

シーダーは `database/seeders/ItemsTableSeeder.php`

ルーティングは
```
Route::get('/i/{item}', [RootController::class, 'item'])->name('root.item');
Route::get('/t/{tag}/i/{item}', [RootController::class, 'item'])->name('root.tag.item');
Route::resource('/items', ItemsController::class);
```


## 画像付きコメント（アイテムにコメント可能）
アイテムの欄でコメントが可能となる

カラムの項目は　`database/migrations/2020_12_17_113422_create_comments_table.php`　で定義されている。

各ビューは

* `resources/view/comments`
* `resources/view/root/item.blade.php`

モデルは　`app/Models/Comment.php`

コントローラーは　`app/Http/Controllers/CommentsController.php`

シーダーは `database/seeders/CommentsTableSeeder.php`

ルーティングは
```
Route::post('/i/{item}/comments', [CommentsController::class, 'user_store'])->name('comments.user_store');

Route::resource('/comments', CommentsController::class);
```



## タグ管理
タグ付けのためのタグを作成・編集・削除可能

カラムの項目は　`database/migrations/2020_12_23_125814_create_tags_table.php`　で定義されている。

各ビューは

* `resources/view/tags`
* `resources/view/root/tag.blade.php`

モデルは　`app/Models/Tag.php`

コントローラーは　`app/Http/Controllers/TagsController.php`

シーダーは `database/seeders/TagsTableSeeder.php`

ルーティングは
```
Route::get('/t/{tag}', [RootController::class, 'tag'])->name('root.tag');
Route::resource('/tags', TagsController::class);
```



## 記事管理
画像と本文を投稿・編集・削除可能、本文はタグを許可している。許可するタグは以下の設定

Article.php
```
public static $allowedTags = '<h1><h2><h3><h4><h5><h6><img><br><p><a>';
```

カラムの項目は　`database/migrations/2021_01_05_132634_create_articles_table.php`　で定義されている。

各ビューは

* resources/view/articles
* resources/view/root/article.blade.php　に存在している

モデルは　`app/Models/Article.php`

コントローラーは　`app/Http/Controllers/ArticlesController.php`

シーダーは `database/seeders/ArticlesTableSeeder.php`

ルーティングは
```
Route::get('/a/{article}', [RootController::class, 'article'])->name('root.article');
Route::get('/t/{tag}/a/{article}', [RootController::class, 'article'])->name('root.tag.article');
Route::resource('/articles', ArticlesController::class);
```

## XML Sitemap
コンテンツのデータから動的にsitemap.xmlを作成している
`SiteMapController` コントローラー内でパス情報や重要度を設定している。
ルーティング（URL）の情報を変えたりしたら必ずサイトマップのソースコードも
変える必要があるかどうか確認してください。

詳しくは以下を参照しください

* https://github.com/Laravelium/laravel-sitemap
* https://qiita.com/bossunn24/items/d2a221fc48c2c2a5287d

ルーティングは
```
Route::get('/sitemap.xml', [SiteMapController::class, 'sitemap'])->name('sitemap');
```


# 変更箇所特定の仕方

## ルーティングについて
`php artisan route:list`でルーティングテーブルを見る癖を付ける
URLとNameとコントローラーのアクションの対応表が見れる

特に理解する必要があるのはHTTPメソッド名
```
Route::get
Route::post
Route::patch
Route::delete
```

patchメソッドで送りたいとき sample1.blade.php
```
<form action="{{ route('articles.update', ['article'=>$article]) }}" method="post">
  @csrf
  @method('PATCH')
</form>
```
deleteメソッドで送りたいとき sample2.blade.php
```
<form action="{{route('articles.destroy', ['article' => $article])}}" method="post">
  @csrf
  @method('delete')
</form>
```


resourceは一つ書いただけで複数のルートが定義される。
```
Route::resource
```
[この記事](https://qiita.com/sympe/items/9297f41d5f7a9d91aa11)を参考にルーティングのresourceを理解してください。

### ルーティングの1例
routes/web.php
```
Route::get('/i/{item}', [RootController::class, 'item'])->name('root.item');
```
sample.blade.php
```
<a href="{{ route('root.item', ['item'=>$item]) }}">サンプル</a>
↓
<a href="/i/1111">サンプル</a> ※1111は$itemのid
```
RootController.php
```
  public function item(Tag $tag, Item $item)
  {
      $tag;  // $tagはルーティングに情報がないのでnullになる
      $item; // idが1111の$itemが自動で設定されている。
  }
```


## blade上のURLからアクションを特定
sample.blade.php
```
<a href="{{ route('login') }}">ログイン</a>
```
routeのNameで指定するとルーティングテーブルの対応表から

* `/login`というパスと
* `App\Http\Controllers\Auth\LoginController@showLoginForm`というコントローラーとアクションが特定される



## アクションから利用bladeを特定

アクション内のreturn文を見ると`ディレクトリ.ファイル名`となっている
この場合は `/resources/views/auth`ディレクトリの`login.blade.php`を編集すればいいと分かる
```
return view('auth.login');
```
※プラグインの機能でCtrl+クリックするとそのbladeファイルに飛ぶことができる


## 翻訳を変えたいとき

 以下を書き換える

* `resources/lang/ja/*.php`
* `resources/lang/ja.json` 

以下の翻訳ファイルは `resources/lang/ja/validation.php` の中の
`attributes` 内にある `upfile` を書き換える
```
{{__('validation.attributes.upfile')}}:
```


## 環境設定Xserver

### サブドメインを作成する
`genericportal.grow-up-webmarketing.co.jp`

URL
* http://genericportal.grow-up-webmarketing.co.jp/

### データベースを作成する
`growupweb_genericportal`

| DB名 | ユーザー | サーバーアドレス | パスワード |
| ------------- | ------------- | ------------- | ------------- |
| growupweb_genericportal | growupweb_generi | mysql8001.xserver.jp | **** |


### git cloneする
```
[growupweb@sv8003 ~]$ cd Laravel
[growupweb@sv8003 ~]$ git clone git@github.com:GrowUpFukuoka/genericportal.git
[growupweb@sv8003 ~]$ cd genericportal
```

### サブドメインのディレクトリにシンボリックリンクを張る
```
[growupweb@sv8003 ~]$ cd
[growupweb@sv8003 ~]$ cd grow-up-webmarketing.co.jp/public_html/
[growupweb@sv8003 ~/grow-up-webmarketing.co.jp/public_html]$ rm -r genericportal
[growupweb@sv8003 ~/grow-up-webmarketing.co.jp/public_html]$ ln -s ~/Laravel/genericportal/public/ genericportal
```


### Laravelの設定
```
[growupweb@sv8003 ~/Laravel/genericportal (main=)]$ composer install
[growupweb@sv8003 ~/Laravel/genericportal (main=)]$ cp .env.exsample .env
[growupweb@sv8003 ~/Laravel/genericportal (main=)]$ vi .env ※DB情報を設定する
[growupweb@sv8003 ~/Laravel/genericportal (main=)]$ php artisan key:generate
[growupweb@sv8003 ~/Laravel/genericportal (main=)]$ php artisan migrate
[growupweb@sv8003 ~/Laravel/genericportal (main=)]$ php artisan storage:link
[growupweb@sv8003 ~/Laravel/genericportal (main=)]$ chmod 777 -R storage/
```

### データファイルの準備

ファイルアップロードの為にFTPの準備

* FTPユーザーID：　genericportal@grow-up-webmarketing.co.jp
* パス：　genericportal123
* 接続先ディレクトリ：　/home/growupweb/Laravel/genericportal

用意する画像
* storage/app/public/article.png
* storage/app/public/comment.png
* storage/app/public/item.png

### サンプルデータの生成
```
php artisan db:seed
```






