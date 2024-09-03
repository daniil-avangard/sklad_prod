<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriteoffProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'writeoff_id',
        'product_id',
        'quantity',
    ];

    public function writeoff()
    {
        return $this->belongsTo(Writeoff::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
