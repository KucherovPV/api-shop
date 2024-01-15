<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JsonResponse;
use App\Models\CartsModels\Cart_product;
use App\Models\OrdersModels\Order;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class OrderController extends Controller
{
    private function authorizeCustomer($user)
    {
        try {
            $user = auth()->userOrFail();
            if (!$user->hasRole('customer')) {
                return response()->json(['message' => 'Login failed'], 401);
            }
        } catch (UserNotDefinedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        return null;
    }

    public function createOrder(Request $request)
    {
        $user = auth()->user();
        $authorizationResult = $this->authorizeCustomer($user);

        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }
        $cartId = $user->carts->id;

        $cartProducts = Cart_product::with('product')
            ->where('cart_id', $cartId)
            ->get();
        $onlyProductId = $cartProducts->map(function ($cartProduct) {
            return [
                $cartProduct->product_id,
            ];
        })->flatten()->toArray();

        $onlyPriceSum = array_sum($cartProducts->map(function ($cartProduct) {
            return [
                $cartProduct->product->price,
            ];
        })->flatten()->toArray());

        if($onlyProductId == null){
            return response()->json(['error' => ['code' => 422, 'message' => 'Cart is empty',],], 422);
        }

        $orderObject = new Order();
        $orderObject->user_id = $user->id;
        $orderObject->products = json_encode($onlyProductId);
        $orderObject->order_price = $onlyPriceSum;
        $orderObject->save();

        Cart_product::with('product')
            ->where('cart_id', $cartId)
            ->delete();
        return response()->json(["order_id" => $orderObject->id, "message" => "Order is processed"], 200);
    }

    public function showOrders()
    {
        $user = auth()->user();
        $authorizationResult = $this->authorizeCustomer($user);

        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }
        $orders = Order::select('id', 'products', 'order_price',)->where('user_id', $user->id)
            ->get();

        return response()->json( $orders, 200, [], JSON_NUMERIC_CHECK);
    }
}
