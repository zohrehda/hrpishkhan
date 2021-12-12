<?php

namespace App\Http\Controllers;


use App\Classes\RequisitionItems;
use App\Classes\StaffHierarchy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Hekmatinasser\Verta\Verta;


class SettingsController extends BaseController
{
    public function levels(Request $request)
    {
        return [
            'levels' => StaffHierarchy::$levels,
            'departments_level' => StaffHierarchy::$departments_levels,
        ];
    }

    public function setting()
    {
        return [
            'form_items' => RequisitionItems::getItems()
        ];
    }
}
