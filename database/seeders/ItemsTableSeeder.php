<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
class ItemsTableSeeder extends Seeder
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
                if (!Storage::disk('public')->exists('item13.png')) {
                    if ( Storage::disk('public')->exists('item.png') ) {
                        Storage::disk('public')->delete('item'. $i. '.png');
                        Storage::disk('public')->copy('item.png', 'item'. $i. '.png');

                        InterventionImage::make(Storage::disk('public')->get('item.png'))->
                        resize(180, null, function($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->save(storage_path('app/public/'. 'item'. $i. '.thumb.png'));
                    } else {
                        throw new \Exception('storage/app/public 内に item.png が存在しないので Seeding を終了する');
                    }
                }
                Item::create([
                    'image_path' => 'item'. $i. '.png',
                    'thumbnail_path' => 'item'. $i. '.thumb.png',
                    'item_name' => 'アイテム名その'. $i,
                    'description' => 'アイテムの要約を入れます',
                    'body' => '<h4>小見出し</h4>
                    <p>アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。</p>
                    <h4>小見出し</h4>
                    <p>アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。アイテムの説明が入ります。</p>',
                ]);
            }
        }
    }
}
