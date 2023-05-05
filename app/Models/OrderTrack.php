<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTrack extends Model
{
    use HasFactory;

    protected $table = 'order_track';

    protected $fillable = [
        'status',
    ];
    public $timestamps = false;
}
