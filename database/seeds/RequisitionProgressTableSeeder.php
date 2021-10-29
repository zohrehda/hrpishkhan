<?php

use Illuminate\Database\Seeder;
use App\RequisitionProgress;

class RequisitionProgressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ee = factory(RequisitionProgress::class)->create(['determiner_id' => 3]);
        factory(RequisitionProgress::class)->create(['requisition_id' => $ee->requisition_id]);
        factory(RequisitionProgress::class)->create(['determiner_id' => 3, 'requisition_id' => $ee->requisition_id]);

    }
}
