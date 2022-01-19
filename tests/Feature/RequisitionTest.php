<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Requisition ;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RequisitionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     use RefreshDatabase ;
 
    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed('UsersTableSeeder');
    }

    public function set_determiners($requisition)
    {
        $requisition->approval_progresses()->createMany([
            [
                'determiner_id' => 1,
                'role' => 1,
                'type'=>'admin_primary_pending'
            ],
            [
                'determiner_id' => 3,
                'role' => 2 ,
                'type'=>'determiners_pending' ,
            ],
            [
                'determiner_id' => 1,
                'role' => 3 ,
                'type'=>'admin_final_pending' ,
            ]
        ]);
    }

   
    public function test_requisition_getting_accepted_by_determiner(){

        $requisition=factory(Requisition::class)->create([
            'determiner_id'=>1
        ]);
        $this->set_determiners($requisition) ;

        $this->be(User::find(1)) ;
        $first_determiner_id = $requisition->current_determiner()->id;
        $requisition->accept();
        $this->assertNotEquals($first_determiner_id, $requisition->current_determiner()->id);

    }
}






