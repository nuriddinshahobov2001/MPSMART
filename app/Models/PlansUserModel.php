<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlansUserModel extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'plan_id', 'date_begin', 'date_end'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscribePlansModel::class, 'plan_id', 'id');
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            PlansUserModel::class,
            'plan_id', // Foreign key on PlansUserModel table
            'id',      // Foreign key on User table
            'id',      // Local key on SubscribePlansModel table
            'user_id'  // Local key on PlansUserModel table
        );
    }

}
