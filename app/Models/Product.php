<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ProductStatusEnum;
use App\Enum\Products\PointsSale\Operator;
use App\Models\ProductVariant;
use App\Models\Division;
use App\Models\Basket;
use App\Models\WriteoffProduct;
use Illuminate\Support\Str;

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
        'company_id',
        'category_id',
        'min_stock'
    ];

    protected $casts = [
        'kko_hall' => 'boolean',
        'kko_account_opening' => 'boolean',
        'kko_operator' => Operator::class,
        'kko_manager' => 'boolean',
        'express_hall' => 'boolean',
        'express_operator' => Operator::class,
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->sku = Str::random(10);
        });
    }

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

    public function writeoffProduct()
    {
        return $this->hasMany(WriteoffProduct::class);
    }

    public function divisions()
    {
        return $this->belongsToMany(Division::class);
    }

    public function divisionGroups()
    {
        return $this->belongsToMany(DivisionGroup::class, 'division_group_product', 'product_id', 'division_group_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function baskets()
    {
        return $this->belongsToMany(Basket::class)->withPivot('quantity');
    }
}
