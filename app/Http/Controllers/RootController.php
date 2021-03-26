<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Item;
use App\Models\Tag;
use App\Models\Comment;

class RootController extends Controller
{
    /**
     * URLが
     * '/' または
     * '/?keyWord=hogehoge' の時
     */
    public function index(Request $req)
    {
        $item_query = Item::query();
        $article_query = Article::query();
        // 全文検索、自分のカラムと関連のカラム全てを検索する
        // OR検索で条件が追加されていく
        if ($req->keyWord) {
            $item_query->where('item_name', 'LIKE', '%'.$req->keyWord.'%');
            $item_query->orWhere('description', 'LIKE', '%'.$req->keyWord.'%');
            $item_query->orWhere('body', 'LIKE', '%'.$req->keyWord.'%');
            $item_query->orWhereHas('tags', function($q) use($req) {
                $q->where('tag', 'LIKE', '%'.$req->keyWord.'%');
            });
            $item_query->orWhereHas('comments', function($q) use($req) {
                $q->where('title', 'LIKE', '%'.$req->keyWord.'%');
                $q->orWhere('body', 'LIKE', '%'.$req->keyWord.'%');
            });

            $article_query->where('title', 'LIKE', '%'.$req->keyWord.'%');
            $article_query->orWhere('description', 'LIKE', '%'.$req->keyWord.'%');
            $article_query->orWhere('body', 'LIKE', '%'.$req->keyWord.'%');
            $article_query->orWhereHas('tags', function($q) use($req) {
                $q->where('tag', 'LIKE', '%'.$req->keyWord.'%');
            });
        }
        return view('root.index', [
            'keyWord' => $req->keyWord,
            'tags' => Tag::orderBy('id', 'DESC')->get(),
            'items' => $item_query->orderBy('id', 'DESC')->paginate(12),
            'articles' => $article_query->where('article_type', Article::OPEN)->orderBy('id', 'DESC')->paginate(12, ['*'], 'a_page', $req->a_page),
        ]);
    }

    /**
     * URLが
     * '/t/{tag}' または
     * '/t/{tag}?tags%5B%5D=12&tags%5B%5D=13' の時
     */
    public function tag(Request $req, Tag $tag)
    {
        $item_query = Item::query();
        $article_query = Article::query();
        if ($req->tags) {
            // パンくずリストを作るために覚えさせておく
            $req->session()->flash('tags', $req->tags);
            $req->session()->flash('fullUrl', url()->full());

            // 絞り込み時
            foreach($req->tags as $tagId) {
                // 検索したいタグのIDをtags[]というパラメーターで複数個を送ることによって
                // コントローラー側では配列として受け取れる
                // 受け取った条件をAND検索でつなげていけばタグを絞り込める
                $item_query->whereHas('tags', function($q) use($tagId) {
                    $q->where('id', $tagId);
                });
            }
            $items = $item_query->orderBy('id', 'DESC')->paginate(3);

            foreach($req->tags as $tagId) {
                $article_query->whereHas('tags', function($q) use($tagId) {
                    $q->where('id', $tagId);
                });
            }
            $articles = $article_query->where('article_type', Article::OPEN)->orderBy('id', 'DESC')->paginate(3, ['*'], 'a_page', $req->a_page);
        } else {
            // ページ初期遷移時
            $items = $tag->items()->orderBy('id', 'DESC')->paginate(3);
            $articles = $tag->articles()->where('article_type', Article::OPEN)->orderBy('id', 'DESC')->paginate(3, ['*'], 'a_page', $req->a_page);
        }

        return view('root.tag', [
            'tag' => $tag,
            'tags' => Tag::orderBy('id', 'ASC')->get(),
            'tagIds' => $req->tags ? $req->tags : [$tag->id],

            'items' => $items,
            'articles' => $articles,
        ]);
    }

    /**
     * URLが
     * '/a/{article}'または
     * '/t/{tag}/a/{article}'の時
     */
    public function article(Tag $tag, Article $article)
    {
        return view('root.article', [
            'article' => $article,
            'tag' => $tag,
            'allowedTags' => Article::$allowedTags,
        ]);
    }

    /**
     * URLが
     * '/i/{item}'または
     * '/t/{tag}/i/{item}'の時
     */
    public function item(Tag $tag, Item $item)
    {
        return view('root.item', [
            'item' => $item,
            'tag' => $tag,
            'comments' => $item->comments()->orderBy('id', 'desc')->paginate(12),
            'allowedTags' => Item::$allowedTags,
        ]);
    }
}
