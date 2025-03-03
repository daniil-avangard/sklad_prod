<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    protected $fillable = ['city', 'name', 'user_id', 'sort_for_excel'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function divisionGroups()
    {
        return $this->belongsToMany(DivisionGroup::class, 'division_division_group');
    }

    public function divisionCategory()
    {
        return $this->belongsToMany(DivisionCategory::class, 'division_category_division');  // Связь с категорией
    }
}