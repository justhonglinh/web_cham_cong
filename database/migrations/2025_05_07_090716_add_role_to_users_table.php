<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột 'role' với giá trị chỉ có thể là 'manager' hoặc 'employee'
            $table->enum('role', ['manager', 'employee'])->default('employee'); // Mặc định là 'employee'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa cột 'role' khi rollback migration
            $table->dropColumn('role');
        });
    }
}
