<?php
namespace App\Service;
use App\Models\ModelResponses\ClothResponses;
use App\Util\AppConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class ClothService {
    public static function addCloth(Request $request){
        try {
            $a = DB::table('clothes')->insert([
                    'description' => $request->description,
                    'name' => $request->name,
                    'price' => $request->price,
                    'brand_id' => $request->brandId,
                    'category_id' => $request->categoryId,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
            $cloth = DB::table('clothes')->where('name', $request->name)->first();
            $clothResponse = new ClothResponses();
            $clothResponse->id = $cloth->id;
            $clothResponse->description = $cloth->description;
            $clothResponse->name = $cloth->name;
            $clothResponse->price = $cloth->price;
            $clothResponse->brandId = $cloth->brand_id;
            $clothResponse->categoryId = $cloth->category_id;
            return $clothResponse;
            // return $a;
        } catch (Exception $th) {
            return AppConstant::$ERROR_WITH_CLOTH;
            // return $th;
        }
    }

    public static function getAllClothes()
    {
        try {
            $clothes = DB::table('clothes')->get();
            $clothResponses = array();
            foreach($clothes as $cloth){
                $clothResponse = new ClothResponses();
                $clothResponse->id = $cloth->id;
                $clothResponse->description = $cloth->description;
                $clothResponse->name = $cloth->name;
                $clothResponse->price = $cloth->price;
                $clothResponse->brandId = $cloth->brand_id;
                $clothResponse->categoryId = $cloth->category_id;
                array_push($clothResponses, $clothResponse);
            }
            return $clothResponses;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_CLOTH;
        }
    }

    public static function getClothById($id)
    {
        try {
            $cloth = DB::table('clothes')->where('id', $id)->first();
            $clothResponse = new ClothResponses();
            $clothResponse->id = $cloth->id;
            $clothResponse->description = $cloth->description;
            $clothResponse->name = $cloth->name;
            $clothResponse->price = $cloth->price;
            $clothResponse->brandId = $cloth->brand_id;
            $clothResponse->categoryId = $cloth->category_id;
            return $clothResponse;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_CLOTH;
            // return $th;
        }
    }

    public static function updateCloth(Request $request, $id)
    {
        try {
            $a = DB::table('clothes')
                ->where('id', $id)
                ->update([
                    'description' => $request->description,
                    'name' => $request->name,
                    'price' => $request->price,
                    'brand_id' => $request->brandId,
                    'category_id' => $request->categoryId,
                    'updated_at' => new DateTime()
                ]);
            return AppConstant::$UPDATE_SUCCESS;
        } catch (\Throwable $th) {
            //throw $th;
            return AppConstant::$ERROR_WITH_BRAND;
        }
    }

    public static function uploadImage(Request $request, $id)
    {
        if ($request->has('image')){
            $image = $request->image;
            $imageName = $id . '.' . $request->image->extension();
            $image->move(public_path(AppConstant::$UPLOAD_DIRECTORY_CLOTH_IMAGE), $imageName);
            self::saveImage($imageName, $id);
            return AppConstant::$UPLOAD_SUCCESS;
        }
        return AppConstant::$UPLOAD_FAILURE;
    }

    static function saveImage($imageName, $id)
    {
        try {
            $imageId = DB::table('images')->where('title', $imageName)->value('id');
            if (!$imageId){
                $a = DB::table('images')->insert([
                    'path' => AppConstant::$UPLOAD_DIRECTORY_CLOTH_IMAGE . '/' . $imageName,
                    'title' => $imageName,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
                $imageId = DB::table('images')->where('title', $imageName)->value('id');
                $b = DB::table('clothes')
                    ->where('id', $id)
                    ->update([
                        'image_id' => $imageId
                    ]);
                // use a, b, c, d for debug if nessessary
            } else {
                $c = DB::table('images')
                    ->update([
                        'updated_at' => new DateTime()
                    ]);

                $d = DB::table('clothes')
                    ->where('id', $id)
                    ->update([
                        'updated_at' => new DateTime()
                    ]);
            } 
        } catch (\Throwable $th) {
            return AppConstant::$UPLOAD_FAILURE;
        }
    }
    public static function getPathImage($id){
        try {
            $imageId = DB::table('clothes')->where('id', $id)->value('image_id');
            $path = DB::table('images')->where('id', $imageId)->value('path');
            if (!$path){
                return AppConstant::$ERROR_WITH_IMAGE;
            }
            return $path;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_IMAGE;
        }
        
    }
}