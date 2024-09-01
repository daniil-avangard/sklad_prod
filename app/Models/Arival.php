<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arival extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'invoice',
        'arrival_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
