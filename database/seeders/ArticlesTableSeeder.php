<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( env('APP_ENV', 'production') === 'local' ) {
            for($i=1; $i<14; $i++) {
                if (!Storage::disk('public')->exists('article13.png')) {
                    if ( Storage::disk('public')->exists('article.png') ) {
                        Storage::disk('public')->delete('article'. $i. '.png');
                        Storage::disk('public')->copy('article.png', 'article'. $i. '.png');

                        InterventionImage::make(Storage::disk('public')->get('article.png'))->
                        resize(180, null, function($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->save(storage_path('app/public/'. 'article'. $i. '.thumb.png'));
                    } else {
                        throw new \Exception('storage/app/public 内に article.png が存在しないので Seeding を終了する');
                    }
                }
                Article::create([
                    'image_path' => 'article'. $i. '.png',
                    'thumbnail_path' => 'article'. $i. '.thumb.png',
                    'title' => '記事のタイトル？その'. $i,
                    'article_type' => Article::OPEN,
                    'description' => '記事の要約を入れます',
                    'body' => "<h4>小見出し</h4>
                    <p><a href='/i/{$i}' target='_blank'>記事の本文を入れます。</a>記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。</p>
                    <h4>小見出し</h4>
                    <p>記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。記事の本文を入れます。</p>",
                ]);
            }
        }
    }
}
