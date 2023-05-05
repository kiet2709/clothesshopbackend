<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cloth extends Model
{
    use HasFactory;
    protected  $primaryKey = 'id';  

    protected $fillable = [
        'description',
        'name',
        'price',
        'brand_id',
        'category_id',
        'image_id'
    ];
}
