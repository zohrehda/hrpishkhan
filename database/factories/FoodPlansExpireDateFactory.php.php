<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Expire;

use Faker\Generator as Faker;

$factory->define(Expire::class, function (Faker $faker) {


    return [
        'id' => '3',
        'plan_date_range' => '1399-07-05/1399-07-11',
        'expire' => '1399/07/09',
    ];
});
