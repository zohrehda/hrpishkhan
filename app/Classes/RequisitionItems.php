<?php

namespace App\Classes;

class RequisitionItems
{
    public static function getItems($item = null)
    {
        $schema0 = [
            'department' => [
                'type' => 'select',
                'grid_col' => 6,
                'required' => true,
                'validate_rules' => ['required'],
                'options' => StaffHierarchy::$departments


            ],
            'level' => [
                'type' => 'select',
                'required' => true,
                'validate_rules' => ['required'],
                'options' => [],
                'grid_col' => 6,
            ],
            // requisition_information
            'en_title' => [
                'type' => 'text',
                'validate_rules' => ['required'],
                'label' => 'English Job Title',
                'grid_col' => 12,
                'required' => true

            ],
            'fa_title' => [
                'type' => 'text',
                'label' => 'Persian Job Title',
                'required' => true,
                'grid_col' => 12,
                'validate_rules' => ['required'],
            ],
            'position_count' => [
                'type' => 'number',
                'label' => 'Position Count',
                'required' => true,
                'grid_col' => 6,
                'validate_rules' => ['required']
            ],
            'location' => [
                'type' => 'text',
                'required' => true,
                'grid_col' => 6,
                'validate_rules' => ['required']
            ],
            'direct_manager_name' => [
                'type' => 'text',
                'label' => 'Direct Manager (Name)',
                'required' => true,
                'grid_col' => 6,
                'validate_rules' => ['required']
            ],
            'direct_manager_position' => [
                'type' => 'text',
                'label' => 'Direct Manager (Position)',
                'required' => true,
                'grid_col' => 6,
                'validate_rules' => ['required']
            ],
            'venture' => [
                'type' => 'text',
                'required' => true,
                'grid_col' => 6,
                'validate_rules' => ['required']
            ],
            'vertical' => [
                'type' => 'select',
                'grid_col' => 6,
                'options' => [
                    'back_office' => 'Back Office',
                    'excellence_center' => 'Center Of Excellence',
                    'customer_retention' => 'Customer Retention',
                    'creative_services' => 'Creative Services',
                    'credit_services' => 'Credit Services',
                    'capital_management' => 'Capital management',
                    'charging' => 'Charging',
                    'data' => 'Date',
                    'dispatching' => 'Dispatching',
                    'driver_retention_engagement' => 'Driver retention and Engagement',
                    'finance' => 'Finance',
                    'insurance' => 'Insurance',
                    'map' => 'Map',
                    'offering' => 'Offering',
                    'passenger_ride_experience' => 'Passenger Ride Experience',
                    'pricing' => 'Pricing',
                    'pwa' => 'PWA',
                    'ride_life_cycle' => 'Ride Life Cycle',
                    'poirot' => 'poirot',
                    'shared_service' => 'Shared Service',
                    'supper_app' => 'Supper App',
                    'users' => 'Users',
                    'safety' => 'Safety & Support',
                    'baly' => 'Baly',
                    'driver_ride_experience' => 'Driver Ride Experience',
                    'none_vertical' => 'None-vertical',
                    'other' => 'Other',

                ],
                'required' => true,
                'validate_rules' => ['required_if:department,tech,product,product_design'],
                'required_if' => [
                    'department' => ['tech', 'product', 'product_design']
                ]
            ],
            'seniority' => [
                'disabled' => true,
                'type' => 'text',
                'grid_col' => 6,
                'required' => true,
                'validate_rules' => ['required']
            ],
            'shift' => [
                'type' => 'multiple',
                'grid_col' => 12,
                'data' => [
                    'options' => 
                    ['morning' => 'Morning',
                     'evening_night' => 'Evening & Night',
                      'holiday' => 'Holiday',
                    'day_12h' => 'Day- 12 Hours',
                      'night_12h' => 'Night- 12 Hours'
                    ]
                ],
                //  'label' => 'Shift (Only for Call Center Positions)',
                'required' => false,
                //  'options' => [0 => 'Empty', 1 => 'Morning (Women)', 2 => 'Evening & Night (Men)', 3 => 'Holiday (Women)', 4 => 'Holiday (Men)'],
                'validate_rules' => ['nullable']
            ],
            'is_full_time' => [
                'label' => 'working hours',
                'type' => 'radio',
                'grid_col' => 12,
                'required' => true,
                'radios' => ['0' => 'Part Time', '1' => 'Full Time'],
                'validate_rules' => ['required']
            ],
            'is_new' => [
                'label' => 'hiring type',
                'type' => 'radio',
                'required' => true,
                'grid_col' => 12,
                'radios' => ['0' => 'Replacement', '1' => 'New Hiring'],
                'validate_rules' => ['required']
            ],
            'replacement' => [
                'type' => 'text',
                'label' => 'Replacement Of',
                'required' => false,
                'grid_col' => 12,
                'dynamic' => true,
                'validate_rules' => ['required_if:is_new,0']
            ],
            // job_requirements
            'field_of_study' => [
                'type' => 'text',
                'required' => true,
                'grid_col' => 12,
                'validate_rules' => ['required']
            ],
            'degree' => [
                'type' => 'select',
                'label'=>'Degree (Minimum)' ,
                'required' => true,
                'grid_col' => 6,
                'validate_rules' => ['required'],
                'options' => ['diploma' => 'Diploma', 'B.A/BSc' => 'B.A/BSc.', 'M.A/MSc' => 'M.A/MSc.', 'phd' => 'PHD']
            ],
            'experience_year' => [
                'type' => 'select',
                'grid_col' => 6,
                'required' => true,
                'label' => 'Experience (Year)',
                'validate_rules' => ['required'],
                'options' => 
                ['fresh_graduate' => 'Fresh Graduate', 
                '1-2' => '1-2',
                 '2-4' => '2-4',
                  '4-6' => '4-6', 
                  '6-10' => '6-10', 
                  'more-10' => 'More than 10']
            ],
            'mission' => [
                'type' => 'textarea',
                'required' => true,
                'grid_col' => 12,
                'validate_rules' => ['required'],
                'placeholder' => 'Develop one to five sentences that describes why a role exists; e.g. the mission for the customer service rep is to help customers resolve their complaints with the highest level of courtesy possible'
            ],
            'outcome' => [
                'type' => 'textarea',
                'grid_col' => 12,
                'required' => true,
                'validate_rules' => ['required'],
                'placeholder' => 'Develop 3-8 specific, objective outcomes that a person must accomplish to achieve an A-performance; e.g. improve customer satisfaction on a ten-point scale from 7.1 to 9.0 in 3 months'
            ],
            'comment' => [
                'type' => 'textarea',
                'required' => false,
                'grid_col' => 12,
                'placeholder' => 'Please share any other comments related to this request',
                'validate_rules' => ['nullable'],
                'title' => 'About the team'
            ],
            'competency' => [
                'type' => 'multiple',
                'required' => true,
                'data' => [],
                'grid_col' => 12,
                'validate_rules' => ['required', 'array', 'min:5'],
            ],
            'interviewers' => [
                'type' => 'multiple',
                'required' => false,
                'data' => [],
                'grid_col' => 12,
                'validate_rules' => [],
            ],
            'determiners' => [
                'type' => 'multiple',
                'required' => false,
                'data' => [],
                'grid_col' => 12,
                'validate_rules' => ['array'],
            ],

        ];
        $schema = [];
        $i = 0;
        foreach ($schema0 as $k => $v) {

            if (!empty($v['disabled'])) {
                continue;
            }
            if (empty($v['label'])) {
                $v['label'] = ucwords(str_replace('_', ' ', $k));
            }

            if (empty($v['dynamic'])) {
                $v['dynamic'] = false;
            }


            $schema[$k] = $v;
        }

        if (is_array($item)) {
            return array_intersect($schema, $item);
        }
        if (is_string($item)) {
            return $schema[$item];
        }
        return $schema;
    }

    public static function shortValueItems()
    {
        return array_slice(self::getItems(), 0, 17);
    }

    public static function longValueItems()
    {
        return array_slice(self::getItems(), 17, 3);
    }

    public static function getPartsItems()
    {

        return [
            'Requisition Information' => array_slice(self::getItems(), 0, 2),

            'Position Information' => array_slice(self::getItems(), 2, 12),

            'Job Requirements' => array_slice(self::getItems(), 14),

        ];

    }

    public static function getSections()
    {
        return [
            'requisition_information' => [
                'title' => 'Requisition Information',
                'items' => array_slice(self::getItems(), 0, 2),
            ],

            'position_information' => [
                'title' => 'Position Information',
                'items' => array_slice(self::getItems(), 2, 12),

            ],

            'job_requirements' => [
                'title' => 'Job Requirements',
                'items' => array_slice(self::getItems(), 14, 6),
            ],

            'competency' => [
                'title' => 'Competency',
                'items' => array_slice(self::getItems(), 20, 1),
            ],
            'interviewers' => [
                'title' => 'Interviewers',
                'items' => array_slice(self::getItems(), 21, 1),
            ],
            'determiners' => [
                'title' => 'Approver Selection',
                'items' => array_slice(self::getItems(), 22, 1),
            ]


            // approver Selection

        ];

    }

    public static function getCommonDb()
    {
        return array_slice(self::getItems(), 2);
    }

}


