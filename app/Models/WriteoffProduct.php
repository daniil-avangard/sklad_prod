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
        'date_of_actruality',
    ];

    protected $casts = [
        'date_of_actruality' => 'date',
    ];

    public function writeoff()
    {
        return $this->belongsTo(Writeoff::class, 'writeoff_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
