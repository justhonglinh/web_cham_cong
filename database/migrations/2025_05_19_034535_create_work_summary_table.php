<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_summary', function (Blueprint $table) {
            $table->id(); // id (PK)
            $table->unsignedBigInteger('user_id'); // FK
            $table->integer('month');
            $table->integer('year');
            $table->float('total_work_hours')->default(0);
            $table->float('total_overtime_hours')->default(0);
            $table->integer('total_leave_days')->default(0);
            $table->integer('total_absent_days')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_summary');
    }
};
