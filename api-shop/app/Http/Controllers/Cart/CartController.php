<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\CartsModels\Cart_product;
use App\Models\ProductsModel\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use App\Models\User;

class CartController extends Controller
{
    private function authorizeCustomer()
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

    public function addToCart(Request $request, $id)
    {
        $user = auth()->user();
        $authorizationResult = $this->authorizeCustomer();


        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        if (!Product::find($id)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $cart = $user->carts;

        $cartObject = new Cart_product();
        $cartObject->id = $cartObject->where('cart_id', $cart->id)->max('id') + 1;
        $cartObject->cart_id = $cart->id;
        $cartObject->product_id = $id;
        $cartObject->save();

        return response()->json(['message' => 'Product added to cart'], 201);
    }

    public function showCart(Request $request)
    {
        $user = auth()->user();
        $authorizationResult = $this->authorizeCustomer();

        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }
        $cartId = $user->carts->id;
        $cartProducts = Cart_product::with('product')
            ->where('cart_id', $cartId)
            ->get();

        $transformedCartProducts = $cartProducts->map(function ($cartProduct) {
            return [
                'id' => $cartProduct->id,
                'product_id' => $cartProduct->product_id,
                'name' => $cartProduct->product->name,
                'description' => $cartProduct->product->description,
                'price' => $cartProduct->product->price,
            ];
        });

        return response()->json( $transformedCartProducts, 200, [], JSON_NUMERIC_CHECK);
    }

    public function removeFromCart(Request $request, $id)
    {
        $user = auth()->user();
        $cart = $user->carts;

        $authorizationResult = $this->authorizeCustomer();

        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        if (!$cart) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $cartId = $user->carts->id;
        Cart_product::with('product')
            ->where('cart_id', $cartId)
            ->where('product_id', $id)
            ->delete();
        return response()->json(['message' => 'Item removed from cart'], 200);
    }


}
