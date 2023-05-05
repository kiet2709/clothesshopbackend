<?php

namespace App\Service;
use App\Models\ModelResponses\CartItemResponses;
use App\Models\ModelResponses\CartResponses;
use App\Util\AppConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class CartService {


    public static function addCart(Request $request, $id)
    {
        try {
            $cartId = DB::table('users')->where('id', $id)->value('cart_id');
            if (self::checkIfItemExist($cartId, $request->clothId)){
                self::updateCart($request, $id);
            }
            $a = DB::table('cart_item')->insert([
                    'quantity' => $request->quantity,
                    'cloth_id' => $request->clothId,
                    'size_id' => $request->sizeId,
                    'cart_id' => $cartId,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
            return self::getCurrentCart($id);
            
            // return $a;
        } catch (Exception $th) {
            return AppConstant::$ERROR_WITH_CART;
            // return $th;
        }
    }
    public static function checkIfItemExist($cartId, $clothId)
    {
        $cloth = DB::table('cart_item')
            ->where('cart_id', $cartId)
            ->where('cloth_id', $clothId)
            ->first();
        return $cloth;
    }
    public static function updateCart(Request $request, $id)
    {
        $cartId = DB::table('users')->where('id', $id)->value('cart_id');
        $a = DB::table('cart_item')
            ->where('cart_id', $cartId)
            ->where('cloth_id', $request->clothId)
            ->update([
                'quantity' => $request->quantity,
                'size_id' => $request->sizeId,
                'cart_id' => $cartId,
                'updated_at' => new DateTime()
            ]);
        return self::getCurrentCart($id);
    }
    public static function getCurrentCart($id)
    {
        $cartId = DB::table('users')->where('id', $id)->value('cart_id');
        $cartItems = DB::table('cart_item')->where('cart_id', $cartId)->get();
        $cartItemResponses = array();
        foreach($cartItems as $cartItem){
            $cartItemResponse = new CartItemResponses();
            $cartItemResponse->id = $cartItem->id;
            $cartItemResponse->quantity = $cartItem->quantity;
            $cartItemResponse->cloth = DB::table('clothes')->where('id', $cartItem->cloth_id)->first();
            $cartItemResponse->choiceSize = DB::table('sizes')->where('id', $cartItem->size_id)->value('name');
            array_push($cartItemResponses, $cartItemResponse);
        }
        $cartResponse = new CartResponses();
        $cartResponse->id = $cartId;
        $cartResponse->cartItems = $cartItemResponses;
        $cartResponse->userId = $id;
        return $cartResponse;
    }
    public static function deleteCartItem($userId, $cartItemId)
    {
        try {
            $cartId = DB::table('users')->where('id', $userId)->value('cart_id');
            $a = DB::table('cart_item')
                ->where('cart_id', $cartId)
                ->where('id', $cartItemId)
                ->delete();
            return AppConstant::$DELETE_SUCCESS;
        } catch (\Throwable $th) {
            //throw $th;
            return AppConstant::$DELETE_FAILURE;
        }
        
    }
}