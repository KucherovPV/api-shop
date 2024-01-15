<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductsModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ProductController extends Controller
{
    private function authorizeCustomer()
    {
        try {
            $user = auth()->userOrFail();
            if (!$user->hasRole('administrator')) {
                return response()->json(['message' => 'Forbidden for you'], 401);
            }
        } catch (UserNotDefinedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        return null;
    }

    public function product()
    {
        $response = ProductsModel\Product::select('id', 'name', 'description', 'price',)->get();
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }

    public function productSave(Request $request)
    {
        $authorizationResult = $this->authorizeCustomer();
        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        $rules = ['name' => 'required|min:2|max:115',
            'description' => 'required|min:2',
            'price' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $product = ProductsModel\Product::create($request->all());
        return response()->json(['id' => $product->id, 'message' => 'Product added'], 201);
    }

    public function productEdit(Request $request, $id)
    {
        $authorizationResult = $this->authorizeCustomer();
        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        $product = ProductsModel\Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $rules = ['name' => 'required|min:2|max:115',
            'description' => 'required|min:2',
            'price' => 'required|numeric'
        ];
        $inputFields = $request->only(['name', 'description', 'price']);

        $filteredRules = array_intersect_key($rules, $inputFields);

        $validator = Validator::make($inputFields, $filteredRules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dataToUpdate = array_filter($request->only(['name', 'description', 'price']), function ($value) {
            return $value !== null;

        });
        $product->update($dataToUpdate);
        return response()->json($product->only('id', 'name', 'description', 'price'), 200);
    }

    public function productDelete($id)
    {
        $authorizationResult = $this->authorizeCustomer();
        if ($authorizationResult instanceof JsonResponse) {
            return $authorizationResult;
        }

        $product = ProductsModel\Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $product->delete();

        return response()->json(["message" => "Product removed"], 200);
    }
}
