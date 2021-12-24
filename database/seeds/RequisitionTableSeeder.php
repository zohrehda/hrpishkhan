<?php

use App\Requisition;
use Illuminate\Database\Seeder;

class RequisitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Requisition::class)->create();

    }
}
