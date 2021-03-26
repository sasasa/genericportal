<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
// use App\Models\Park;
// use App\Models\Photo;

class TagsController extends Controller
{
    public function index(Request $req)
    {
        $tag_query = Tag::query();
        if ($req->tag) {
            $tag_query->where('tag', 'LIKE', '%'.$req->tag.'%');
        }
        if ($req->item_name) {
            $tag_query->whereHas('items', function($q) use($req) {
                $q->where('item_name', 'LIKE', '%'.$req->item_name.'%');
            });
        }
        
        return view('tags.index', [
            'tags' => $tag_query->orderBy('id', 'DESC')->paginate(12),
            'tag' => $req->tag,
            'item_name' => $req->item_name,
        ]);
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $req)
    {
        $this->validate($req, Tag::$rules);
        
        $tag = new Tag();
        $tag->fill($req->all())->save();
        
        return redirect(route('tags.index'));
    }

    public function show($id)
    {
        //
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', [
            'tag' => $tag,
        ]);
    }

    public function update(Request $req, Tag $tag)
    {
        $this->validate($req, Tag::$rules);
        
        $tag->fill($req->all())->save();
        
        return redirect(route('tags.index'));
    }

    public function destroy(Tag $tag)
    {
        if($tag->items->isEmpty()) {
            $tag->delete();
        } else {
            session()->flash('message', $tag->tag. 'に紐づけてあるデータが存在するため削除できません。');
        }
        return redirect(route('tags.index'));
    }
}
