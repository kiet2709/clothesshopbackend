<?php

namespace App\Http\Controllers;

use App\Service\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function uploadImage(Request $request)
    {
        $message = BannerService::uploadImage($request);
        return response()->json([
            'message' => $message
        ]);
    }
    public function getAllImageId()
    {
        $data = BannerService::getAllImageId();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getImage($id)
    {
        $path = BannerService::getPathImage($id);
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
