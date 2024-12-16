<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user_models', 'role_id', 'user_id');
    }
}
