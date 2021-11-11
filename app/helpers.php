<?php

use App\FoodPlan;
use App\Food;
use App\FoodReserved;
use Illuminate\Support\Facades\Auth;

function convert2english($string)
{
    $newNumbers = range(0, 9);
    // 1. Persian HTML decimal
    $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
    // 2. Arabic HTML decimal
    $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
    // 3. Arabic Numeric
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    // 4. Persian Numeric
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

    $string = str_replace($persianDecimal, $newNumbers, $string);
    $string = str_replace($arabicDecimal, $newNumbers, $string);
    $string = str_replace($arabic, $newNumbers, $string);
    return str_replace($persian, $newNumbers, $string);
}

function getFoodDay($day)
{
    $foodPlans = FoodPlan::where('date', $day)->get()->first();
    if ($foodPlans) {
        return json_decode($foodPlans->foods_id, true);
    }
    return [];

}

function FoodName($food_id)
{
    $food = Food::where('id', $food_id)->first();
    if ($food) {
        return $food->title;
    }
    return;
}

function getFoodReserved($date)
{
    $food_reserved = FoodReserved::where('user_id', Auth::user()->id)->where('date', $date)->first();
    if ($food_reserved) {
        return $food_reserved->food_id;
    } else {
        return;
    }
}

function colle($array)
{
    if (!is_array($array)) {
        return;
    }
    foreach ($array as $item) {

    }
    collect($array);
}

?>
