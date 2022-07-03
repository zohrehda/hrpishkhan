<?php

namespace App\Classes;

use App\User;
use Illuminate\Support\Facades\Auth;

class Determiners
{
    protected $determiners;
    protected $requisition;

    public function __construct($determiners, $requisition)
    {
        $this->determiners = $determiners;
        $this->requisition = $requisition;

    }

    public static function ordered($determiners, $requisition)
    {
        $obj = new self($determiners, $requisition);
        return $obj->get_ordered_determiners($determiners, $requisition);
    }

    private function get_ordered_determiners($determiners, $requisition)
    {
        $determiners = $determiners ?? [];
        return $this->add_hr_admin_determiner($this->add_details_to_determiners($determiners), $requisition);
    }

    private function add_details_to_determiners($determiners)
    {
        $determiners_array = [];
        foreach ($determiners as $determiner) {
            $determiners_array[] = User::by_provider($determiner)->id;
        }
        return $determiners_array;
    }

    private function add_hr_admin_determiner($determiners, $requisition)
    {
        if ($this->can_prepend_hr_admin_determiner($determiners, $requisition)) {
            array_unshift($determiners, User::hr_admin()->id);
        }

        if ($this->can_append_hr_admin_determiner($determiners)) {
            array_push($determiners, User::hr_admin()->id);

        }

        return $determiners;
    }

    private function can_prepend_hr_admin_determiner($determiners, $requisition)
    {
        if ($requisition->owner->id == User::hr_admin()->id && !count($determiners)) {
            return true;
        }
        if ((Auth::user()->is_hr_admin()) || (count($determiners) && $determiners[0] == User::hr_admin()->id)) {
            return false;
        }
        return true;
    }

    private function can_append_hr_admin_determiner($determiners)
    {

        if (!count($determiners) || (count($determiners) && last($determiners) == User::hr_admin()->id)) {
            return false;
        }
        return true;
    }

}
