<?php
namespace App\Service;

use App\Models\ModelResponses\UserProfileResponses;
use App\Util\AppConstant;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserService {
    
    public static function getRoles($id){
        try {
            $a = DB::table('users')
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.id', '=', 'roles.id')
            ->select('roles.name')->where('users.id',$id)->value('name');
            return $a;
        } catch (Exception $e) {
            return AppConstant::$ERROR_WITH_USER_ROLE;
        }    
    }

    public static function findByEmail($email)
    {
        try {
            $a = DB::table('users')
            ->where('email',$email)
            ->first();
            return $a;
        } catch (Exception $e) {
            return AppConstant::$ERROR_WITH_USER;
        }    
    }

    public static function setRole($id)
    {
        try {
            $a = DB::table('user_role')
            ->insert([
                'user_id' => $id,
                'role_id' => 2
            ]);
            return "set role successfully";
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_INSERT;
        }
    }

    public static function getAllUsers(){
        try {
            $users = DB::table('users')->get();
            return $users;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_USER;
        }
    }

    public static function getUserById($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();
            return $user;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_USER;
        }
    }

    public static function getCurrentUser($id){
        try {
            $user = DB::table('users')->where('id', $id)->first();
            $userProfileResponse = new UserProfileResponses();
            $userProfileResponse->id = $user->id;
            $userProfileResponse->name = $user->name;
            $userProfileResponse->email = $user->email;
            $userProfileResponse->address = $user->address;
            $userProfileResponse->birthday = $user->birthday;
            $userProfileResponse->enabled = $user->enabled;
            $userProfileResponse->phoneNumber = $user->phone_number;
            $userProfileResponse->cartId = $user->cart_id;
            $userProfileResponse->imageId = $user->image_id;
            $role = self::getRoles($id);
            $userProfileResponse->role = $role;
            return $userProfileResponse;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_USER;
        }
    }

    public static function updateCurrentProfile(Request $request, $id){
        try {
            $user = DB::table('users')
                ->where('id', $id)
                ->update([
                    'address' => $request->address,
                    'birthday' => $request->birthday,
                    'phone_number' =>$request->phone_number,
                    'updated_at' => new DateTime()
                ]);
            return $user;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_USER;
        }
    }

    

    public static function uploadImage(Request $request, $id)
    {
        if ($request->has('image')){
            $image = $request->image;
            $imageName = $id . '.' . $request->image->extension();
            $image->move(public_path(AppConstant::$UPLOAD_DIRECTORY_USER_IMAGE), $imageName);
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
                    'path' => AppConstant::$UPLOAD_DIRECTORY_USER_IMAGE . '/' . $imageName,
                    'title' => $imageName,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
                $imageId = DB::table('images')->where('title', $imageName)->value('id');
                $b = DB::table('users')
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

                $d = DB::table('users')
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
            $imageId = DB::table('users')->where('id', $id)->value('image_id');
            $path = DB::table('images')->where('id', $imageId)->value('path');
            if (!$path){
                return AppConstant::$ERROR_WITH_IMAGE;
            }
            return $path;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_IMAGE;
        }
        
    }

    public static function creatCart($id)
    {
        try {
            $cartId = DB::table('carts')->insertGetId([
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
            $cart = DB::table('carts')->orderBy('id', 'desc')->first();
            $user = DB::table('users')
                ->where('id', $id)
                ->update([
                    'cart_id' => $cart->id
                ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public static function getCurrentOrder($id)
    {
        try {
            $orders = DB::table('orders')
                ->where('user_id', $id)
                ->where(function($query) {
                    $query->where('order_track_id', 1)
                        ->orWhere('order_track_id', 2);
                })->get();
            $orderResponses = array();
            foreach($orders as $order){
                $orderResponse = OrderService::getOrder($order->id, $order->deliver_cost);
                array_push($orderResponses, $orderResponse);
            }
            return $orderResponses;
        } catch (\Throwable $th) {
            //throw $th;
            return AppConstant::$ERROR_WITH_ORDER;
        }
    }
}