<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Requisition;
use Faker\Generator as Faker;

$factory->define(Requisition::class, function (Faker $faker) {
    $ee=[
        1=> [0=>"competency1" ,1=> 1 ] ,
        

     ] ;
    return [
       'department'=> 'product_design' ,
       'level'=>'principal' ,
       'en_title'=>$faker->jobTitle ,
       'fa_title'=>$faker->jobTitle ,
       'position_count'=>rand(1,10) ,
       'location'=>$faker->address ,
       'direct_manager_name'=>$faker->lastName  ,
       'direct_manager_position'=>$faker->lastName  ,
       'venture'=>$faker->title ,
       'vertical'=>'back_office' ,
       'shift'=>null ,
       'is_full_time'=>1 ,
       'is_new'=>1 ,
       'field_of_study'=>$faker->title ,
       'degree'=>'B.A/BSc' ,
       'experience_year'=>'2' ,
       'mission'=>$faker->text(),
       'competency'=>$ee ,
       'outcome'=>$faker->text ,
       'comment'=>null ,
       'interviewers'=>null ,
       'owner_id'=>2 

    ];
});
