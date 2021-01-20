<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Requisition;
use Faker\Generator as Faker;

$factory->define(Requisition::class, function (Faker $faker) {
    return [
        'department'=>0 ,
        'level'=>0 ,
        'fa_title' => 'عنوان فارسی',
        'en_title' => 'English title',
        'position_count' => rand(1, 10),
        'shift'=>1 ,
        'is_full_time'=>1 ,
        'is_new'=>1 ,
        'field_of_study' => 'field_of_study',
        'degree' => 1,
        'competency' => 'Competency',
        'mission' => 'Mission',
        'outcome' => 'Outcome',
        'about'=>'about' ,
        'experience_year' => rand(1, 10),
        'determiner_id'=>1,
        'owner_id'=>1,
    ];
});
