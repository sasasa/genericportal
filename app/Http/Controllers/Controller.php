<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Http\UploadedFile;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 画像を必要に応じて回転させたり、縮小したりして保存する
     *
     * @param  UploadedFile $file
     * @return string
     */
    protected function saveProperlyImage($file, string $extension = null): array
    {
        if ($extension) {
            $file_name = time() . '.' . $extension;
            $thumb_file_name = time() . '.thumb.' . $extension;
        } else {
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $thumb_file_name = time() . '.thumb.' . $file->getClientOriginalExtension();
        }

        // EXIF情報を読み取りスマホ画像を回転させる
        $exif = @exif_read_data($file);
        $tmpImg = InterventionImage::make($file);
        if(!empty($exif) && !empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $tmpImg = $tmpImg->rotate(90);
                    break;
                case 3:
                    $tmpImg = $tmpImg->rotate(180);
                    break;
                case 6:
                    $tmpImg = $tmpImg->rotate(-90);
                    break;
            }
        }

        //アスペクト比を維持、画像サイズを横幅1080pxにして保存する。
        $tmpImg->
        resize(1080, null, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(storage_path('app/public/'. $file_name));

        $tmpImg->
        resize(180, null, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(storage_path('app/public/'. $thumb_file_name));


        return [$file_name, $thumb_file_name];
    }
}
