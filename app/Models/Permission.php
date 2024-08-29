<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'action', 'model'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


    public function getName(): string
    {
        $model = class_basename($this->model);

        $action = ucfirst($this->action);

        return trim("{$model} {$action}");
    }
    
}
