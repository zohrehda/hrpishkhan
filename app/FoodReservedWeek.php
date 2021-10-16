<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodReservedWeek extends Model
{

    protected $table='food_reserved_week' ;
    protected $fillable = ['user_id', 'date_range'];

}
