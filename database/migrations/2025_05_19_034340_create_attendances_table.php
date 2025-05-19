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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // id (PK)
            $table->unsignedBigInteger('user_id'); // FK tới users
            $table->date('date'); // ngày chấm công
            $table->unsignedBigInteger('shift_id'); // FK tới shifts
            $table->dateTime('check_in_time')->nullable(); // giờ vào
            $table->dateTime('check_out_time')->nullable(); // giờ ra
            $table->enum('status', ['present', 'half-day', 'leave', 'absent', 'late', 'early_leave'])->nullable(); // KHÔNG dùng ->change()
            $table->timestamps(); // created_at, updated_at

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
