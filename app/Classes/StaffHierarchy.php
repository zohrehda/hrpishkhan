<?php

namespace App\Classes;

class StaffHierarchy
{
    public static $departments = [0 => 'Tech', 1 => 'Product', 2 => 'Other Departments'];
    public static $levels = [
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
    public static $Approver = [
        1 => 'CPO',
        2 => 'CTO',
        3 => 'Director/VP',
        4 => 'CXO',
        5 => 'HRBP',
        6 => 'HR Director',
    ];
    public static $algorithm_array = [
        0 => [0 => [5, 2],
            1 => [5, 2],
            2 => [5, 2],
            3 => [5, 2],
            4 => [5, 2, 6],
        ],
        1 => [0 => [1, 5],
            5 => [1, 5],
            6 => [1, 5],
            7 => [1, 5],
            8 => [1, 5, 6],
            9 => [1, 5, 6],
            10 => [1, 5, 6],
            11 => [1, 5, 6],
        ],
        2 => [
            12 => [3, 5],
            13 => [3, 5],
            4 => [3, 5],
            14 => [3, 4, 5, 6],
            15 => [3, 4, 5, 6],
            10 => [4, 5, 6],
            11 => [4, 5, 6],

        ]
    ];


}
