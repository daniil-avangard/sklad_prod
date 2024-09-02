<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ArivalStatusEnum;

class Arival extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'invoice',
        'arrival_date',
    ];

    protected $casts = [
        'arrival_date' => 'datetime',
        'status' => ArivalStatusEnum::class,
    ];

    public function products()
    {
        return $this->hasMany(ArivalProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
