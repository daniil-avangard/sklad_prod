<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySnapshot extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'snapshot_date',
        'quantity'
    ];
    
    protected $dates = ['snapshot_date'];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
