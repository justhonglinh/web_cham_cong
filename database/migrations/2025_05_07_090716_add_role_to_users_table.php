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
            $table->enum('role', ['manager', 'employee'])->default('employee'); // thêm role
            $table->unsignedBigInteger('id_manager')->nullable();  // Thêm cột id_manager
            $table->string('avatar')->nullable(); // Thêm cột avatar lưu ảnh, cho phép null
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
