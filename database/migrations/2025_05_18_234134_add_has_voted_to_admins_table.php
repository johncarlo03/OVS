<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('admins', function (Blueprint $table) {
        $table->boolean('has_voted')->default(false); // or ->default(0)
    });
}

public function down()
{
    Schema::table('admins', function (Blueprint $table) {
        $table->dropColumn('has_voted');
    });
}

};
