<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOvertimeIdToAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Thêm cột overtime_id nullable (trong trường hợp không phải attendance nào cũng có overtime)
            $table->unsignedBigInteger('overtime_id')->nullable()->after('shift_id');

            // Thêm ràng buộc khóa ngoại
            $table->foreign('overtime_id')->references('id')->on('overtime_shifts')->onDelete('set null');

            $table->string('face_image')->nullable()->after('overtime_id');

        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['overtime_id']);
            $table->dropColumn('overtime_id');
        });
    }
}
