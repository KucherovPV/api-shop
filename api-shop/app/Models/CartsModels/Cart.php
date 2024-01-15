<?php

namespace App\Models\CartsModels;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->hasOne(User::class);
    }
}
