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
    Schema::table('voters', function (Blueprint $table) {
        // Add separate columns for last_name, first_name, and middle_name
        $table->string('last_name')->nullable();
        $table->string('first_name')->nullable();
        $table->string('middle_name')->nullable();

        // Remove the old name column (if exists)
        $table->dropColumn('name');
    });
}

public function down()
{
    Schema::table('voters', function (Blueprint $table) {
        // Revert the changes if rolling back
        $table->string('name')->nullable();
        $table->dropColumn(['last_name', 'first_name', 'middle_name']);
    });
}

};
