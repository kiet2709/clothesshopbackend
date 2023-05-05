<?php

namespace App\Http\Controllers;

use App\Service\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom_auth');
    }

    public function addBrand(Request $request){
        $brandResponse = BrandService::addBrand($request);
        return response()->json([
            'brand' => $brandResponse
        ]);   
    }

    public function getAllBrands()
    {
        $brandResponses = BrandService::getAllBrands();
        return response()->json($brandResponses); 
    }

    public function getBrandById($id){
        $brandResponses = BrandService::getBrandById($id);
        return response()->json($brandResponses); 
    }
    
    public function updateBrand(Request $request, int $id){
        $message = BrandService::updateBrand($request->name, $id);
        return response()->json([
            'message' => $message
        ]);
    }
    public function getClothByBrandId($id)
    {
        $clothResponses = BrandService::getClothByBrandId($id);
        return response()->json($clothResponses);
    }
}
