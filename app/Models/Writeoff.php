<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\WriteoffStatusEnum;


class Writeoff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'writeoff_date',
        'status',
    ];

    protected $casts = [
        'writeoff_date' => 'datetime',
        'status' => WriteoffStatusEnum::class,
    ];

    public function products()
    {
        return $this->hasMany(WriteoffProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
