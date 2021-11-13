<?php

namespace App\Classes;

class RequisitionItems
{
    public static function getItems($item = null)
    {
        $schema0 = [
            'department' => [
                'type' => 'select',
                'required' => true,
                'validate_rules' => ['required'],
                'options' => [1 => 'Tech', 'Product', 'Business Development',
                    'Commercial', 'Finance', ' General Services',
                    'Call Center', 'Human Resources', 'Legal',
                    'Management', 'Marketing', 'Operations', 'Other'
                ]
            ],
            'position' => [
                'type' => 'text',
                'required' => true,
                'validate_rules' => ['required'],
            ],
            'en_title' => [
                'type' => 'text',
                'validate_rules' => ['required'],
                'label' => 'English Job Title'
                , 'required' => true

            ],
            'fa_title' => [
                'type' => 'text',
                'label' => 'Persian Job Title',
                'required' => true,
                'validate_rules' => ['required'],
            ],
            'position_count' => [
                'type' => 'number',
                'label'=>'Position Count' ,
                'required' => true,
                'validate_rules' => ['required']
            ],
            'location' => [
                'type' => 'text',
                'required' => true,
                'validate_rules' => ['required']
            ],
            'direct_manager' => [
                'type' => 'text',
                'required' => true,
                'validate_rules' => ['required']
            ],
            'venture' => [
                'type' => 'text',
                'required' => true,
                'validate_rules' => ['required']
            ],
            'vertical' => [
                'type' => 'text',
                'required' => true,
                'validate_rules' => ['required_if:department,1,2']
            ],
            'seniority' => [
                'type' => 'text',
                'required' => true,
                'validate_rules' => ['required']
            ],
            'shift' => [
                'type' => 'select',
                'label' => 'Shift (Only for Call Center Positions)',
                'required' => false,
                'options' => [0 => 'Empty', 1 => 'Morning (Women)', 2 => 'Evening & Night (Men)', 3 => 'Holiday (Women)', 4 => 'Holiday (Men)'],
                'validate_rules' => ['required']
            ],
            'is_full_time' => [
                'label' => 'working hours',
                'type' => 'radio',
                'required' => true,
                'radios' => ['0' => 'Part Time', '1' => 'Full Time'],
                'validate_rules' => ['required']
            ],
            'is_new' => [
                'label' => 'hiring type',
                'type' => 'radio',
                'required' => true,
                'radios' => [ '0' => 'Replacement','1' => 'New hiring'],
                'validate_rules' => ['required']
            ],
            'replacement' => [
                'type' => 'text',
                'required' => false,
                'dynamic' => true,
                'validate_rules' => ['required_if:is_new,0']
            ],
            'field_of_study' => [
                'type' => 'text',
                'required' => true,
                'validate_rules' => ['required']
            ],
            'degree' => [
                'type' => 'select',
                'required' => true,
                'validate_rules' => ['required'],
                'options' => [1 => 'Diploma', 2 => 'B.A/BSc.', 3 => 'M.A/MSc.', 4 => 'PHD']
            ],
            'experience_year' => [
                'type' => 'select',
                'required' => true,
                'label'=>'Experience (Year)' ,
                'validate_rules' => ['required'],
                'options' => [1 => 'Fresh Graduate', 2 => '1', 3 => '1-2', 4 => '2-4', 5 => '4-6', 6 => '6-10', 7 => 'More than 10']
            ],
            'mission' => [
                'type' => 'textarea',
                'required' => true,
                'validate_rules' => ['required'],
                'placeholder' => 'Develop one to five sentences that describes why a role exists; e.g. the mission for the customer service rep is to help customers resolve their complaints with the highest level of courtesy possible'
            ],
           /* 'competency' => [
                'type' => 'multiple',
                'required' => true,
                'validate_rules' => ['required'],
                'placeholder' => 'dentify as many role-based competencies to describe the behaviors someone must demonstrate to achieve the outcomes; e.g. Teamwork, Analytical Skills, Attention to Detail, Negotiation Skills, etc.'
            ],*/
            'outcome' => [
                'type' => 'textarea',
                'required' => true,
                'validate_rules' => ['required'],
                'placeholder' => 'Develop 3-8 specific, objective outcomes that a person must accomplish to achieve an A-performance; e.g. improve customer satisfaction on a ten-point scale from 7.1 to 9.0 in 3 months'
            ],
            'about' => [
                'type' => 'textarea',
                'required' => false,
                'placeholder' => '',
                'validate_rules' => ['required'],
                'title' => 'About the team'
            ],

            // 'interviewers'

        ];
        $schema = [];
        $i = 0;
        foreach ($schema0 as $k => $v) {

            if (empty($v['label'])) {
                $v['label'] = ucwords(str_replace('_', ' ', $k));
            }

            if (empty($v['dynamic'])) {
                $v['dynamic'] = false;
            }


            $schema[$k] = $v;
        }
        //  dd($schema);
        if (is_array($item)) {
            return array_intersect($schema, $item);
        }
        if (is_string($item)) {
            return $schema[$item];
        }
        return $schema;
    }

    public static function getPartsItems()
    {

        return [
            'Requisition Information' => array_slice(self::getItems(), 0, 2),

            'Position Information' => array_slice(self::getItems(), 2, 12),

            'Job Requirements' => array_slice(self::getItems(), 14),

        ];

    }

    public static function getCommonDb()
    {
        return array_slice(self::getItems(), 2);

    }

}


