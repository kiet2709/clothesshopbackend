<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ModelResponses\UserResponses;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Service\UserService;
use App\Util\AppConstant;

class AuthController extends Controller
{
    public function __construct()
    {
        
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        $userResponse = new UserResponses();
        $userResponse->email = $user->email;
        $userResponse->id = $user->id;
        return response()->json([
                'status' => 'success',
                'user' => $userResponse,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => AppConstant::$VALIDATE_ERROR,
            ], 401);
        }
        

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user = UserService::findByEmail($request->email);
        $messageSetRole = UserService::setRole($user->id);
        // $messageSetRole use to debug if set role has error
        UserService::creatCart($user->id);
        $userResponse = new UserResponses();
        $userResponse->email = $user->email;
        $userResponse->id = $user->id;
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $userResponse,
            'id' => $user->id
        ]);
    }
}
