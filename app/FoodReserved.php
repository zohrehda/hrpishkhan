<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodReserved extends Model
{
    protected $table='food_reserved' ;
    protected $fillable = ['user_id', 'date', 'food_id'];
    public $timestamps = false ;

    public function food()
    {
     return   $this->belongsTo(Food::class,'food_id','id') ;
    }

    public function user()
    {
      return  $this->belongsTo(User::class,'user_id','id') ;
    }
}
