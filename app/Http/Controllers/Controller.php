<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Hekmatinasser\Verta\Verta;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static $today = 'gg';

    public $departments = [0 => 'Tech', 1 => 'Product', 2 => 'Other Departments'];
    public $levels = [
        0 => 'Trainee',
        1 => 'Junior',
        2 => 'Mid',
        3 => 'Senior',
        4 => 'Lead',
        5 => 'Analyst',
        6 => 'Associate Product Manager',
        7 => 'Product Manager',
        8 => 'Senior Product Manager',
        9 => 'Associate Director',
        10 => 'Director',
        11 => 'Senior Director',
        12 => 'Specialist',
        13 => 'Senior Specialist',
        14 => 'Manager',
        15 => 'Senior Manager'
    ];
    public $Approver = [
         1 => 'CPO',
        2 => 'CTO'  ,
        3 => 'Director/VP',
        4 => 'CXO',
        5 => 'HRBP',
        6 => 'HR Director',
       ];
    public $algorithm_array = [
        0 => [0 => [5, 2],
            1 => [5, 2],
            2 => [5, 2],
            3 => [5, 2],
            4 => [5, 2, 6],
        ],
        1 => [0 => [1,5],
            5 => [1,5],
            6 => [1,5],
            7 => [1,5],
            8 => [1,5,6],
            9 => [1,5,6],
            10 =>[1,5,6],
            11 =>[1,5,6],
        ],
        2 => [
            12 => [3, 5],
            13 => [3, 5],
            4 => [3, 5],
            14 => [3,4,5,6],
            15 => [3,4,5,6],
            10 => [4,5,6],
            11 => [4,5,6],

        ]
    ];

    public static function nextWeek()
    {
        $today_num_day = Verta::today()->dayOfWeek;

        $week_first_day = Verta::now()->subDays($today_num_day)->addDays(7);
        $fd = clone $week_first_day;
        $week_last_day = $fd->addDays(6);
        return $week_first_day->format('Y-m-d') . '|' . $week_last_day->format('Y-m-d');
    }

    public static function lastWeek()
    {
        $today_num_day = Verta::today()->dayOfWeek;

        $week_first_day = Verta::now()->subDays($today_num_day)->subDays(7);
        $fd = clone $week_first_day;
        $week_last_day = $fd->addDays(6);
        return $week_first_day->format('Y-m-d') . '|' . $week_last_day->format('Y-m-d');
    }

    public static function thisWeek()
    {
        $today_num_day = Verta::today()->dayOfWeek;

        $week_first_day = Verta::now()->subDays($today_num_day);
        $fd = clone $week_first_day;
        $week_last_day = $fd->addDays(6);
        return $week_first_day->format('Y-m-d') . '|' . $week_last_day->format('Y-m-d');
    }

    public static function today()
    {
        return Verta::today()->format('Y-m-d');
    }


}
