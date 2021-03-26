<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Comment extends Model
{
    use HasFactory;

    public static $rules = [
        'item_id' => 'required',
        'title' => 'required|min:1|max:20',
        'body' => 'required|min:1|max:1000',
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
        'title',
        'body',
        'image_path',
        'thumbnail_path',
        'item_id',
    ];

    public function item(): object
    {
        return $this->belongsTo('App\Models\Item');
    }

}
