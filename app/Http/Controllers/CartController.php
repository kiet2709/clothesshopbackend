<?php

namespace App\Http\Controllers;

use App\Service\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addCart(Request $request)
    {
        $cartResponse = CartService::addCart($request, auth()->id());
        return response()->json($cartResponse);
    }
    public function updateCart(Request $request)
    {
        $cartResponse = CartService::updateCart($request, auth()->id());
        return response()->json($cartResponse);
    }
    public function getCurrentCart()
    {
        $cartResponse = CartService::getCurrentCart(auth()->id());
        return response()->json($cartResponse);
    }
    public function deleteCartItem(Request $request)
    {
        $message = CartService::deleteCartItem(auth()->id(), $request->id);
        return response()->json([
            'message' => $message
        ]);
    }
}
