<?php

use Illuminate\Database\Seeder;
use App\RequisitionApprovalProgress;

class RequisitionProgressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ee = factory(RequisitionApprovalProgress::class)->create(['determiner_id' => 3]);
        factory(RequisitionApprovalProgress::class)->create(['requisition_id' => $ee->requisition_id]);
        factory(RequisitionApprovalProgress::class)->create(['determiner_id' => 3, 'requisition_id' => $ee->requisition_id]);

    }
}
