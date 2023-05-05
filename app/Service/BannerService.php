<?php
namespace App\Service;

use App\Models\ModelResponses\UserProfileResponses;
use App\Util\AppConstant;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BannerService {
    public static function uploadImage(Request $request)
    {
        if ($request->has('image')){
            $image = $request->image;
            $imageName = time() . '.' . $request->image->extension();
            $image->move(public_path(AppConstant::$UPLOAD_DIRECTORY_BANNER_IMAGE), $imageName);
            self::saveImage($imageName);
            return AppConstant::$UPLOAD_SUCCESS;
        }
        return AppConstant::$UPLOAD_FAILURE;
    }

    static function saveImage($imageName)
    {
        try {
            $imageId = DB::table('images')->where('title', $imageName)->value('id');
            $a = DB::table('images')->insert([
                'path' => AppConstant::$UPLOAD_DIRECTORY_BANNER_IMAGE . '/' . $imageName,
                'title' => $imageName,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]);
            $imageId = DB::table('images')->where('title', $imageName)->value('id');
            $b = DB::table('banners')
                ->insert([
                    'image_id' => $imageId,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
            // use a, b, c, d for debug if nessessary
            
        } catch (\Throwable $th) {
            return AppConstant::$UPLOAD_FAILURE;
        }
    }

    public static function getPathImage($imageId){
        try {
            $path = DB::table('images')->where('id', $imageId)->value('path');
            if (!$path){
                return AppConstant::$ERROR_WITH_IMAGE;
            }
            return $path;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_IMAGE;
        }    
    }

    public static function getAllImageId(){
        try {
            $banners = DB::table('banners')->get();
            $imageIds = array();
            foreach($banners as $banner){
                array_push($imageIds, $banner->image_id);
            }
            return $imageIds;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_IMAGE;
        }
    }
}