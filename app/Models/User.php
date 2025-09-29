<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasPermissions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'phone',
        'position',
        'avatar',
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

    public function divisionGroups(): BelongsToMany
    {
        return $this->belongsToMany(DivisionGroup::class, 'division_group_user');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function getCreatedAtYearAttribute()
    {
        return $this->created_at->format('Y');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
