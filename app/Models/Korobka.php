<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Korobka extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'counter_number',
        'track_number',
        'delivery_method',
        'courier_date',
        'courier_time',
        'car_number',
        'car_date',
        'other_comment',
        'order_id',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
