<?php

namespace App\Http\Controllers\FoodReservation;

use App\Expire;
use App\Food;
use App\FoodReserved;
use App\Http\Controllers\Controller;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class FoodReportsController extends Controller
{
    public function weekly($week_date = null)
    {

        if ($week_date) {
            $week = explode('|', $week_date);

            $firstDay = Verta::parse(convert2english($week[0]));
            $lastDay = Verta::parse(convert2english($week[1]));

            $diff = $firstDay->diffDays($lastDay);
            $fd = clone $firstDay;
            $ld = clone $lastDay;
            $day_range = array();
            for ($i = 0; $i <= $diff; $i++) {
                $day_range[] = ($i == 0) ? $fd->format('Y-m-d') : $fd->addDay()->format('Y-m-d');
            }

            $report = FoodReserved::whereIn('date', $day_range)->whereNotIn('food_id', [0])->get()->groupBy('food_id')->map(function ($item) {
                return $item->groupBy('date')->map(function ($item) {
                    return count($item);
                })->toArray();
            })->toArray();

            array_walk($report, function (&$value, $key) use ($day_range) {
                $k = array_keys($value);
                $array = [];
                foreach ($day_range as $d) {
                    if (in_array($d, $k)) {
                        $array[$d] = $value[$d];
                    } else {
                        $array[$d] = 0;
                    }
                }
                $value = ['food_title' => FoodName($key),
                    'data' => $array
                ];
            });
            return view('FoodReservation.food-report.weekly', compact('day_range', 'report', 'firstDay', 'lastDay'));
        }
        return view('FoodReservation.food-report.weekly');
    }

    public function daily($day_date = null)
    {
        if ($day_date) {
            $date = Verta::parse($day_date);
            $daily = FoodReserved::where('date', $date->format('Y-m-d'))->whereNotIn('food_id', [0])->get()->keyBy(function ($item) {
                return ($item->user)?$item->user->name:'deleted user';
                
            })->map(function ($item) {
                return $item->food->title;
            })->toArray();
            $daily_foods_count = array_count_values($daily);
            session(['daily' => $daily]);
            session(['today' => $date->format('%B %d، %Y')]);
            session(['foods_count'=>$daily_foods_count ]) ;


            return view('FoodReservation.food-report.daily', compact('daily', 'date','daily_foods_count'));
        }
        return view('FoodReservation.food-report.daily');


    }

    public function createPDF()
    {
        $daily = session('daily');
        $today = session('today');
        $foods_count = session('foods_count');
        $pdf = PDF::loadView('FoodReservation.food-report.print', array('daily' => $daily, 'today' => $today,'foods_count'=>$foods_count));
        return $pdf->stream('report-' . $today . '.pdf');
    }
}
