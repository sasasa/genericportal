<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Taggable;
class Article extends Model
{
    use HasFactory, Taggable;

    const OPEN = 1;
    const DRAFT = 2;

    public static $allowedTags = '<h1><h2><h3><h4><h5><h6><img><br><p><a>';

    public static $rules = [
        'title' => 'required|min:6|max:60',
        'body' => 'required|min:10|max:3000',
        'description' => 'required|min:10|max:120',
        'article_type' => "required|in:".self::OPEN.','.self::DRAFT,
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

    public static $types = [
        '' => '選択してください',
        self::OPEN => '公開',
        self::DRAFT => '下書き',
    ];

    protected $fillable = [
        'title',
        'body',
        'image_path',
        'thumbnail_path',
        'article_type',
        'description',
    ];

    /**
     * @return string
     * $article->type でアクセスすると'公開'や'下書き'の文字列を取得できる
     */
    public function getTypeAttribute(): string
    {
        return self::$types[$this->article_type];
    }
}
