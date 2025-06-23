<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Facades\Redirect;
use App\Models\OvertimeShift;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    public function show(Request $request)
    {
        $managerId = Auth::user()->id;
        $today = now()->toDateString();

        // Lấy danh sách nhân viên dưới quyền manager
        $users = User::where('role', 'employee')
            ->where('manager', $managerId)
            ->get();

        $employeeIds = $users->pluck('id');

        foreach ($employeeIds as $employeeId) {
            // Kiểm tra Attendance ngày hôm nay của từng nhân viên
            $attendance = Attendance::where('user_id', $employeeId)
                ->where('date', $today)
                ->where('shift_id', '!=', null)
                ->first();

            if (empty($attendance)) {
                // Nếu chưa có bản ghi Attendance ngày hôm nay thì insert mới
                Attendance::create([
                    'user_id' => $employeeId,
                    'date' => $today,
                    'shift_id' => '1',
                    'check_in_time' => null,
                    'check_out_time' => null,
                    'status' => 'absent', // trạng thái mặc định
                    'created_at' => now(),
                ]);
            }
        }

        // Lấy lại danh sách Attendance sau khi đảm bảo đã có bản ghi ngày hôm nay
        $attendance_shifts = Attendance::with('shift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->whereNull('overtime_id')
            ->where('date', $today)
            ->orderBy('created_at', 'desc')
            ->get();

         $attendance_overtimes = Attendance::with('overtimeShift', 'user')
            ->whereIn('user_id', $employeeIds)
            ->whereNull('shift_id')
            ->where('date', '>=', $today)
            ->orderBy('created_at', 'desc')
            ->get();


        $shifts = Shift::where('user_id', $managerId)->get();
        $overtimes = OvertimeShift::where('user_id', $managerId)->get();
        return view('attendance_management', compact('attendance_shifts','attendance_overtimes', 'shifts','overtimes'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        // Nếu là ca làm việc thường
        if ($request->filled('shift_id')) {
            $attendance->shift_id = $request->shift_id;
            $attendance->overtime_id = null;
        }
        // Nếu là tăng ca
        if ($request->filled('overtime_shift_id')) {
            $attendance->overtime_id = $request->overtime_shift_id;
            $attendance->shift_id = null;
        }
        // Các trường chung
        if ($request->filled('date')) {
            $attendance->date = $request->date;
        }
        if ($request->filled('check_in_time')) {
            $attendance->check_in_time = $request->check_in_time;
        }
        if ($request->filled('check_out_time')) {
            $attendance->check_out_time = $request->check_out_time;
        }
        if ($request->filled('status')) {
            $attendance->status = $request->status;
        }
        $attendance->save();
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('attendance.index')->with('success', 'Cập nhật thành công');
    }

    public function history()
    {
        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)
            ->orderByDesc('date')
            ->where('date', '>=', now()->startOfMonth())
            ->get();

        return view('employees.attendance-history', compact('attendances'));
    }

    public function index(Request $request)
    {
        $today = now()->toDateString();
        $table = $request->get('table', 'attendance');
        
        $query = Attendance::with(['user', 'shift'])
            ->whereNull('overtime_id') // Chỉ lấy attendance thông thường
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        $overtimeQuery = Attendance::with(['user', 'overtimeShift'])
            ->whereNotNull('overtime_id') // Chỉ lấy attendance overtime
            ->where('date', '>', $today) // Chỉ lấy các ngày trong tương lai
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // Xử lý tìm kiếm cho bảng ca làm việc (chỉ khi table = attendance)
        if ($table === 'attendance' && $request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Xử lý tìm kiếm cho bảng tăng ca (chỉ khi table = overtime)
        if ($table === 'overtime' && $request->filled('search_overtime')) {
            $search = $request->search_overtime;
            $overtimeQuery->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Lọc theo ngày (chỉ áp dụng cho bảng đang được xem)
        if ($request->filled('date')) {
            if ($table === 'attendance') {
                $query->whereDate('date', $request->date);
            } elseif ($table === 'overtime') {
                $overtimeQuery->whereDate('date', $request->date);
            }
        }

        // Lọc theo ca làm việc (chỉ cho bảng attendance)
        if ($table === 'attendance' && $request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        // Lọc theo ca tăng ca (chỉ cho bảng overtime)
        if ($table === 'overtime' && $request->filled('overtime_shift_id')) {
            $overtimeQuery->where('overtime_id', $request->overtime_shift_id);
        }

        // Lọc theo trạng thái (chỉ áp dụng cho bảng đang được xem)
        if ($request->filled('status')) {
            if ($table === 'attendance') {
                $query->where('status', $request->status);
            } elseif ($table === 'overtime') {
                $overtimeQuery->where('status', $request->status);
            }
        }

        $attendance = $query->paginate(10);
        $attendance_overtimes = $overtimeQuery->paginate(10);

        // Thêm tham số table và các tham số tìm kiếm vào URL phân trang
        if ($table === 'attendance') {
            $attendance->appends($request->only(['table', 'search', 'date', 'shift_id', 'status']));
            $attendance_overtimes->appends(['table' => 'overtime']);
        } elseif ($table === 'overtime') {
            $attendance_overtimes->appends($request->only(['table', 'search_overtime', 'date', 'overtime_shift_id', 'status']));
            $attendance->appends(['table' => 'attendance']);
        }

        $shifts = Shift::all();
        $overtimes = OvertimeShift::all();

        return view('attendance_management', compact('attendance', 'attendance_overtimes', 'shifts', 'overtimes'));
    }

    /**
     * Hiển thị form chấm công với camera và thông tin ca làm việc hiện tại
     */
    public function showAttendanceForm()
    {
        $user = Auth::user();
        $today = now()->toDateString();
        $currentTime = now();

        // Khởi tạo mặc định để tránh lỗi undefined variable
        $canCheckIn = false;
        $canCheckOut = false;

        // Tìm tất cả ca làm việc của user hôm nay
        $todayAttendances = Attendance::with(['shift', 'overtimeShift'])
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->get();

        // Xác định trạng thái ca làm việc hiện tại
        $shiftStatus = 'no_shift';
        $shiftInfo = null;
        $todayAttendance = null;

        if ($todayAttendances->count() > 0) {
            // Tìm ca làm việc đang hoạt động hoặc sắp tới
            $activeShift = null;
            $upcomingShift = null;
            $endedShift = null;
            $checkedInShift = null;

            foreach ($todayAttendances as $attendance) {
                $currentShift = null;
                $shiftType = '';
                
                if ($attendance->shift_id) {
                    $currentShift = $attendance->shift;
                    $shiftType = 'shift';
                } elseif ($attendance->overtime_id) {
                    $currentShift = $attendance->overtimeShift;
                    $shiftType = 'overtime';
                }

        if ($currentShift) {
                    // Ưu tiên ca đã check-in nhưng chưa check-out
                    if ($attendance->check_in_time && !$attendance->check_out_time) {
                        if (!$checkedInShift) {
                            $checkedInShift = [
                                'attendance' => $attendance,
                                'shift' => $currentShift,
                                'type' => $shiftType
                            ];
                        }
                        continue; // Bỏ qua logic khác nếu đã tìm thấy ca check-in
                    }
                    
                    // Xử lý thời gian khác nhau cho shift và overtime
                    if ($shiftType === 'shift') {
                        // Shift chỉ có giờ phút, cần thêm ngày hôm nay
                        $shiftStartTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->start_time);
                        $shiftEndTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->end_time);
                    } else {
                        // Overtime có cả ngày tháng
            $shiftStartTime = \Carbon\Carbon::parse($currentShift->start_time);
            $shiftEndTime = \Carbon\Carbon::parse($currentShift->end_time);
                    }
                    
            $currentTimeOnly = $currentTime->format('H:i:s');
            
                    // Kiểm tra trạng thái ca làm việc
            if ($currentTimeOnly >= $shiftStartTime->format('H:i:s') && $currentTimeOnly <= $shiftEndTime->format('H:i:s')) {
                        // Ca đang hoạt động
                        if (!$activeShift || $shiftStartTime < \Carbon\Carbon::parse($activeShift['shift']->start_time)) {
                            $activeShift = [
                                'attendance' => $attendance,
                                'shift' => $currentShift,
                                'type' => $shiftType
                            ];
                        }
                    } elseif ($currentTimeOnly < $shiftStartTime->format('H:i:s')) {
                        // Kiểm tra xem có thể chấm công sớm không (trước 15 phút)
                        $earlyCheckInTime = $shiftStartTime->copy()->subMinutes(15);
                        if ($currentTimeOnly >= $earlyCheckInTime->format('H:i:s')) {
                            // Có thể chấm công sớm
                            if (!$activeShift || $shiftStartTime < \Carbon\Carbon::parse($activeShift['shift']->start_time)) {
                                $activeShift = [
                                    'attendance' => $attendance,
                                    'shift' => $currentShift,
                                    'type' => $shiftType,
                                    'is_early_checkin' => true
                                ];
                            }
                        } else {
                            // Ca sắp tới (chưa đến thời gian chấm công sớm)
                            if (!$upcomingShift || $shiftStartTime < \Carbon\Carbon::parse($upcomingShift['shift']->start_time)) {
                                $upcomingShift = [
                                    'attendance' => $attendance,
                                    'shift' => $currentShift,
                                    'type' => $shiftType
                                ];
                            }
                        }
                    } else {
                        // Ca đã kết thúc
                        if (!$endedShift || $shiftEndTime > \Carbon\Carbon::parse($endedShift['shift']->end_time)) {
                            $endedShift = [
                                'attendance' => $attendance,
                                'shift' => $currentShift,
                                'type' => $shiftType
                            ];
                        }
                    }
                }
            }

            // Ưu tiên hiển thị ca đã check-in trước
            if ($checkedInShift) {
                $todayAttendance = $checkedInShift['attendance'];
                $currentShift = $checkedInShift['shift'];
                $shiftType = $checkedInShift['type'];
                
                // Xử lý thời gian hiển thị
                if ($shiftType === 'shift') {
                    $shiftStartTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->end_time);
                } else {
                    $shiftStartTime = \Carbon\Carbon::parse($currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($currentShift->end_time);
                }
                
                $currentTimeOnly = $currentTime->format('H:i:s');
                
                $shiftStatus = 'active';
                $canCheckIn = false;
                $canCheckOut = true;
                
                $shiftInfo = [
                    'name' => $currentShift->name,
                    'start_time' => $shiftStartTime->format('H:i'),
                    'end_time' => $shiftEndTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i'),
                    'is_late' => $currentTimeOnly > $shiftStartTime->addMinutes(15)->format('H:i:s'),
                    'is_early_checkin' => false,
                    'type' => $shiftType,
                    'check_in_time' => $todayAttendance->check_in_time,
                    'check_out_time' => $todayAttendance->check_out_time
                ];
            } elseif ($activeShift) {
                $todayAttendance = $activeShift['attendance'];
                $currentShift = $activeShift['shift'];
                $shiftType = $activeShift['type'];
                $isEarlyCheckIn = isset($activeShift['is_early_checkin']) ? $activeShift['is_early_checkin'] : false;
                
                // Xử lý thời gian hiển thị
                if ($shiftType === 'shift') {
                    $shiftStartTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->end_time);
                } else {
                    $shiftStartTime = \Carbon\Carbon::parse($currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($currentShift->end_time);
                }
                
                $currentTimeOnly = $currentTime->format('H:i:s');
                
                $shiftStatus = $isEarlyCheckIn ? 'early_checkin' : 'active';
                
                // Kiểm tra trạng thái chấm công
                $hasCheckedIn = $todayAttendance->check_in_time;
                $hasCheckedOut = $todayAttendance->check_out_time;
                
                if ($hasCheckedIn && !$hasCheckedOut) {
                    // Đã check-in, chưa check-out
                    $canCheckIn = false;
                    $canCheckOut = true;
                } elseif (!$hasCheckedIn) {
                    // Chưa check-in
                    $canCheckIn = true;
                    $canCheckOut = false;
                } else {
                    // Đã check-in và check-out
                    $canCheckIn = false;
                    $canCheckOut = false;
                }
                
                $shiftInfo = [
                    'name' => $currentShift->name,
                    'start_time' => $shiftStartTime->format('H:i'),
                    'end_time' => $shiftEndTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i'),
                    'is_late' => $currentTimeOnly > $shiftStartTime->addMinutes(15)->format('H:i:s'),
                    'is_early_checkin' => $isEarlyCheckIn,
                    'type' => $shiftType,
                    'check_in_time' => $hasCheckedIn ? $todayAttendance->check_in_time : null,
                    'check_out_time' => $hasCheckedOut ? $todayAttendance->check_out_time : null
                ];
            } elseif ($upcomingShift) {
                $todayAttendance = $upcomingShift['attendance'];
                $currentShift = $upcomingShift['shift'];
                $shiftType = $upcomingShift['type'];
                
                // Xử lý thời gian hiển thị
                if ($shiftType === 'shift') {
                    $shiftStartTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->end_time);
                } else {
                    $shiftStartTime = \Carbon\Carbon::parse($currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($currentShift->end_time);
                }
                
                $shiftStatus = 'upcoming';
                $shiftInfo = [
                    'name' => $currentShift->name,
                    'start_time' => $shiftStartTime->format('H:i'),
                    'end_time' => $shiftEndTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i'),
                    'type' => $shiftType
                ];
            } elseif ($endedShift) {
                $todayAttendance = $endedShift['attendance'];
                $currentShift = $endedShift['shift'];
                $shiftType = $endedShift['type'];
                
                // Xử lý thời gian hiển thị
                if ($shiftType === 'shift') {
                    $shiftStartTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($today . ' ' . $currentShift->end_time);
            } else {
                    $shiftStartTime = \Carbon\Carbon::parse($currentShift->start_time);
                    $shiftEndTime = \Carbon\Carbon::parse($currentShift->end_time);
                }
                
                $shiftStatus = 'ended';
                $shiftInfo = [
                    'name' => $currentShift->name,
                    'start_time' => $shiftStartTime->format('H:i'),
                    'end_time' => $shiftEndTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i'),
                    'type' => $shiftType
                ];
            }
        }

        return view('employees.attendance', compact('todayAttendance', 'shiftStatus', 'canCheckIn', 'canCheckOut', 'shiftInfo'));
    }

    /**
     * Xử lý chấm công với camera
     */
    public function processAttendance(Request $request)
    {
        $request->validate([
            'image1' => 'required|string',
            'action' => 'required|in:check_in,check_out',
        ]);

        $user = Auth::user();
        $today = now()->toDateString();
        $currentTime = now();
        $action = $request->input('action');

        // Lấy hoặc tạo bản ghi chấm công hôm nay
        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => $today,
        ]);

        // Xử lý ảnh chụp từ camera
        $capturedUrl = $request->input('image1');
        $image1 = str_replace('data:image/png;base64,', '', $capturedUrl);
        $image1 = str_replace(' ', '+', $image1);
        $imagePath = 'attendance_images/' . $user->id . '_' . $today . '_' . time() . '.png';
        $fullPath = storage_path('app/public/' . $imagePath);
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        file_put_contents($fullPath, base64_decode($image1));

        // Lấy vị trí nếu có
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $distance = $request->input('distance');
        $officeLat = 21.028511;
        $officeLng = 105.804817;
        if ($latitude && $longitude && !$distance) {
            $distance = $this->haversine($latitude, $longitude, $officeLat, $officeLng) * 1000; // m
        }

        // So sánh khuôn mặt với avatar user
        $userAvatarPath = storage_path('app/public/' . $user->avatar);
        if (!file_exists($userAvatarPath)) {
            return redirect()->back()->with('error', 'Không tìm thấy ảnh mẫu (avatar) của bạn.');
        }
        $image1Path = storage_path('app/temp_image1.png');
        file_put_contents($image1Path, base64_decode($image1));
        $image2Path = storage_path('app/temp_avatar.png');
        copy($userAvatarPath, $image2Path);
        $api_key = env('FACEPP_API_KEY');
        $api_secret = env('FACEPP_API_SECRET');
        $url = 'https://api-us.faceplusplus.com/facepp/v3/compare';
        $response = Http::asMultipart()->post($url, [
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'image_file1' => fopen($image1Path, 'r'),
            'image_file2' => fopen($image2Path, 'r'),
        ]);
        @unlink($image1Path);
        @unlink($image2Path);
        $result = $response->json();
        $confidence = $result['confidence'] ?? null;

        
        $threshold = 70;
        if ($confidence === null || $confidence < $threshold) {
            return redirect()->back()->with('error', 'Khuôn mặt không khớp hoặc không nhận diện được. Điểm: ' . ($confidence ?? 'N/A'));
        }

        // Nếu thành công: tạo hoặc update bản ghi
        $attendance->face_image = $imagePath;
        if ($action === 'check_in') {
            $attendance->check_in_time = $currentTime->format('H:i:s');
        } elseif ($action === 'check_out') {
            $attendance->check_out_time = $currentTime->format('H:i:s');
        }
        $attendance->status = 'present';
        $attendance->save();

        return redirect()->back()->with('success', 'Chấm công thành công! Điểm so sánh khuôn mặt: ' . $confidence);
    }

    /**
     * Hàm tính khoảng cách Haversine (km)
     */
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        return $distance;
    }
}
