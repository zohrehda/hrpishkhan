<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FoodPlan;
use App\Model;
use Faker\Generator as Faker;

$factory->define(FoodPlan::class, function (Faker $faker) {

    return [
        'id'=>'1' ,
        'date'=> $faker->date ,
        'foods_id'=>'["1"]'
    ];
});
