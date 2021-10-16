<?php

namespace App\Http\Controllers\FoodReservation;

use App\Food;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FoodsController extends Controller
{
    public function index()
    {
        $foods = Food::orderBy('id', 'desc')->get();
        return view('FoodReservation.foods.index', compact('foods'));
    }

    public function store()
    {
        $this->validate(\request(), [
            'food-title' => 'required'
        ]);
        $new_food = Food::create(['title' => \request()->input('food-title')]);
        return redirect()->back();
    }

    public function delete($id)
    {
        $food = Food::find($id);
        if ($food) {
            $food->delete();
            return redirect()->route('FoodReservation.foods.index');
        }
        abort(404);
    }

    public function edit($id)
    {
        $food = Food::find($id);
        if ($food) {
            $foods = Food::orderBy('id', 'desc')->get();
            return view('FoodReservation.foods.index', compact('food', 'foods'));
        }
        abort(404);


    }

    public function update($id)
    {
        $food = Food::find($id);
        if ($food) {
            $food->title = \request()->input('food-title');
            $food->save();
            return redirect()->route('FoodReservation.foods.index');
        }
        abort(404);

    }


}
