<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcceptedColumnToRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->addColumn('tinyInteger','accepted')->default(0)->after('status') ;
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisition', function (Blueprint $table) {
            $table->removeColumn('accepted') ;
        });
    }
}
