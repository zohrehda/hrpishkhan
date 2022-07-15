<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCatIdColumnToDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drafts', function (Blueprint $table) {

            $table->addColumn('bigInteger','cat_id')->unsigned()->nullable()->default(null)  ;
          //  $table->json('draft')->nullable()->change();
            $table->foreign('cat_id','drafts_category_id_foreign')->on('draft_categories')->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drafts', function (Blueprint $table) {
           $table->dropForeign('drafts_category_id_foreign') ;
            $table->dropColumn('cat_id') ;

            //
        });
    }
}
