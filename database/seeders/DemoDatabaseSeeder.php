<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt khóa ngoại để truncate bảng
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('attendances')->truncate();
        DB::table('work_summary')->truncate();
        DB::table('overtime_requests')->truncate();
        DB::table('overtime_shifts')->truncate();
        DB::table('shifts')->truncate();
        DB::table('users')->truncate();

        // Bật lại khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo 5 nhân viên
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => "Nhân viên $i",
                'email' => "employee$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'employee',
                'manager' => 'Manager',
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->insert([
            'name' => "Manager",
            'email' => "manager@gmail.com",
            'password' => Hash::make('manager@gmail.com'),
            'role' => 'manager',
            'manager' => null,
            'avatar' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Lấy danh sách user ids
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Tạo 2 ca làm
        $shiftIds = [];
        $shiftIds[] = DB::table('shifts')->insertGetId([
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'name'=> 'A',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $shiftIds[] = DB::table('shifts')->insertGetId([
            'start_time' => '13:00:00',
            'name'=> 'A',
            'end_time' => '17:00:00',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Chấm công 3 ngày gần nhất với các trạng thái
        $statuses = ['present', 'leave', 'absent', 'late', 'early_leave'];

        foreach ($userIds as $userId) {
            foreach (range(0, 2) as $d) {
                $date = Carbon::today()->subDays($d);
                $status = $statuses[array_rand($statuses)];

                if ($status === 'absent' || $status === 'leave') {
                    $checkIn = null;
                    $checkOut = null;
                } elseif ($status === 'half-day') {
                    $checkIn = $date->copy()->setTime(8, 0);
                    $checkOut = $date->copy()->setTime(12, 0);
                } elseif ($status === 'late') {
                    $checkIn = $date->copy()->setTime(9, 0);
                    $checkOut = $date->copy()->setTime(17, 0);
                } elseif ($status === 'early_leave') {
                    $checkIn = $date->copy()->setTime(8, 0);
                    $checkOut = $date->copy()->setTime(15, 0);
                } else {
                    $checkIn = $date->copy()->setTime(8, 0);
                    $checkOut = $date->copy()->setTime(17, 0);
                }

                DB::table('attendances')->insert([
                    'user_id' => $userId,
                    'shift_id' => $shiftIds[array_rand($shiftIds)],
                    'date' => $date->format('Y-m-d'),
                    'check_in_time' => $checkIn ? $checkIn->format('Y-m-d H:i:s') : null,
                    'check_out_time' => $checkOut ? $checkOut->format('Y-m-d H:i:s') : null,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Tổng hợp giờ làm trong tháng
        foreach ($userIds as $userId) {
            DB::table('work_summary')->insert([
                'user_id' => $userId,
                'month' => now()->month,
                'year' => now()->year,
                'total_work_hours' => rand(80, 120),
                'total_overtime_hours' => rand(5, 15),
                'total_leave_days' => rand(0, 2),
                'total_absent_days' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo ca làm thêm
        $otShiftId = DB::table('overtime_shifts')->insertGetId([
            'name' => 'OT buổi tối',
            'start_time' => '19:00:00',
            'end_time' => '22:00:00',
            'description' => 'Tăng ca dự án cuối tuần',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo yêu cầu làm thêm cho mỗi nhân viên
        foreach ($userIds as $userId) {
            DB::table('overtime_requests')->insert([
                'user_id' => $userId,
                'overtime_shift_id' => $otShiftId,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
                'approved_at' => null,
            ]);
        }
    }
}
