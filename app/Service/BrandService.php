<?php

namespace App\Service;
use App\Models\ModelResponses\BrandResponses;
use App\Models\ModelResponses\ClothResponses;
use App\Util\AppConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class BrandService {
    public static function addBrand(Request $request)
    {
        try {
            $name = $request->name;
            $brand = DB::table('brands')->insert([
                    'name' => $name,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
            $brand = DB::table('brands')->where('name', $name)->first();
            $brandResponse = new BrandResponses();
            $brandResponse->id = $brand->id;
            $brandResponse->name = $brand->name;
            return $brandResponse;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_BRAND;
        }  
    }

    public static function getAllBrands()
    {
        try {
            $brands = DB::table('brands')->get();
            $brandResponses = array();
            foreach($brands as $brand){
                $brandResponse = new BrandResponses();
                $brandResponse->id = $brand->id;
                $brandResponse->name = $brand->name;
                array_push($brandResponses, $brandResponse);
            }  
            return $brandResponses;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_BRAND;
        }
    }

    public static function getBrandById($id)
    {
        try {
            $brand = DB::table('brands')->where('id', $id)->first();
            $brandResponse = new BrandResponses();
            $brandResponse->id = $brand->id;
            $brandResponse->name = $brand->name;
            return $brandResponse;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_BRAND;
        }  
    }

    public static function updateBrand($name, $id)
    {
        try {
            $a = DB::table('brands')
                ->where('id', $id)
                ->update([
                    'name' => $name,
                    'updated_at' => new DateTime()
                ]);
            return AppConstant::$UPDATE_SUCCESS;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_BRAND;
            // return 'abc';
        }
    }
    public static function getClothByBrandId($id)
    {
        try {
            $clothes = DB::table('clothes')->where('brand_id', $id)->get();
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