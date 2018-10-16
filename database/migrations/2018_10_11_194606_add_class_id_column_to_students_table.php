<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassIdColumnToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('students', function($table)
      {
          $table->integer('class_id')->unsigned()->index();
          $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade')->onUpdate('cascade');
          $table->unique( array('class_id','student_id'));
          $table->dropUnique('students_user_id_student_id_unique');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('students', function($table)
      {
        $table->unique( array('user_id','student_id'));
        $table->dropForeign('students_class_id_foreign');
        $table->dropUnique('students_class_id_student_id_unique');
        $table->dropColumn('class_id');
      });
    }
}
