<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected  $primaryKey = 'id';  

    protected $fillable = [
        'quantity',
        'cart_id',
        'size_id',
        'cloth_id'
    ];
}
