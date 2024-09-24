<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\Order\StatusEnum;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['comment'];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }
}
