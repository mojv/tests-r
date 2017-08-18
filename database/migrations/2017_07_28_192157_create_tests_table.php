<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->integer('class_id')->unsigned()->index();
          $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade')->onUpdate('cascade');
          $table->integer('form_id')->nullable()->unsigned()->index();
          $table->foreign('form_id')->references('id')->on('forms')->onDelete('set null')->onUpdate('cascade');
          $table->string('name');
          $table->integer('test_weight')->nullable();
          $table->text('titles')->nullable();
          $table->text('answers')->nullable();
          $table->text('answers_weight')->nullable();
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
        Schema::dropIfExists('tests');
    }
}
