<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expire extends Model
{
    protected $table = 'food_plans_expire_date';
    protected $fillable = ['plan_date_range', 'expire'];
    public $timestamps = false;
}
