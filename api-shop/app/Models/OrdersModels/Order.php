<?php

namespace App\Models\OrdersModels;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function users()
    {
        return $this->belongsTo(User::class,);
    }
}
