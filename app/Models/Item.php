<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Taggable;

class Item extends Model
{
    use HasFactory, Taggable;

    public static $allowedTags = '<h1><h2><h3><h4><h5><h6><img><br><p><a>';

    public static $rules = [
        'item_name' => 'required|max:60',
        'body' => 'required|min:10|max:3000',
        'description' => 'required|min:10|max:120',
    ];

    public static $rules_image = [
        'upfile' => [
            'required',
            'file',
            'image',
            'mimes:webp,jpg,jpeg,png',
            'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
        ],
    ];

    protected $fillable = [
        'item_name',
        'image_path',
        'thumbnail_path',
        'body',
        'description',
    ];

    public function comments(): object
    {
        return $this->hasMany('App\Models\Comment');
    }

    public static function optionsForSelect()
    {
        $options = ['' => '選択してください'];
        self::orderBy('id', 'desc')->get()->each(function($item) use(&$options) {
            $options[$item->id] = $item->item_name;
        });
        return $options;
    }
}
