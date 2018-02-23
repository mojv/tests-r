<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCornerColumnToFormcorrdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     public function up()
     {
       Schema::table('formcoords', function (Blueprint $table) {
             $table->integer('corner')->nullable()->default(0);
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
       Schema::table('formcoords', function($table)
       {
           $table->dropColumn('corner');
       });
     }
}
