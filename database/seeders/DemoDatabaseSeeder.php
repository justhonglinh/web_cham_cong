<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DemoDatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate các bảng
        DB::table('overtime_requests')->truncate();
        DB::table('attendances')->truncate();
        DB::table('work_summary')->truncate();
        DB::table('user_details')->truncate();
        DB::table('users')->truncate();
        DB::table('shifts')->truncate();
        DB::table('overtime_shifts')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // Tạo 5 shifts
        $shifts = [];
        for ($i=1; $i<=5; $i++) {
            $shifts[] = DB::table('shifts')->insertGetId([
                'name' => "Shift $i",
                'start_time' => $faker->time(),
                'end_time' => $faker->time(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo 5 overtime shifts
        $overtimeShifts = [];
        for ($i=1; $i<=5; $i++) {
            $overtimeShifts[] = DB::table('overtime_shifts')->insertGetId([
                'name' => "Overtime Shift $i",
                'start_time' => $faker->time(),
                'end_time' => $faker->time(),
                'description' => $faker->sentence(),
                'max_registrations' => 5,
                'current_registrations' => 0,
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->insertGetId([
            'name' => "manager",
            'email' => 'manager@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('manager@gmail.com'),
            'role' => 'manager',
            'manager' => null,
            'avatar' => $faker->imageUrl(100, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo 15 users
        $users = [];
        for ($i=1; $i<=15; $i++) {
            $users[] = DB::table('users')->insertGetId([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'role' => 'employee',
                'manager' => '1',
                'avatar' => $faker->imageUrl(100, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        };

        DB::table('users')->insertGetId([
            'name' => "linh",
            'email' => 'linh@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('linh@gmail.com'),
            'role' => 'employee',
            'manager' => '1',
            'avatar' => $faker->imageUrl(100, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo dữ liệu chi tiết cho từng user
        foreach ($users as $userId) {
            DB::table('user_details')->insert([
                'user_id' => $userId,
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'birthday' => $faker->date(),
                'emergency_contact' => $faker->phoneNumber(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('work_summary')->insert([
                'user_id' => $userId,
                'month' => rand(1,12),
                'year' => rand(2020, 2024),
                'total_work_hours' => rand(120, 160),
                'total_overtime_hours' => rand(0, 20),
                'total_leave_days' => rand(0, 5),
                'total_late_days' => rand(0, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tạo 5 attendances mỗi user
            for ($j=1; $j<=5; $j++) {
                DB::table('attendances')->insert([
                    'user_id' => $userId,
                    'date' => $faker->date(),
                    'shift_id' => $shifts[array_rand($shifts)],
                    'overtime_id' => null,
                    'check_in_time' => now(),
                    'check_out_time' => now(),
                    'status' => 'present',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('attendances')->insert([
                    'user_id' => $userId,
                    'date' => $faker->date(),
                    'shift_id' => null,
                    'overtime_id' => $overtimeShifts[array_rand($overtimeShifts)],
                    'check_in_time' => now(),
                    'check_out_time' => now(),
                    'status' => 'present',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Tạo 2 overtime requests mỗi user
            for ($k=1; $k<=2; $k++) {
                DB::table('overtime_requests')->insert([
                    'user_id' => $userId,
                    'overtime_shift_id' => $overtimeShifts[array_rand($overtimeShifts)],
                    'status' => 'pending',
                    'created_at' => now(),
                    'approved_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}
