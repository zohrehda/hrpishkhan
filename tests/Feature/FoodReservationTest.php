<?php

namespace Tests\Feature;


use App\Food;
use App\FoodPlan;
use App\FoodReserved;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FoodReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed('UsersTableSeeder');
    }

    public function test_supervisor_can_add_food()
    {

        $creator = User::first();
        $response = $this->actingAs($creator)->post('/panel/foods', [
            'food-title' => 'فسنجون'
        ]);
        $this->assertCount(1, Food::get());

    }

    public function test_food_can_be_deleted()
    {
        $food = factory(Food::class)->create();
        $food->delete();

        $this->assertCount(0, Food::get());
    }

    public function test_supervisor_can_update_food()
    {
        $food = factory(Food::class)->create();
        $creator = User::first();

        $request = $this->actingAs($creator)->post('/panel/foods/edit' . $food->id, [
            'food-title' => 'فسنجون'
        ]);

        $this->assertCount(1, Food::get());
    }

    public function test_food_plan_can_be_created()
    {
        $food_plan = factory(FoodPlan::class)->create();

        $this->assertCount(1, FoodPlan::get());
    }

    public function test_food_plan_can_be_deleted()
    {
        $food = factory(FoodPlan::class)->create();
        $food->delete();
        $this->assertCount(0, Food::get());


    }

    public function test_user_can_reserve_food()
    {

        $creator = User::first();
        $response = $this->actingAs($creator)->post('/panel/food-reserve/store', [
            "_token" => "DsCj6ELPaD2rS0stJ3873vvKSmfj6ROdJktVGzEU",
            "1399-07-05" => "1",
            "1399-07-06" => "3",
            "1399-07-07" => "2",
            "1399-07-08" => "1",
            "1399-07-09" => "2",
            "1399-07-10" => "0",
            "1399-07-11" => "0"
        ]);
        $this->assertCount(1, FoodReserved::get());

    }


}
