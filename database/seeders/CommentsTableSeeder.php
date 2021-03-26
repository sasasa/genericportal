<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( env('APP_ENV', 'production') === 'local' ) {
            Item::orderBy('id', 'desc')->get()->each(function($item){
                for($i=1; $i<14; $i++) {
                    if (!Storage::disk('public')->exists('comment13.png')) {
                        if ( Storage::disk('public')->exists('comment.png') ) {
                            Storage::disk('public')->delete('comment'. $i. '.png');
                            Storage::disk('public')->copy('comment.png', 'comment'. $i. '.png');

                            InterventionImage::make(Storage::disk('public')->get('comment.png'))->
                            resize(180, null, function($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->save(storage_path('app/public/'. 'comment'. $i. '.thumb.png'));
                        } else {
                            throw new \Exception('storage/app/public 内に comment.png が存在しないので Seeding を終了する');
                        }
                    }
                    Comment::create([
                        'image_path' => 'comment'. $i. '.png',
                        'thumbnail_path' => 'comment'. $i. '.thumb.png',
                        'title' => "コメントタイトルその{$item->id}-{$i}",
                        'body' => "コメント本文コメント本文その{$item->id}-{$i}",
                        'item_id' => $item->id,
                    ]);
                }
            });
        }
    }
}
