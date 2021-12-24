<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('department');
            $table->string('level');
            // $table->string('position');
            $table->text('en_title');
            $table->text('fa_title');
            $table->integer('position_count');
            $table->string('location');
            $table->string('direct_manager_name');
            $table->string('direct_manager_position');
            $table->string('venture');
            $table->string('poirot')->nullable();
            $table->string('shift')->nullable();
            $table->boolean('is_full_time')->default(0);
            $table->boolean('is_new')->default(0);
            $table->string('replacement')->nullable();
            $table->string('field_of_study');
            $table->string('degree');
            $table->integer('experience_year');
            $table->text('mission');
            $table->json('competency');
            $table->text('outcome');
            $table->text('comment')->nullable();
            $table->json('interviewers')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('determiner_id')->nullable();
            $table->foreign('determiner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
}
