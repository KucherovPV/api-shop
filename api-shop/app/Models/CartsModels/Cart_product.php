<?php

namespace App\Models\CartsModels;

use App\Models\ProductsModel\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_product extends Model
{
    use HasFactory;
    protected $table = 'cart_products';
    protected $fillable = [
        'cart_id',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
