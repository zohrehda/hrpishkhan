<?php

namespace App\Extract;

use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Collection;

class StaffInfo
{
    public static function get()
    {
        $excel = Excel::toCollection(new \App\Imports\StaffInfo, 'docs/staff_information.xls')->first();
        $columns = $excel->first();
        $staff_info = $excel->skip(1);

        $data = [];
        $row = [];
        foreach ($staff_info as $item) {

            $data['personnel_code'] = $item->get(1);
            $data['name'] = $item->get(2);
            $data['position'] = $item->get(3);
            $data['department'] = $item->get(4);
            $data['team'] = $item->get(5);
            $data['email'] = $item->get(6);
            $data['direct_report'] = $item->get(7);
            $data['direct_manager'] = $item->get(8);
            $data['operational_unit'] = $item->get(9);
            $row[]  =collect($data)->recursive();


        }

        return collect($row);

    }
}
