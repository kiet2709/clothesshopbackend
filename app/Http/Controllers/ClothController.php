<?php

namespace App\Http\Controllers;

use App\Service\ClothService;
use Illuminate\Http\Request;

class ClothController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom_auth');
    }

    public function addCloth(Request $request){
        $clothResponse = ClothService::addCloth($request);
        return response()->json($clothResponse);
    }
    public function getAllClothes()
    {
        $clothResponses = ClothService::getAllClothes();
        return response()->json($clothResponses);
    }
    public function getClothById($id)
    {
        $clothResponses = ClothService::getClothById($id);
        return response()->json($clothResponses);
    }
    public function updateCloth(Request $request, $id)
    {
        $message = ClothService::updateCloth($request, $id);
        return response()->json([
            'message' => $message
        ]);
    }
    public function uploadImage(Request $request, $id){
        $message = ClothService::uploadImage($request, $id);
        return response()->json([
            'message' => $message
        ]);
    }
    public function getImage($id){
        $path = ClothService::getPathImage($id);
        if (str_contains($path, 'uploads')){
            header('Content-Type: image/jpeg');
            readfile($path);        
        } else {
            return response()->json([
                'message' => $path
            ]);
        }
    }
}
