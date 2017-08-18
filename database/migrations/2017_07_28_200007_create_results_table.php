<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->integer('test_id')->unsigned()->index();
          $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade')->onUpdate('cascade');
          $table->integer('student_id')->unsigned()->index();
          $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
          $table->text('responses')->nullable();
          $table->integer('grade')->nullable();
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
        Schema::dropIfExists('results');
    }
}
