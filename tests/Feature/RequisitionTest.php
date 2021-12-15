<?php

namespace Tests\Feature;

use App\Requisition;
use App\RequisitionApprovalProgress;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RequisitionTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();
        $this->seed('UsersTableSeeder');
    }

    public function test_requisition_can_be_created()
    {
        $creator = User::first();
        $response = $this->actingAs($creator)->post('panel/requisitions/store', [

            'department'=>0 ,
            'level'=>0 ,
            'fa_title' => 'عنوان فارسی',
            'en_title' => 'English title',
            'position_count' => rand(1, 10),
            'time'=>1 ,
            'hiring_type'=>1 ,
            'field_of_study' => 'field_of_study',
            'degree' => 1,
            'competency' => 'Competency',
            'mission' => 'Mission',
            'outcome' => 'Outcome',
            'experience_year' => rand(1, 10),
            'determiners'=>array(1) ,
        ]);
        $this->assertCount(1, Requisition::get());
    }

    public function test_requisition_determiner_status_resets_on_creator_update()
    {
        $creator = User::first();

        $requisition = factory(Requisition::class)->create();

        $requisition->progresses()->createMany([
            [
                'determiner_id' => 2,
                'role' => 1
            ],
            [
                'determiner_id' => 3,
                'role' => 2
            ],
        ]);
        $requisition->accept();
        $this->actingAs($creator)->post('/panel/requisitions/' . $requisition->id . '/update', [
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

        foreach ($requisition->progresses as $progress) {
            $this->assertEquals(0, $progress->getOriginal('status'));
        }
    }

    public function test_requisition_can_be_accepted_by_determiner()
    {
        $creator = User::first();
        $requisition = factory(Requisition::class)->create();
        $requisition->progresses()->createMany([
            [
                'determiner_id' => 2,
                'role' => 1
            ],
            [
                'determiner_id' => 3,
                'role' => 2
            ],
        ]);
        $progress_id = $requisition->current_progress()->id;
        $requisition->accept();

        $this->assertEquals(1, $requisition->progresses()->find($progress_id)->getOriginal('status'));
    }

    public function test_requisition_can_be_rejected_by_determiner()
    {
        $creator = User::first();
        $requisition = factory(Requisition::class)->create();
        $requisition->progresses()->createMany([
            [
                'determiner_id' => 2,
                'role' => 1
            ],
            [
                'determiner_id' => 3,
                'role' => 2
            ],
        ]);
        $progress_id = $requisition->current_progress()->id;
        $requisition->reject();

        $this->assertEquals(2, $requisition->progresses()->find($progress_id)->getOriginal('status'));
    }
}
