<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodPlan extends Model
{
    protected $table = 'food_plans';
    protected $fillable = ['foods_id', 'date'];
    public $timestamps = false;

}
