<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Tag;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Support\Facades\Storage;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $article_query = Article::query();
        // AND検索で条件が追加されていく
        if ($req->title) {
            $article_query->where('title', 'LIKE', '%'.$req->title.'%');
        }
        if ($req->article_type) {
            $article_query->where('article_type', $req->article_type);
        }
        if ($req->description) {
            $article_query->where('description', 'LIKE', '%'.$req->description.'%');
        }
        if ($req->body) {
            $article_query->where('body', 'LIKE', '%'.$req->body.'%');
        }
        if ($req->tag) {
            $article_query->whereHas('tags', function($q) use($req) {
                $q->where('tag', 'LIKE', '%'.$req->tag.'%');
            });
        }
        return view('articles.index', [
            'allowedTags' => Article::$allowedTags,
            'articles' => $article_query->orderBy('id', 'DESC')->paginate(12),
            'title' => $req->title,
            'article_type' => $req->article_type,
            'description' => $req->description,
            'body' => $req->body,
            'tag' => $req->tag,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create', [
            'allowedTags' => Article::$allowedTags,
            'tags' => Tag::all(),
            'tagIds' => old('tags') ? old('tags') : [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $file_name = null;
        $thumb_file_name = null;
        if ($req->upload_image) {
            $this->validate($req, array_merge(Article::$rules, Article::$rules_image));
            [$file_name, $thumb_file_name] = $this->saveProperlyImage($req->upfile);
        } else {
            $this->validate($req, Article::$rules);
            [$file_name, $thumb_file_name] = $this->saveProperlyImage(Storage::disk('public')->get('article.png'), 'png');
        }
        $article = new Article();
        $article->fill(array_merge($req->all(), [
            'image_path' => $file_name,
            'thumbnail_path' => $thumb_file_name,
        ]))->save();
        $article->tags()->sync($req->tags);

        return redirect()->route('articles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        if (\Session::has('tags')) {
            // バリデーションで戻ってきたとき
            $ids = session('tags');
        } else {
            // 初回の表示
            $ids = $article->tagIds();
        }
        return view('articles.edit', [
            'article' => $article,
            'allowedTags' => Article::$allowedTags,
            'tags' => Tag::all(),
            'tagIds' => $ids,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Article $article)
    {
        // バリデーションで戻されたときに利用する
        $req->session()->flash('tags', $req->tags ? $req->tags : []);

        if ($req->delete_image) {
            $this->validate($req, array_merge(Article::$rules, Article::$rules_image));
            Storage::disk('public')->delete($article->image_path);
            Storage::disk('public')->delete($article->thumbnail_path);
            [$file_name, $thumb_file_name] = $this->saveProperlyImage($req->upfile);
            $article->image_path = $file_name;
            $article->thumbnail_path = $thumb_file_name;
        } else {
            $this->validate($req, Article::$rules);
        }
        $article->fill($req->all());
        $article->save();
        $article->tags()->sync($req->tags);

        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        Storage::disk('public')->delete($article->image_path);
        Storage::disk('public')->delete($article->thumbnail_path);
        $article->delete();

        return redirect(route('articles.index'));
    }
}
