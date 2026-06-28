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
            $table->unsignedBigInteger('manager')->nullable(); // manager id
            $table->string('avatar')->nullable(); // Thêm cột avatar lưu ảnh, cho phép null

            $table->foreign('manager')->references('id')->on('users')->onDelete('set null');
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
