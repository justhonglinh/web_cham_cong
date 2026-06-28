<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DemoDatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN'); // Sử dụng locale Việt Nam

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate các bảng
        DB::table('leave_requests')->truncate();
        DB::table('overtime_requests')->truncate();
        DB::table('attendances')->truncate();
        DB::table('work_summary')->truncate();
        DB::table('user_details')->truncate();
        DB::table('users')->truncate();
        DB::table('shifts')->truncate();
        DB::table('overtime_shifts')->truncate();
        DB::table('locations')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo manager
        $managerId = DB::table('users')->insertGetId([
            'name' => "Nguyễn Văn Quản Lý",
            'email' => 'manager@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('manager@gmail.com'),
            'role' => 'manager',
            'manager' => null,
            'avatar' => $faker->imageUrl(100, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo 5 ca làm việc thực tế
        $shifts = [];
        $shiftData = [
            ['name' => 'Ca Sáng', 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
            ['name' => 'Ca Chiều', 'start_time' => '13:00:00', 'end_time' => '17:00:00'],
            ['name' => 'Ca Tối', 'start_time' => '18:00:00', 'end_time' => '22:00:00'],
            ['name' => 'Ca Đêm', 'start_time' => '22:00:00', 'end_time' => '06:00:00'],
            ['name' => 'Ca Linh Hoạt', 'start_time' => '09:00:00', 'end_time' => '18:00:00'],
        ];

        foreach ($shiftData as $shift) {
            $shifts[] = DB::table('shifts')->insertGetId([
                'name' => $shift['name'],
                'start_time' => $shift['start_time'],
                'end_time' => $shift['end_time'],
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => $managerId,
            ]);
        }

        // Tạo 5 ca tăng ca thực tế
        $overtimeShifts = [];
        $overtimeData = [
            ['name' => 'Tăng Ca Sáng', 'start_time' => '06:00:00', 'end_time' => '08:00:00', 'max_registrations' => 10],
            ['name' => 'Tăng Ca Chiều', 'start_time' => '17:00:00', 'end_time' => '19:00:00', 'max_registrations' => 8],
            ['name' => 'Tăng Ca Tối', 'start_time' => '22:00:00', 'end_time' => '00:00:00', 'max_registrations' => 5],
            ['name' => 'Tăng Ca Cuối Tuần', 'start_time' => '08:00:00', 'end_time' => '17:00:00', 'max_registrations' => 15],
            ['name' => 'Tăng Ca Khẩn Cấp', 'start_time' => '20:00:00', 'end_time' => '23:00:00', 'max_registrations' => 12],
        ];

        foreach ($overtimeData as $overtime) {
            $overtimeShifts[] = DB::table('overtime_shifts')->insertGetId([
                'user_id' => $managerId,
                'name' => $overtime['name'],
                'start_time' => $overtime['start_time'],
                'end_time' => $overtime['end_time'],
                'description' => $faker->sentence(),
                'max_registrations' => $overtime['max_registrations'],
                'current_registrations' => 0,
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo 20 nhân viên với tên Việt Nam
        $vietnameseNames = [
            'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Văn Cường', 'Phạm Thị Dung',
            'Hoàng Văn Em', 'Vũ Thị Phương', 'Đặng Văn Giang', 'Bùi Thị Hoa',
            'Ngô Văn Hùng', 'Đỗ Thị Lan', 'Lý Văn Minh', 'Hồ Thị Nga',
            'Võ Văn Phúc', 'Dương Thị Quỳnh', 'Tạ Văn Sơn', 'Lưu Thị Thảo',
            'Đinh Văn Tuấn', 'Mai Thị Uyên', 'Châu Văn Vinh', 'Hà Thị Xuân'
        ];

        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $users[] = DB::table('users')->insertGetId([
                'name' => $vietnameseNames[$i],
                'email' => strtolower(str_replace(' ', '.', $vietnameseNames[$i])) . '@company.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'role' => 'employee',
                'manager' => $managerId,
                'avatar' => $faker->imageUrl(100, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo dữ liệu chi tiết cho từng user
        foreach ($users as $userId) {
            DB::table('user_details')->insert([
                'user_id' => $userId,
                'phone' => $faker->numerify('0#########'),
                'address' => $faker->address(),
                'birthday' => $faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
                'emergency_contact' => $faker->numerify('0#########'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tạo work summary cho tháng hiện tại
            DB::table('work_summary')->insert([
                'user_id' => $userId,
                'month' => now()->month,
                'year' => now()->year,
                'total_work_hours' => rand(120, 180),
                'total_overtime_hours' => rand(0, 30),
                'total_leave_days' => rand(0, 3),
                'total_late_days' => rand(0, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo lịch chấm công trong 1 tuần (7 ngày gần đây)
        $statuses = ['present', 'late', 'absent', 'leave', 'early_leave'];
        $statusWeights = [70, 15, 5, 8, 2]; // Tỷ lệ phần trăm cho mỗi trạng thái

        for ($day = 6; $day >= 0; $day--) {
            $currentDate = now()->subDays($day);
            $dayOfWeek = $currentDate->format('l');
            
            // Bỏ qua chủ nhật (ít người làm việc)
            if ($dayOfWeek === 'Sunday') {
                continue;
            }

            foreach ($users as $userId) {
                // Chấm công ca làm việc thường
                $shiftId = $shifts[array_rand($shifts)];
                $status = $this->getRandomStatus($statuses, $statusWeights);
                
                // Tạo thời gian check-in/out thực tế
                $shift = DB::table('shifts')->where('id', $shiftId)->first();
                $checkInTime = $this->generateCheckInTime($shift->start_time, $status);
                $checkOutTime = $this->generateCheckOutTime($shift->end_time, $status);

                DB::table('attendances')->insert([
                    'user_id' => $userId,
                    'date' => $currentDate->format('Y-m-d'),
                    'shift_id' => $shiftId,
                    'overtime_id' => null,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => $checkOutTime,
                    'status' => $status,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);

                // 30% khả năng có tăng ca
                if (rand(1, 100) <= 30) {
                    $overtimeId = $overtimeShifts[array_rand($overtimeShifts)];
                    $overtimeStatus = $this->getRandomStatus(['present', 'late'], [80, 20]);
                    
                    $overtimeShift = DB::table('overtime_shifts')->where('id', $overtimeId)->first();
                    $overtimeCheckIn = $this->generateCheckInTime($overtimeShift->start_time, $overtimeStatus);
                    $overtimeCheckOut = $this->generateCheckOutTime($overtimeShift->end_time, $overtimeStatus);

                    DB::table('attendances')->insert([
                        'user_id' => $userId,
                        'date' => $currentDate->format('Y-m-d'),
                        'shift_id' => null,
                        'overtime_id' => $overtimeId,
                        'check_in_time' => $overtimeCheckIn,
                        'check_out_time' => $overtimeCheckOut,
                        'status' => $overtimeStatus,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
            }
        }

        // Tạo yêu cầu tăng ca
        foreach ($users as $userId) {
            // Mỗi nhân viên có 1-3 yêu cầu tăng ca
            $numRequests = rand(1, 3);
            for ($i = 0; $i < $numRequests; $i++) {
                $overtimeShiftId = $overtimeShifts[array_rand($overtimeShifts)];
                $statuses = ['pending', 'approved', 'rejected'];
                $statusWeights = [40, 40, 20]; // 40% pending, 40% approved, 20% rejected
                $status = $this->getRandomStatus($statuses, $statusWeights);

                DB::table('overtime_requests')->insert([
                    'user_id' => $userId,
                    'overtime_shift_id' => $overtimeShiftId,
                    'status' => $status,
                    'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-30 days', 'now'),
                ]);

                // Nếu approved, tăng current_registrations
                if ($status === 'approved') {
                    DB::table('overtime_shifts')
                        ->where('id', $overtimeShiftId)
                        ->increment('current_registrations');
                }
            }
        }

        // Tạo yêu cầu nghỉ phép
        foreach ($users as $userId) {
            // Mỗi nhân viên có 0-2 yêu cầu nghỉ phép
            $numLeaveRequests = rand(0, 2);
            for ($i = 0; $i < $numLeaveRequests; $i++) {
                $requestTypes = ['late', 'leave', 'early_leave'];
                $requestType = $requestTypes[array_rand($requestTypes)];
                $statuses = ['pending', 'approved', 'rejected'];
                $statusWeights = [30, 50, 20]; // 30% pending, 50% approved, 20% rejected
                $status = $this->getRandomStatus($statuses, $statusWeights);

                // Tạo ngày yêu cầu trong tương lai hoặc quá khứ gần
                $requestDate = $faker->dateTimeBetween('-15 days', '+15 days');
                $startTime = $faker->time('H:i:s');
                $endTime = $faker->time('H:i:s');

                // Đảm bảo end_time > start_time
                if ($endTime <= $startTime) {
                    $endTime = date('H:i:s', strtotime($startTime) + 3600); // Thêm 1 giờ
                }

                DB::table('leave_requests')->insert([
                    'user_id' => $userId,
                    'type' => $requestType,
                    'request_date' => $requestDate->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'reason' => $faker->sentence(),
                    'status' => $status,
                    'manager_notes' => $status !== 'pending' ? $faker->sentence() : null,
                    'approved_at' => $status === 'approved' ? $faker->dateTimeBetween('-10 days', 'now') : null,
                    'rejected_at' => $status === 'rejected' ? $faker->dateTimeBetween('-10 days', 'now') : null,
                    'approver_id' => $status !== 'pending' ? $managerId : null,
                    'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-30 days', 'now'),
                ]);
            }
        }

        // Tạo dữ liệu location
        $locations = [
            ['name' => 'Văn phòng chính', 'address' => '123 Đường ABC, Quận 1, TP.HCM', 'latitude' => 10.7769, 'longitude' => 106.7009, 'radius' => 100, 'is_active' => 1],
            ['name' => 'Chi nhánh 1', 'address' => '456 Đường XYZ, Quận 3, TP.HCM', 'latitude' => 10.7829, 'longitude' => 106.6889, 'radius' => 150, 'is_active' => 1],
            ['name' => 'Chi nhánh 2', 'address' => '789 Đường DEF, Quận 7, TP.HCM', 'latitude' => 10.7329, 'longitude' => 106.7229, 'radius' => 200, 'is_active' => 0],
        ];

        foreach ($locations as $location) {
            DB::table('locations')->insert([
                'user_id' => $managerId,
                'name' => $location['name'],
                'address' => $location['address'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'radius' => $location['radius'],
                'is_active' => $location['is_active'],
                'description' => $faker->sentence(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "Demo data seeded successfully!\n";
        echo "Manager account: manager@gmail.com / manager@gmail.com\n";
        echo "Employee accounts: [name]@company.com / password\n";
    }

    private function getRandomStatus($statuses, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $currentWeight = 0;
        
        foreach ($statuses as $index => $status) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $status;
            }
        }
        
        return $statuses[0]; // Fallback
    }

    private function generateCheckInTime($shiftStartTime, $status)
    {
        $shiftTime = Carbon::createFromFormat('H:i:s', $shiftStartTime);
        
        switch ($status) {
            case 'present':
                // Đến sớm hoặc đúng giờ (-15 phút đến +5 phút)
                return $shiftTime->copy()->addMinutes(rand(-15, 5))->format('H:i:s');
            case 'late':
                // Đến muộn (+5 phút đến +30 phút)
                return $shiftTime->copy()->addMinutes(rand(5, 30))->format('H:i:s');
            case 'absent':
                return null;
            default:
                return $shiftTime->format('H:i:s');
        }
    }

    private function generateCheckOutTime($shiftEndTime, $status)
    {
        $shiftTime = Carbon::createFromFormat('H:i:s', $shiftEndTime);
        
        switch ($status) {
            case 'present':
                // Về đúng giờ hoặc muộn một chút (-5 phút đến +15 phút)
                return $shiftTime->copy()->addMinutes(rand(-5, 15))->format('H:i:s');
            case 'early_leave':
                // Về sớm (-30 phút đến -5 phút)
                return $shiftTime->copy()->addMinutes(rand(-30, -5))->format('H:i:s');
            case 'absent':
                return null;
            default:
                return $shiftTime->format('H:i:s');
        }
    }
}
