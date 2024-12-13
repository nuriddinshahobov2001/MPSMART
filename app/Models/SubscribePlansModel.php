<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribePlansModel extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'price', 'month'];
}
