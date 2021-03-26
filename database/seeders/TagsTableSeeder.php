<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Item;
use App\Models\Article;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( env('APP_ENV', 'production') === 'local' ) {
            Tag::create([
                'tag' => '#タグその１',
            ]);
            Tag::create([
                'tag' => '#タグその２',
            ]);
            Tag::create([
                'tag' => '#タグその３',
            ]);
    
            Tag::create([
                'tag' => '#タグその４',
            ]);
            Tag::create([
                'tag' => '#タグその５',
            ]);
            Tag::create([
                'tag' => '#タグその６',
            ]);
            Tag::create([
                'tag' => '#タグその７',
            ]);
            Tag::create([
                'tag' => '#タグその８',
            ]);
            Tag::create([
                'tag' => '#タグその９',
            ]);
            Tag::create([
                'tag' => '#タグその１０',
            ]);
            Tag::create([
                'tag' => '#タグその１１',
            ]);
            Tag::create([
                'tag' => '#タグその１２',
            ]);
            Tag::create([
                'tag' => '#タグその１３',
            ]);

            $tags = Tag::get();

            Item::get()->each(function($item) use($tags) {
                $sync = [];
                for($i=0; $i<3; $i++) {
                    $sync[] = $tags->random()->id;
                }
                $item->tags()->sync($sync);
            });

            Article::get()->each(function($article) use($tags) {
                $sync = [];
                for($i=0; $i<3; $i++) {
                    $sync[] = $tags->random()->id;
                }
                $article->tags()->sync($sync);
            });
        }
    }
}
