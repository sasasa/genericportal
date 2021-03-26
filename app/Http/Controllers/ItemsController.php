<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class ItemsController extends Controller
{
    public function index(Request $req)
    {
        $item_query = Item::query();
        // AND検索で条件が追加されていく
        if ($req->item_name) {
            $item_query->where('item_name', 'LIKE', '%'.$req->item_name.'%');
        }
        if ($req->description) {
            $item_query->where('description', 'LIKE', '%'.$req->description.'%');
        }
        if ($req->body) {
            $item_query->where('body', 'LIKE', '%'.$req->body.'%');
        }
        if ($req->tag) {
            $item_query->whereHas('tags', function($q) use($req) {
                $q->where('tag', 'LIKE', '%'.$req->tag.'%');
            });
        }
        return view('items.index', [
            'items' => $item_query->orderBy('id', 'DESC')->paginate(12),
            'item_name' => $req->item_name,
            'description' => $req->description,
            'body' => $req->body,
            'tag' => $req->tag,
            'allowedTags' => Item::$allowedTags
        ]);
    }

    public function create()
    {
        return view('items.create', [
            'allowedTags' => Item::$allowedTags,
            'tags' => Tag::all(),
            'tagIds' => old('tags') ? old('tags') : [],
        ]);
    }

    public function store(Request $req)
    {
        $file_name = null;
        $thumb_file_name = null;
        if ($req->upload_image) {
            $this->validate($req, array_merge(Item::$rules, Item::$rules_image));
            [$file_name, $thumb_file_name] = $this->saveProperlyImage($req->upfile);
        } else {
            $this->validate($req, Item::$rules);
            [$file_name, $thumb_file_name] = $this->saveProperlyImage(Storage::disk('public')->get('item.png'), 'png');
        }
        $item = new Item();
        $item->fill(array_merge($req->all(), [
            'image_path' => $file_name,
            'thumbnail_path' => $thumb_file_name,
        ]));
        $item->save();

        $item->tags()->sync($req->tags);

        return redirect(route('items.index'));
    }

    public function edit(Item $item)
    {
        if (\Session::has('tags')) {
            // バリデーションで戻ってきたとき
            $ids = session('tags');
        } else {
            // 初回の表示
            $ids = $item->tagIds();
        }

        return view('items.edit', [
            'item' => $item,
            'allowedTags' => Item::$allowedTags,
            'tags' => Tag::all(),
            'tagIds' => $ids,
        ]);
    }

    public function update(Request $req, Item $item)
    {
        // バリデーションで戻されたときに利用する
        $req->session()->flash('tags', $req->tags ? $req->tags : []);

        if ($req->delete_image) {
            $this->validate($req, array_merge(Item::$rules, Item::$rules_image));
            Storage::disk('public')->delete($item->image_path);
            Storage::disk('public')->delete($item->thumbnail_path);
            [$file_name, $thumb_file_name] = $this->saveProperlyImage($req->upfile);
            $item->image_path = $file_name;
            $item->thumbnail_path = $thumb_file_name;
        } else {
            $this->validate($req, Item::$rules);
        }
        $item->fill($req->all());
        $item->save();
        $item->tags()->sync($req->tags);

        return redirect(route('items.index'));
    }

    public function destroy(Item $item)
    {
        Storage::disk('public')->delete($item->image_path);
        Storage::disk('public')->delete($item->thumbnail_path);
        $item->delete();

        return redirect(route('items.index'));
    }
}
