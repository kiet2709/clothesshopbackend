<?php
namespace App\Service;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ModelResponses\CategoryResponses;
use App\Util\AppConstant;
use App\Models\ModelResponses\ClothResponses;
use Exception;

class CategoryService {

    public static function addCategory($name)
    {
        try {
            $a = DB::table('categories')->insert([
                    'name' => $name,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
            $category = DB::table('categories')->where('name', $name)->first();
            $categoryResponse = new CategoryResponses();
            $categoryResponse->id = $category->id;
            $categoryResponse->name = $category->name;
            return $categoryResponse;
            // return $a;
        } catch (Exception $th) {
            return AppConstant::$ERROR_WITH_CATEGORY;
            // return $th;
        }
    }

    public static function getAllCategories()
    {
        try {
            $categories = DB::table('categories')->get();
            $categoryResponses = array();
            foreach($categories as $category){
                $categoryResponse = new CategoryResponses();
                $categoryResponse->id = $category->id;
                $categoryResponse->name = $category->name;
                array_push($categoryResponses, $categoryResponse);
            }
            return $categoryResponses;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_CATEGORY;
            //throw $th;
        }
    }

    public static function getCategoryById($id){
        try {
            $category = DB::table('categories')->where('id', $id)->first();
            $categoryResponse = new CategoryResponses();
            $categoryResponse->id = $category->id;
            $categoryResponse->name = $category->name;
            return $categoryResponse;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_CATEGORY;
            //throw $th;
        }
    }

    public static function updateCategory($name, $id){
        try {
            $a = DB::table('categories')
                ->where('id', $id)
                ->update([
                    'name' => $name,
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
            $image->move(public_path(AppConstant::$UPLOAD_DIRECTORY_CATEGORY_IMAGE), $imageName);
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
                    'path' => AppConstant::$UPLOAD_DIRECTORY_CATEGORY_IMAGE . '/' . $imageName,
                    'title' => $imageName,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
                $imageId = DB::table('images')->where('title', $imageName)->value('id');
                $b = DB::table('categories')
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

                $d = DB::table('categories')
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
            $imageId = DB::table('categories')->where('id', $id)->value('image_id');
            $path = DB::table('images')->where('id', $imageId)->value('path');
            if (!$path){
                return AppConstant::$ERROR_WITH_IMAGE;
            }
            return $path;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_IMAGE;
        }
        
    }

    public static function getClothByCategoryId($id)
    {
        try {
            $clothes = DB::table('clothes')->where('category_id', $id)->get();
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

}