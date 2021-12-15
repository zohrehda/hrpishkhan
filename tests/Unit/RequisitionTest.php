<?php

namespace Tests\Unit;

use App\Requisition;
use App\RequisitionApprovalProgress;
use App\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequisitionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed('UsersTableSeeder');
    }

    public function set_determiners($requisition)
    {
        $requisition->progresses()->createMany([
            [
                'determiner_id' => 2,
                'role' => 3
            ],
            [
                'determiner_id' => 3,
                'role' => 4
            ]
        ]);
    }

    public function test_requisition_can_be_created()
    {
        Requisition::create([
            'department'=>0 ,
            'level'=>0 ,
            'fa_title' => 'عنوان فارسی',
            'en_title' => 'English title',
            'position_count' => rand(1, 10),
            'is_full_time'=>1 ,
            'is_new'=>1 ,
            'field_of_study' => 'field_of_study',
            'degree' => 1,
            'competency' => 'Competency',
            'mission' => 'Mission',
            'outcome' => 'Outcome',
            'experience_year' => rand(1, 10),
            'determiner_id'=>1 ,
            'owner_id'=>1,
        ]);

        $this->assertCount(1, Requisition::get());
    }

    public function test_requisition_getting_accepted_by_determiner()
    {
        $requisition = factory(Requisition::class)->create();
        $this->set_determiners($requisition);
        $first_determiner_id = $requisition->current_determiner()->id;
        $requisition->accept();
        $this->assertNotEquals($first_determiner_id, $requisition->current_determiner()->id);
    }

    public function test_requisition_getting_rejected_by_determiner()
    {
        $requisition = factory(Requisition::class)->create();
        $this->set_determiners($requisition);
        $first_determiner_id = $requisition->current_determiner()->id;
        $requisition->accept();
        $requisition->reject();
        $this->assertEquals($first_determiner_id, $requisition->current_determiner()->id);
    }

    public function test_requisition_owner_update_resets_all_progresses_status()
    {
        $requisition = factory(Requisition::class)->create();
        $creator = $requisition->owner;
        $this->set_determiners($requisition);

        $requisition->accept();

        $request = $this->actingAs($creator)->post('/panel/requisitions/' . $requisition->id . '/update', [
            'fa_title' => 'عنوان فارسی',
            'en_title' => 'English title',
            'competency' => 'Competency',
            'mission' => 'Mission',
            'outcome' => 'Outcome',
            'position_count' => rand(1, 10),
            'experience_year' => rand(1, 10),
            'field_of_study' => 1,
            'degree' => 1,
        ]);

        $request->assertSessionHasNoErrors();
        foreach ($requisition->progresses as $progress) {
            $this->assertEquals(RequisitionApprovalProgress::PENDING_STATUS, $progress->getOriginal('status'));
        }
    }

    public function test_requisition_can_be_deleted()
    {
        $requisition = factory(Requisition::class)->create();
        $requisition->delete();

        $this->assertCount(0, Requisition::get());
    }
}
