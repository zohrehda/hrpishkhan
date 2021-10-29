<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RequisitionProgress;
use Faker\Generator as Faker;
use \App\Requisition;
use \App\User;

$factory->define(RequisitionProgress::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    return [
        'requisition_id' => factory(Requisition::class)->create()->id,
        'determiner_id' => $user->id,
        'role' => rand(1, 5),
        'status' => 0
        //  'determiner_comment' =>$faker->


    ];
});
