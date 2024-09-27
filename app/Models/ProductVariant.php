<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'is_active',
        'reserved',
        'date_of_actuality',
        'image',
        'reserved_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
