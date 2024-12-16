<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'verification_code'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Возвращает уникальный идентификатор, обычно поле `id`
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function plans()
    {
        return $this->hasMany(PlansUserModel::class, 'user_id', 'id');
    }
    public function plan()
    {
        return $this->hasOneThrough(
            SubscribePlansModel::class,
            PlansUserModel::class,
            'user_id', // Foreign key on PlansUserModel table
            'id',      // Foreign key on SubscribePlansModel table
            'id',      // Local key on User table
            'plan_id'  // Local key on PlansUserModel table
        );
    }
}
