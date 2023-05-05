<?php

namespace App\Http\Controllers;

use App\Service\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function submit(Request $request)
    {
        $orderResponse = OrderService::submit($request, auth()->id());
        return response()->json($orderResponse);
    }
    public function getAllOrders()
    {
        $orderResponses = OrderService::getAllOrders();
        return response()->json($orderResponses);
    }
    public function getOrderById($id)
    {
        $orderResponse = OrderService::getOrderById($id);
        return response()->json($orderResponse);
    }
    public function updateStatus(Request $request, $id)
    {
        $message = OrderService::updateStatus($id, $request->id);
        return response()->json([
            'message' => $message
        ]);
    }
}
