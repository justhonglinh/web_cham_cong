<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật dữ liệu overtime shifts từ datetime sang time format
        $overtimeShifts = DB::table('overtime_shifts')->get();
        
        foreach ($overtimeShifts as $shift) {
            $startTime = Carbon::parse($shift->start_time)->format('H:i:s');
            $endTime = Carbon::parse($shift->end_time)->format('H:i:s');
            
            DB::table('overtime_shifts')
                ->where('id', $shift->id)
                ->update([
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì đây là cập nhật dữ liệu
    }
};
