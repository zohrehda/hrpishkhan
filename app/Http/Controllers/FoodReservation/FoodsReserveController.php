<?php

namespace App\Http\Controllers\FoodReservation;

use App\Expire;
use App\Food;
use App\FoodPlan;
use App\FoodReserved;
use App\FoodReservedWeek;
use App\Http\Controllers\Controller;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodsReserveController extends Controller
{
    public function index($week_date)
    {
        $week = explode('|', $week_date);

        $planedF = FoodPlan::where('date', $week[0])->first();
        $planedL = FoodPlan::where('date', $week[1])->first();

        $planed = ($planedF && $planedL) ? true : false;


        $firstDay = Verta::parse($week[0]);
        $lastDay = Verta::parse($week[1]);
   //     $plan_date_range = $firstDay->format('Y-m-d') . '/' . $lastDay->format('Y-m-d');
        $plan_date_range = str_replace('|','/',$week_date) ;
        $expire = Expire::where('plan_date_range', $plan_date_range)->get()->first();
        if ($expire) {
            $today = verta();
            $expire_date = Verta::parse($expire->expire);
            $diff = $today->diffDays($expire_date);

            $expired = ($diff < 0) ? true : false;
        } else {
            $expired = false;
        }

    //    $frw = FoodReservedWeek::where('user_id', Auth::user()->id)->get();
    $frw=Expire::all() ; 
       
        
        return view('FoodReservation.food-reserve.index', compact('frw','planed', 'firstDay', 'lastDay', 'expire', 'expired'));


    }

    public function store()
    {

        $days = array_slice(\request()->all(), 1);
        $fd = array_keys(array_slice($days, 0, 1))[0];
        $ld = array_keys(array_slice($days, -1, 1))[0];
        $plan_date_range = $fd . '/' . $ld;
        $expire = Expire::where('plan_date_range', $plan_date_range)->get()->first();
        $today = verta();
        $expire_date = Verta::parse($expire->expire);
        $diff = $today->diffDays($expire_date);
        $expired = ($diff < 0) ? true : false;

        if (!$expired) {
            foreach ($days as $day => $food_id) {
                $foodReserved = FoodReserved::where('user_id', Auth::user()->id)->where('date', $day)->first();
                if ($foodReserved) {
                    $foodReserved->food_id = $food_id;
                    $foodReserved->save();
                    $msg = 'food reservation updated successfully!';
                } else {
                    FoodReserved::create([
                        'user_id' => Auth::user()->id,
                        'date' => $day,
                        'food_id' => $food_id
                    ]);
                    $msg = 'food reservation completed successfully!';
                }
            }
        }
        $frw = FoodReservedWeek::where('date_range', $plan_date_range)->first();
        if (!$frw) {
            FoodReservedWeek::create([
                'date_range' => $plan_date_range,
                'user_id' => Auth::user()->id,
            ]);
        }
        return redirect()->back()->with('success', $msg);
    }
}
