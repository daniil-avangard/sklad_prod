<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasPermissions;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissions;


    protected $fillable = [
        'surname',
        'first_name',
        'middle_name',
        'email',
        'password',
        'is_admin',
        'division_id',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function getCreatedAtYearAttribute()
    {
        return $this->created_at->format('Y');
    }
}
