<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormcoordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formcoords', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('form_id')->unsigned()->index(); 
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->string('field_name');
            $table->double('x',15,8);
            $table->double('y',15,8);
            $table->double('w',15,8)->nullable();
            $table->double('h',15,8)->nullable();
            $table->double('r',15,8)->nullable();
            $table->integer('shape')->unsigned();
            $table->string('fill')->nullable();
            $table->string('multiMark')->nullable();
            $table->integer('q_id')->unsigned()->nullable();
            $table->string('q_option')->nullable();            
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
        Schema::dropIfExists('formcoords');
    }
}
