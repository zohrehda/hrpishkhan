<?php

namespace App\Classes;

class StaffHierarchy
{
    public static $departments = [
        'tech' => 'Tech',
        'product' => 'Product',
        'product_design' => 'Product Design',
        'business_development' => 'Business Development',
        'commercial' => 'Commercial',
        'finance' => 'Finance',
        'general_services' => ' General Services',
        'call_center' => 'Call Center',
        'human_resources' => 'Human Resources',
        'legal' => 'Legal',
        'management' => 'Management',
        'marketing' => 'Marketing',
        'operations' => 'Operations',
        'other' => 'Other'
    ];
    public static $levels = [
        'trainee' => 'Trainee',
        'junior' => 'Junior',
        'mid' => 'Mid',
        'senior' => 'Senior',
        'principal' => 'Principal',
        'senior_principal' => 'Senior Principal',
        'engineering_manager' => 'Engineering Manager',
        'senior_engineering_manager' => 'Senior Engineering Manager',
        'associate_director' => 'Associate Director',
        'associate' => 'Associate',
        'director' => 'Director',
        'senior_director' => 'Senior Director',
        'cto' => 'CTO',
        'cpo' => 'CPO',
        'cxo' => 'CXO',

        'lead' => 'Lead',
        'manager' => 'Manager',
        'senior_manager' => 'Senior Manager',
        'specialist' => 'Specialist',
        'analyst' => 'Analyst',
        'associate_product_manager' => 'Associate Product Manager',
        'product_manager' => 'Product Manager',
        'senior_product_manager' => 'Senior Product Manager',
        'senior_specialist' => 'Senior Specialist',
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

    public static $departments_levels = [
        'tech' => [
            'trainee', 'junior', 'mid', 'senior', 'principal', 'engineering_manager'
            , 'senior_engineering_manager', 'associate_director', 'director', 
            'senior_director'  
        ],

        'product' => [
            'trainee', 'associate', 'mid', 'senior', 'principal',
            'associate_director', 'director', 'senior_director'
            , 'cpo'
        ],
        'product_design' => [
            'trainee', 'junior', 'mid', 'senior', 'principal', 'senior_principal',
            'lead', 'manager'
        ],
        'ect' => [
   'trainee',   'specialist' ,'senior_specialist' ,
   'lead' , 'manager' , 'senior_manager' ,'director' ,

    
        ]
    ];


}
