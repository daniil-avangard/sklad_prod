<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ProductStatusEnum;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'quantity',
        'reserve',
        'status',
        'user_id',
        'division_id',
    ];

    protected $casts = [
        'status' => ProductStatusEnum::class,
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





}
