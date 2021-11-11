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
            $table->integer('department');
            $table->string('position');
            $table->text('en_title');
            $table->text('fa_title');
            $table->integer('position_count');
            $table->string('location');
            $table->string('direct_manger');
            $table->string('venture');
            $table->string('vertical')->nullable();
            $table->string('seniority');
            $table->integer('shift')->nullable();
            $table->boolean('is_full_time')->default(0);
            $table->boolean('is_new')->default(0);
            $table->string('replacement')->nullable();
            $table->string('field_of_study');
            $table->integer('degree');
            $table->integer('experience_year');
            $table->text('mission');
            $table->json('competency');
            $table->text('outcome');
            $table->text('about')->nullable();
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
            $table->integer('status')->default(0);
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
