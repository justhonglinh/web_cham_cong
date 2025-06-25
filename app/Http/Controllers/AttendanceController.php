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


        $shifts = Shift::where('user_id', $managerId)->get();
        if($shifts->count() == 0){
            return redirect()->route('shifts.index')->with('warning', 'Vui lòng tạo ca làm việc trước khi chấm công');
        }
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
        
        // Lưu thông tin cũ để so sánh
        $oldDate = $attendance->date;
        $oldShiftId = $attendance->shift_id;
        $oldOvertimeId = $attendance->overtime_id;
        $oldStatus = $attendance->status;
        
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
        
        // Cập nhật work_summary sau khi thay đổi attendance
        $this->updateWorkSummary($attendance->user_id, $attendance->date);
        
        // Nếu ngày thay đổi, cũng cập nhật work_summary cho ngày cũ
        if ($oldDate != $attendance->date) {
            $this->updateWorkSummary($attendance->user_id, $oldDate);
        }
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('attendance.index')->with('success', 'Cập nhật thành công');
    }

    /**
     * Cập nhật work_summary cho user trong tháng/năm cụ thể
     */
    private function updateWorkSummary($userId, $date)
    {
        $dateObj = \Carbon\Carbon::parse($date);
        $month = $dateObj->month;
        $year = $dateObj->year;
        
        // Tạo hoặc cập nhật work summary
        $workSummary = \App\Models\WorkSummary::firstOrNew([
            'user_id' => $userId,
            'month' => $month,
            'year' => $year,
        ]);
        
        // Lấy tất cả attendance của user trong tháng
        $attendances = \App\Models\Attendance::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
        
        // Tính toán lại các chỉ số
        $totalWorkHours = 0;
        $totalOvertimeHours = 0;
        $totalLeaveDays = 0;
        $totalLateDays = 0;
        
        foreach ($attendances as $att) {
            // Tính giờ làm việc thường
            if ($att->shift_id && $att->shift) {
                $date = is_string($att->date) ? substr($att->date, 0, 10) : $att->date->format('Y-m-d');
                $shiftStart = \Carbon\Carbon::parse($date . ' ' . $att->shift->start_time);
                $shiftEnd = \Carbon\Carbon::parse($date . ' ' . $att->shift->end_time);
                $workMinutes = abs($shiftEnd->diffInMinutes($shiftStart));
                $totalWorkHours += $workMinutes / 60;
            }
            
            // Tính giờ làm thêm
            if ($att->overtime_id && $att->overtimeShift) {
                $shiftStart = \Carbon\Carbon::parse($att->overtimeShift->start_time);
                $shiftEnd = \Carbon\Carbon::parse($att->overtimeShift->end_time);
                $workMinutes = abs($shiftEnd->diffInMinutes($shiftStart));
                $totalOvertimeHours += $workMinutes / 60;
            }
            
            // Đếm ngày nghỉ phép
            if ($att->status === 'leave') {
                $totalLeaveDays++;
            }
            
            // Đếm ngày đi muộn
            if ($att->status === 'late') {
                $totalLateDays++;
            }
        }
        
        // Cập nhật work summary
        $workSummary->total_work_hours = $totalWorkHours;
        $workSummary->total_overtime_hours = $totalOvertimeHours;
        $workSummary->total_leave_days = $totalLeaveDays;
        $workSummary->total_late_days = $totalLateDays;
        $workSummary->save();
        
        return $workSummary;
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
                    $latestCheckIn = $shiftStartTime->copy()->addHour();
                    $currentTimeOnly = $currentTime->format('H:i:s');
                    // Kiểm tra trạng thái ca làm việc
                    if ($currentTimeOnly >= $shiftStartTime->format('H:i:s') && $currentTimeOnly <= $latestCheckIn->format('H:i:s')) {
                        // Cho phép check-in muộn tối đa 1 tiếng
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
        if (strpos($capturedUrl, 'data:image/jpeg') === 0) {
            $image1 = preg_replace('/^data:image\/jpeg;base64,/', '', $capturedUrl);
            $image1Path = storage_path('app/temp_image1.jpg');
        } elseif (strpos($capturedUrl, 'data:image/png') === 0) {
            $image1 = preg_replace('/^data:image\/png;base64,/', '', $capturedUrl);
            $image1Path = storage_path('app/temp_image1.png');
        } else {
            return redirect()->back()->with('error', 'Định dạng ảnh không được hỗ trợ!');
        }
        $image1 = str_replace(' ', '+', $image1);
        file_put_contents($image1Path, base64_decode($image1));
        if (!file_exists($image1Path) || filesize($image1Path) === 0) {
            return redirect()->back()->with('error', 'Ảnh chụp bị lỗi, vui lòng thử lại.');
        }
        // Lưu ảnh vào thư mục public như cũ
        $imagePath = 'attendance_images/' . $user->id . '_' . $today . '_' . time() . (strpos($capturedUrl, 'jpeg') !== false ? '.jpg' : '.png');
        $fullPath = storage_path('app/public/' . $imagePath);
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        copy($image1Path, $fullPath);

        // Lấy vị trí nếu có
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $distance = $request->input('distance');
        
        // Lấy location active của manager
        $manager = $user->manager; // quan hệ belongsTo
        if (is_numeric($manager)) {
            $manager = \App\Models\User::find($manager);
        }
        if (!$manager) {
            return redirect()->back()->with('error', 'Không xác định được quản lý của bạn.');
        }
        $managerLocation = \App\Models\Location::where('user_id', $manager->id)
            ->where('is_active', true)
            ->first();
        if (!$managerLocation) {
            return redirect()->back()->with('error', 'Quản lý của bạn chưa thiết lập vị trí chấm công.');
        }
        $officeLat = $managerLocation->latitude;
        $officeLng = $managerLocation->longitude;
        $officeRadius = $managerLocation->radius;
        if ($latitude && $longitude && !$distance) {
            // Làm tròn lat/lng về 6 chữ số thập phân để giảm sai số GPS
            $latitude = round($latitude, 6);
            $longitude = round($longitude, 6);
            $officeLat = round($officeLat, 6);
            $officeLng = round($officeLng, 6);
            $distance = $this->haversine($latitude, $longitude, $officeLat, $officeLng) * 1000; // m
            $distance = round($distance, 2); // làm tròn về 2 số thập phân
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

        // Xác định status giống logic FaceCompareController
        $status = 'present';
        $now = $currentTime;
        // Lấy shiftStart từ DB
        $shiftId = $request->input('shift_id');
        $overtimeId = $request->input('overtime_id');
        $shiftStart = null;
        if ($shiftId) {
            $shift = \App\Models\Shift::find($shiftId);
            if ($shift) {
                $shiftStart = $shift->start_time;
            }
        } elseif ($overtimeId) {
            $overtimeShift = \App\Models\OvertimeShift::find($overtimeId);
            if ($overtimeShift) {
                $shiftStart = \Carbon\Carbon::parse($overtimeShift->start_time)->format('H:i:s');
            }
        }
        if (!$shiftStart) {
            return redirect()->back()->with('error', 'Không xác định được giờ bắt đầu ca làm việc. Vui lòng kiểm tra lại thông tin ca.');
        }
        if ($confidence === null || $confidence < $threshold || ($distance !== null && $distance > $officeRadius)) {
            $status = 'absent';
        } elseif ($now->format('H:i:s') > $shiftStart) {
            $status = 'late';
        } else {
            $status = 'present';
        }
        if ($status === 'absent') {
            return redirect()->back()->with('error', 'Chấm công thất bại! Khuôn mặt không khớp hoặc ngoài phạm vi cho phép. Điểm: ' . ($confidence ?? 'N/A'));
        }

        // === Xử lý tạo/cập nhật attendance ===
        // Xác định shift hay overtime
        $shiftId = $request->input('shift_id');
        $overtimeId = $request->input('overtime_id');
        $attendance = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
        if ($attendance) {
            // Update bản ghi cũ
            if ($shiftId) {
                $attendance->shift_id = $shiftId;
                $attendance->overtime_id = null;
            } elseif ($overtimeId) {
                $attendance->overtime_id = $overtimeId;
                $attendance->shift_id = null;
            }
        } else {
            // Tạo mới
            $attendance = new \App\Models\Attendance();
            $attendance->user_id = $user->id;
            $attendance->date = $today;
            if ($shiftId) {
                $attendance->shift_id = $shiftId;
            } elseif ($overtimeId) {
                $attendance->overtime_id = $overtimeId;
            }
        }
        $attendance->face_image = $imagePath;
        if ($action === 'check_in') {
            $attendance->check_in_time = $currentTime->format('H:i:s');
        } elseif ($action === 'check_out') {
            $attendance->check_out_time = $currentTime->format('H:i:s');
        }
        $attendance->status = $status;
        $attendance->save();

        // === Tạo hoặc cập nhật work summary ===
        $month = $currentTime->month;
        $year = $currentTime->year;
        $workSummary = \App\Models\WorkSummary::firstOrNew([
            'user_id' => $user->id,
            'month' => $month,
            'year' => $year,
        ]);
        // Lấy tất cả attendance của user trong tháng
        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
        // Tổng số giờ làm (chỉ tính các bản ghi có check_in và check_out)
        $totalWorkHours = 0;
        $totalLateDays = 0;
        foreach ($attendances as $att) {
            // Chỉ tính nếu có shift hoặc overtime và đã check-in/check-out
            if ($att->shift_id && $att->shift) {
                $date = is_string($att->date) ? substr($att->date, 0, 10) : $att->date->format('Y-m-d');
                $shiftStart = \Carbon\Carbon::parse($date . ' ' . $att->shift->start_time);
                $shiftEnd = \Carbon\Carbon::parse($date . ' ' . $att->shift->end_time);
                $workMinutes = abs($shiftEnd->diffInMinutes($shiftStart));
                $totalWorkHours += $workMinutes / 60;
            } elseif ($att->overtime_id && $att->overtimeShift) {
                $shiftStart = \Carbon\Carbon::parse($att->overtimeShift->start_time);
                $shiftEnd = \Carbon\Carbon::parse($att->overtimeShift->end_time);
                $workMinutes = abs($shiftEnd->diffInMinutes($shiftStart));
                $totalWorkHours += $workMinutes / 60;
            }
            if ($att->status === 'late') {
                $totalLateDays++;
            }
        }
        $workSummary->total_work_hours = $totalWorkHours;
        $workSummary->total_late_days = $totalLateDays;
        $workSummary->save();

        return redirect()->back()->with('success', 'Chấm công thành công! Điểm so sánh khuôn mặt: ' . $confidence . '. Trạng thái: ' . $status);
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
