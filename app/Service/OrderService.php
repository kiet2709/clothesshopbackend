<?php

namespace App\Service;
use App\Models\ModelResponses\OrderItemResponses;
use App\Models\ModelResponses\OrderResponses;
use App\Util\AppConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Exception;

class OrderService {
    public static function submit(Request $request, $id)
    {
        try {
            $deliveryCost = AppConstant::$deleviryCost[$request->deliveryMethod];
            $deliveryMethod = AppConstant::$deleviryMethod[$request->deliveryMethod];
            // create order
            $a = DB::table('orders')->insert([
                'deliver_cost' => $deliveryCost,
                'deliver_method' => $deliveryMethod,
                'order_date' => new DateTime(),
                'order_track_id' => 1,
                'user_id' => $id
            ]);
            $orderId = DB::table('orders')->where('user_id', $id)->orderBy('id', 'desc')->value('id');
            // $request->orderItems is list of cart items
            $orderItems = $request->orderItems;
            foreach($orderItems as $orderItem){
                $cartItem = DB::table('cart_item')->where('id', $orderItem)->first();
                $b = DB::table('order_item')->insert([
                    'quantity' => $cartItem->quantity,
                    'cloth_id' => $cartItem->cloth_id,
                    'size_id' => $cartItem->size_id,
                    'order_id' => $orderId,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]);
                CartService::deleteCartItem($id, $cartItem->id);
            }
            
            //return order
            return self::getOrder($orderId, $deliveryCost);
            
        } catch (\Throwable $th) {
            //throw $th;
            return AppConstant::$ERROR_WITH_ORDER;
        }
    }

    public static function getOrder($orderId, $deliveryCost){
        $orderResponse = new OrderResponses();
        $order = DB::table('orders')->where('id', $orderId)->first();
        $orderResponse->id = $order->id;
        $orderResponse->orderDate = $order->order_date;
        $orderResponse->deliveryMethod = $order->deliver_method;
        $orderResponse->deliveryCost = $deliveryCost;
        $orderItemsRess = DB::table('order_item')->where('order_id', $orderId)->get();
        $orderResponse->order_track = DB::table('order_track')->where('id', $order->order_track_id)->value('status');
        $orderItemResponses = array();
        $totalCost = 0;
        foreach($orderItemsRess as $orderItemsRes){
            $orderItemResponse = new OrderItemResponses();
            $orderItemResponse->id = $orderItemsRes->id;
            $orderItemResponse->quantity = $orderItemsRes->quantity;
            $cloth = DB::table('clothes')->where('id', $orderItemsRes->cloth_id)->first();
            $orderItemResponse->cloth = $cloth;
            $totalCost = $totalCost + $cloth->price * $orderItemResponse->quantity;
            $size = DB::table('sizes')->where('id', $orderItemsRes->size_id)->first();
            $orderItemResponse->choiceSize = $size->name;
            array_push($orderItemResponses, $orderItemResponse);
        }
        $orderResponse->orderItems = $orderItemResponses;
        $orderResponse->totalCostTemporary = $totalCost;
        $orderResponse->totalCostFinal = $totalCost + $deliveryCost;
        return $orderResponse;
    }

    public static function getAllOrders()
    {
        try {
            $orders = DB::table('orders')->get();
            $orderResponses = array();
            foreach($orders as $order){
                $orderResponse = self::getOrder($order->id, $order->deliver_cost);
                array_push($orderResponses, $orderResponse);
            }
            return $orderResponses;
        } catch (\Throwable $th) {
            //throw $th;
            return AppConstant::$ERROR_WITH_ORDER;
        }
    }
    public static function getOrderById($id)
    {
        try {
            $order = DB::table('orders')->where('id', $id)->first();
            $orderResponse = self::getOrder($order->id, $order->deliver_cost);
            return $orderResponse;
        } catch (\Throwable $th) {
            //throw $th;
            return AppConstant::$ERROR_WITH_ORDER;
        }
    }
    public static function updateStatus($orderId, $orderTrackId)
    {
        try {
            DB::table('orders')
                ->where('id', $orderId)
                ->update([
                    'order_track_id' => $orderTrackId
                ]);
            return AppConstant::$UPDATE_SUCCESS;
        } catch (\Throwable $th) {
            return AppConstant::$ERROR_WITH_ORDER;
        }
    }
}