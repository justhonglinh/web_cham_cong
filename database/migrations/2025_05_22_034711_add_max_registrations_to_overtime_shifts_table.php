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
        Schema::table('overtime_shifts', function (Blueprint $table) {
            $table->unsignedInteger('max_registrations')->default(1)->after('description');
            $table->unsignedInteger('current_registrations')->default(0)->after('max_registrations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_shifts', function (Blueprint $table) {
            //
        });
    }
};
