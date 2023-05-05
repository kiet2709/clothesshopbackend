<?php
namespace App\Service;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ModelResponses\SizeResponses;
use App\Util\AppConstant;

class SizeService {

    public static function getAllSizes()
    {
        try {
            $sizes = DB::table('sizes')->get();
            $sizeResponses = array();
            foreach($sizes as $size){
                $sizeResponse = new SizeResponses();
                $sizeResponse->id = $size->id;
                $sizeResponse->name = $size->name;
                array_push($sizeResponses, $sizeResponse);
            }
            return $sizeResponses;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_SIZE;
        }
    }

    public static function getSizeById($id)
    {
        try {
            $size = DB::table('sizes')->where('id', $id)->first();
            $sizeResponse = new SizeResponses();
            $sizeResponse->id = $size->id;
            $sizeResponse->name = $size->name;
            return $sizeResponse;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_SIZE;
        }
    }

}