<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArivalProduct extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'arival_id',
        'product_id',
        'quantity',
    ];

    public function arival()
    {
        return $this->belongsTo(Arival::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
}
