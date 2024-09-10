<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ProductStatusEnum;
use App\Enum\Products\PointsSale\Operator;
use App\Models\ProductVariant;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'user_id',
        'kko_hall',
        'kko_account_opening',
        'kko_manager',
        'kko_operator',
        'express_hall',
        'express_operator',       
        'sku',
    ];

    protected $casts = [
        'kko_hall' => 'boolean',
        'kko_account_opening' => 'boolean',
        'kko_operator' => Operator::class,
        'kko_manager' => 'boolean',
        'express_hall' => 'boolean',
        'express_operator' => Operator::class,
    ];

    public function getStatusName(): string
    {
        return $this->status->getStatusName();
    }

    public function getStatusColor(): string
    {
        return $this->status->getStatusColor();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function arivalProduct()
    {
        return $this->hasMany(ArivalProduct::class);
    }

    public function writeOffProduct()
    {
        return $this->hasMany(WriteOffProduct::class);
    }

    public function divisions()
    {
        return $this->belongsToMany(Division::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }




}
