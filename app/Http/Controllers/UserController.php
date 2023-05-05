<?php

namespace App\Http\Controllers;

use App\Service\UserService;
use Illuminate\Http\Request;
use App\Models\ModelResponses\UserResponses;
use App\Models\ModelResponses\UserProfileResponses;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom_auth');
    }

    public function getAllUsers()
    {
        $users = UserService::getAllUsers();
        $userResponses = array();
        foreach ($users as $user){
            $userResponse = new UserResponses();
            $userResponse->id = $user->id;
            $userResponse->email = $user->email;
            array_push($userResponses, $userResponse);    
        }
        return response()->json($userResponses);
    }
    public function getCurrentUser(){
        $userProfileResponse = UserService::getCurrentUser(auth()->id()); 
        return response()->json([
            'user' => $userProfileResponse
        ]);
    }

    public function getUserById(int $id)
    {
        $user = UserService::getUserById($id);
        $userResponse = new UserResponses();
        $userResponse->id = $user->id;
        $userResponse->email = $user->email;
        return response()->json([
            'user' => $userResponse
        ]);
    }

    public function updateCurrentProfile(Request $request){
        $user = UserService::updateCurrentProfile($request, auth()->id());
        $userProfileResponse = UserService::getCurrentUser(auth()->id());
        return response()->json([
            'user' => $userProfileResponse
        ]);
    }

    public function uploadImage(Request $request){
        $message = UserService::uploadImage($request, auth()->id());
        return response()->json([
            'message' => $message
        ]);
    }

    public function getImage()
    {
        $path = UserService::getPathImage(auth()->id());
        if (str_contains($path, 'uploads')){
            header('Content-Type: image/jpeg');
            readfile($path);        
        } else {
            return response()->json([
                'message' => $path
            ]);
        }
        
    }

    public function getCurrentOrder()
    {
        $orderResponse = UserService::getCurrentOrder(auth()->id());
        return response()->json($orderResponse);
    }

}
