<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreUserModel extends Model
{
    use HasFactory;
    protected $fillable = ['name','api_key','store_id'];

    public function store(){
        return $this->belongsTo(StoreModel::class, 'store_id');
    }
}
