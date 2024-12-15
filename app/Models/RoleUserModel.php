<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUserModel extends Model
{
    use HasFactory;
    protected $table = 'role_user_models';
    protected $fillable = ['user_id', 'role_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role(){
        return $this->belongsTo(RoleModel::class, 'role_id');
    }
}
