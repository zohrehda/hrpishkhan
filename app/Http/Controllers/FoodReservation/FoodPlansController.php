<?php

namespace App\Http\Controllers\FoodReservation;

use App\Expire;
use App\Food;
use App\FoodPlan;
use App\FoodReserved;
use App\FoodReservedWeek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Verta;
use Hekmatinasser\Verta\Verta;

class FoodPlansController extends Controller
{
    public function index($week_date)
    {
        $week = explode('|', $week_date);
        $firstDay = Verta::parse($week[0]);
        $lastDay = Verta::parse($week[1]);
        $plan_date_range = str_replace('|', '/', $week_date);
        $expire = Expire::where('plan_date_range', $plan_date_range)->get()->pluck('expire')->first();
        $expire = ($expire) ? Verta::parse($expire) : null;
        $today = verta();
        $diff = $today->diffDays($lastDay);
        $expired = ($diff < 0) ? true : false;
        $plan_created = ($expire) ? true : false;
        $foods = Food::all();

        if ($expired) {
            $plan_status = 'expired';
        } elseif ($plan_created) {
            $plan_status = 'created';
        } else {
            $plan_status = 'empty';
        }
        $plan_list=Expire::all() ;

        return view('FoodReservation.food-plans.index', compact('plan_list','plan_status', 'foods', 'firstDay', 'lastDay', 'expire'));

    }

    public function store()
    {
        $days = array_slice(\request()->all(), 2);
        $fd = array_keys(array_slice($days, 0, 1))[0];
        $ld = array_keys(array_slice($days, -1, 1))[0];
        $plan_date_range = $fd . '/' . $ld;
        $expire = Expire::where('plan_date_range', $plan_date_range)->get()->first();
        if ($expire) {
            $expire->expire = \request()->input('expire');
            $expire->save();
        } else {
            $newExpire = Expire::create([
                'plan_date_range' => $plan_date_range,
                'expire' => \request()->input('expire'),
            ]);
        }

        foreach ($days as $day => $foods_id) {
            $reserved = FoodPlan::where('date', $day)->get()->first();
            if ($reserved) {
                $reserved->foods_id = json_encode($foods_id);
                $reserved->save();
                $msg = 'food plan updated successfully!';
            } else {
                $newReserve = FoodPlan::create([
                    'date' => $day,
                    'foods_id' => json_encode($foods_id),
                ]);
                $msg = 'food plan ctreated successfully!';

            }
        }
        return redirect()->back()->with('success', $msg);
    }

    public function delete($firstDay, $lastDay)
    {
        $firstDay = Verta::parse($firstDay);
        $lastDay = Verta::parse($lastDay);
        $plan_date_range = $firstDay->format('Y-m-d') . '/' . $lastDay->format('Y-m-d');
        Expire::where('plan_date_range', $plan_date_range)->first()->delete();
        
        if(FoodReservedWeek::where('date_range', $plan_date_range)->first())
    FoodReservedWeek::where('date_range', $plan_date_range)->first()->delete();

        $fd = clone $firstDay;
        $ls = clone $lastDay;
        $diff = $firstDay->diffDays($lastDay);

        for ($i = 0; $i <= $diff; $i++) {
            if ($i == 0) {
                $day = $fd->format('Y-m-d');
            } else {
                $day = $fd->addDay()->format('Y-m-d');
            }
            FoodPlan::where('date', $day)->first()->delete();
             FoodReserved::where('date',$day)->delete() ;
        }
        $msg = 'food plan deleted successfully!';
        return redirect()->back()->with('success', $msg);;
    }


}
