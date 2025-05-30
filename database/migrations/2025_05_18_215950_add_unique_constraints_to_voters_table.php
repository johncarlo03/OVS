<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  /*  public function up()
{
    Schema::table('voters', function (Blueprint $table) {
        $table->unique('student_id');
        $table->unique('rfid');
    });
}

    /**
     * Reverse the migrations.
     */
  /*  public function down()
{
    Schema::table('voters', function (Blueprint $table) {
        $table->dropUnique(['student_id']);
        $table->dropUnique(['rfid']);
    });
} */
};
