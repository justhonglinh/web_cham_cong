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
        Schema::table('overtime_shifts', function (Blueprint $table) {
            // Thêm cột 'user_id' vào bảng overtime_shifts
            $table->unsignedBigInteger('user_id')->after('id'); // Bạn có thể thay 'after' tùy chỉnh vị trí cột

            // Tạo khóa ngoại cho cột 'user_id' tham chiếu đến cột 'id' trong bảng 'users'
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('overtime_shifts', function (Blueprint $table) {
            // Xóa khóa ngoại
            $table->dropForeign(['user_id']);

            // Xóa cột 'user_id'
            $table->dropColumn('user_id');
        });
    }
};
