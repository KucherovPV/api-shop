<?php

namespace App\Models\OrdersModels;

use App\Models\ProductsModel\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_product extends Model
{
    use HasFactory;


    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_products', 'cart_id', 'product_id');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
