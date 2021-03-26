<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $comment_query = Comment::query();
        // AND検索で条件が追加されていく
        if ($req->title) {
            $comment_query->where('title', 'LIKE', '%'.$req->title.'%');
        }
        if ($req->body) {
            $comment_query->where('body', 'LIKE', '%'.$req->body.'%');
        }
        if ($req->item_name) {
            $comment_query->whereHas('item', function($q) use($req) {
                $q->where('item_name', 'LIKE', '%'.$req->item_name.'%');
            });
        }
        return view('comments.index', [
            'comments' => $comment_query->orderBy('id', 'DESC')->paginate(12),
            'title' => $req->title,
            'body' =>  $req->body,
            'item_name' => $req->item_name,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comments.create');
    }

    private function store_common(Request $req)
    {
        $file_name = null;
        $thumb_file_name = null;
        if ($req->upload_image) {
            $this->validate($req, array_merge(Comment::$rules, Comment::$rules_image));
            [$file_name, $thumb_file_name] = $this->saveProperlyImage($req->upfile);
        } else {
            $this->validate($req, Comment::$rules);
            [$file_name, $thumb_file_name] = $this->saveProperlyImage(Storage::disk('public')->get('comment.png'), 'png');
        }
        $comment = new Comment();
        $comment->fill(array_merge($req->all(), [
            'image_path' => $file_name,
            'thumbnail_path' => $thumb_file_name,
        ]));
        return $comment->save();
    }

    public function user_store(Request $req)
    {
        $this->store_common($req);
        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $this->store_common($req);
        return redirect(route('comments.index'));
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
    public function edit(Comment $comment)
    {
        return view('comments.edit',[
            'comment' => $comment
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Comment $comment)
    {
        if ($req->delete_image) {
            $this->validate($req, array_merge(Comment::$rules, Comment::$rules_image));
            Storage::disk('public')->delete($comment->image_path);
            Storage::disk('public')->delete($comment->thumbnail_path);
            [$file_name, $thumb_file_name] = $this->saveProperlyImage($req->upfile);
            $comment->image_path = $file_name;
            $comment->thumbnail_path = $thumb_file_name;
        } else {
            $this->validate($req, Comment::$rules);
        }
        $comment->fill($req->all());
        $comment->save();

        return redirect(route('comments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        Storage::disk('public')->delete($comment->image_path);
        Storage::disk('public')->delete($comment->thumbnail_path);
        $comment->delete();

        return redirect(route('comments.index'));
    }
}
