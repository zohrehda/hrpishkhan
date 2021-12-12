<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Requisition;
use Faker\Generator as Faker;

$factory->define(Requisition::class, function (Faker $faker) {
    return [
        'department' => 'product',
        'level' => 'junior',
        'fa_title' => $faker->words,
        'en_title' => $faker->words,
        'position_count' => rand(1, 10),
        'location' => $faker->city,
        'direct_manager_name' => $faker->name,
        'direct_manager_position' => $faker->jobTitle,
        'venture' => $faker->title,
        'vertical' => $faker->title,
        'shift' => null,
        'is_full_time' => 1,
        'is_new' => 1,
        'replacement' => null,
        'field_of_study' => $faker->title,
        'degree' => 1,
        'experience_year' => rand(1, 10),
        'mission' => $faker->text,
        'competency' => json_encode([
            ['dfd',1]
        ]),
        'outcome' => $faker->text,
        'comment' => $faker->text,
        'interviewers' => null,
        'owner_id' => 1,
        'determiner_id' => 3,

    ];
});
