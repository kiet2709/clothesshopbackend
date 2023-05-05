<?php

namespace App\Http\Controllers;

use App\Service\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom_auth');
    }

    public function addCategory(Request $request)
    {
        $categoryResponse = CategoryService::addCategory($request->name);
        return response()->json($categoryResponse);
        // return response()->json($request->name);
    }
    public function getAllCategories(){
        $categoryResponse = CategoryService::getAllCategories();
        return response()->json($categoryResponse);
    }
    public function getCategoryById($id){
        $categoryResponse = CategoryService::getCategoryById($id);
        return response()->json($categoryResponse);
    }
    public function updateCategory(Request $request, $id){
        $message = CategoryService::updateCategory($request->name, $id);
        return response()->json([
            'message' => $message
        ]);
    }
    public function uploadImage(Request $request, $id){
        $message = CategoryService::uploadImage($request, $id);
        return response()->json([
            'message' => $message
        ]);
    }
    public function getImage($id){
        $path = CategoryService::getPathImage($id);
        if (str_contains($path, 'uploads')){
            header('Content-Type: image/jpeg');
            readfile($path);        
        } else {
            return response()->json([
                'message' => $path
            ]);
        }
    }
    public function getClothByCategoryId($id)
    {
        $clothResponses = CategoryService::getClothByCategoryId($id);
        return response()->json($clothResponses);
    }
}
