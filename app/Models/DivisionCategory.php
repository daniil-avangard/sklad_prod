<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'user_id'];

    public function divisions() {
        return $this->belongsToMany(Division::class, 'division_category_division');
    }

    public function user()
    {
        return $this->belongsTo(User::class);  // Один пользователь может создать много категорий
    }
}