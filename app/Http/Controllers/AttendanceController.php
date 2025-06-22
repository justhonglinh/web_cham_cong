<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Facades\Redirect;
use App\Models\OvertimeShift;
use App\Models\Location;

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

        // Lấy ca làm việc của user hiện tại
        $currentShift = Shift::where('user_id', $user->id)->first();
        
        // Kiểm tra xem đã chấm công hôm nay chưa
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->whereNotNull('shift_id')
            ->first();

        // Xác định trạng thái ca làm việc hiện tại
        $shiftStatus = 'no_shift';
        $canCheckIn = false;
        $shiftInfo = null;

        if ($currentShift) {
            $shiftStartTime = \Carbon\Carbon::parse($currentShift->start_time);
            $shiftEndTime = \Carbon\Carbon::parse($currentShift->end_time);
            $currentTimeOnly = $currentTime->format('H:i:s');
            
            // Kiểm tra xem có đang trong giờ làm việc không
            if ($currentTimeOnly >= $shiftStartTime->format('H:i:s') && $currentTimeOnly <= $shiftEndTime->format('H:i:s')) {
                $shiftStatus = 'active';
                $canCheckIn = !$todayAttendance || !$todayAttendance->check_in_time;
                $shiftInfo = [
                    'name' => $currentShift->name,
                    'start_time' => $shiftStartTime->format('H:i'),
                    'end_time' => $shiftEndTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i'),
                    'is_late' => $currentTimeOnly > $shiftStartTime->addMinutes(15)->format('H:i:s')
                ];
            } elseif ($currentTimeOnly < $shiftStartTime->format('H:i:s')) {
                $shiftStatus = 'upcoming';
                $shiftInfo = [
                    'name' => $currentShift->name,
                    'start_time' => $shiftStartTime->format('H:i'),
                    'end_time' => $shiftEndTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i')
                ];
            } else {
                $shiftStatus = 'ended';
                $shiftInfo = [
                    'name' => $currentShift->name,
                    'start_time' => $shiftStartTime->format('H:i'),
                    'end_time' => $shiftEndTime->format('H:i'),
                    'current_time' => $currentTime->format('H:i')
                ];
            }
        }

        return view('employees.attendance', compact('currentShift', 'todayAttendance', 'shiftStatus', 'canCheckIn', 'shiftInfo'));
    }

    /**
     * Xử lý chấm công với camera
     */
    public function processAttendance(Request $request)
    {
        $request->validate([
            'image1' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'distance' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $today = now()->toDateString();
        $currentTime = now();

        // Lấy ca làm việc hiện tại
        $currentShift = Shift::where('user_id', $user->id)->first();
        
        if (!$currentShift) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công ca làm việc');
        }

        // Kiểm tra xem đã chấm công hôm nay chưa
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->whereNotNull('shift_id')
            ->first();

        if ($todayAttendance && $todayAttendance->check_in_time) {
            return redirect()->back()->with('error', 'Bạn đã chấm công vào hôm nay');
        }

        // Xử lý ảnh chụp từ camera
        $capturedUrl = $request->input('image1');
        $image1 = str_replace('data:image/png;base64,', '', $capturedUrl);
        $image1 = str_replace(' ', '+', $image1);
        
        // Lưu ảnh chấm công
        $imagePath = 'attendance_images/' . $user->id . '_' . $today . '_' . time() . '.png';
        $fullPath = storage_path('app/public/' . $imagePath);
        
        // Tạo thư mục nếu chưa có
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        
        file_put_contents($fullPath, base64_decode($image1));

        // 1. SO SÁNH KHUÔN MẶT VỚI AVATAR
        $faceComparisonResult = $this->compareFaceWithAvatar($user, $fullPath);
        
        if (!$faceComparisonResult['success']) {
            // Xóa ảnh đã lưu nếu so sánh thất bại
            @unlink($fullPath);
            return redirect()->back()->with('error', 'Không thể xác thực khuôn mặt: ' . $faceComparisonResult['message']);
        }

        // 2. KIỂM TRA VỊ TRÍ VỚI QUẢN LÝ
        $locationValidationResult = $this->validateLocationWithManager($user, $request->input('latitude'), $request->input('longitude'));
        
        if (!$locationValidationResult['success']) {
            // Xóa ảnh đã lưu nếu kiểm tra vị trí thất bại
            @unlink($fullPath);
            return redirect()->back()->with('error', 'Vị trí không hợp lệ: ' . $locationValidationResult['message']);
        }

        // Xác định trạng thái chấm công
        $shiftStartTime = \Carbon\Carbon::parse($currentShift->start_time);
        $currentTimeOnly = $currentTime->format('H:i:s');
        
        $status = 'present';
        if ($currentTimeOnly > $shiftStartTime->addMinutes(15)->format('H:i:s')) {
            $status = 'late';
        }

        // Tạo hoặc cập nhật bản ghi chấm công
        if ($todayAttendance) {
            $todayAttendance->update([
                'check_in_time' => $currentTime,
                'status' => $status,
                'face_image' => $imagePath,
            ]);
        } else {
            Attendance::create([
                'user_id' => $user->id,
                'shift_id' => $currentShift->id,
                'date' => $today,
                'check_in_time' => $currentTime,
                'status' => $status,
                'face_image' => $imagePath,
            ]);
        }

        return redirect()->route('employees.dashboard')->with('success', 'Chấm công thành công! Độ tin cậy: ' . $faceComparisonResult['confidence'] . '%');
    }

    /**
     * So sánh khuôn mặt với avatar ban đầu
     */
    private function compareFaceWithAvatar($user, $capturedImagePath)
    {
        // Kiểm tra xem user có avatar không
        if (!$user->avatar) {
            return [
                'success' => false,
                'message' => 'Bạn chưa có ảnh đại diện trong hệ thống'
            ];
        }

        $avatarPath = storage_path('app/public/' . $user->avatar);
        
        if (!file_exists($avatarPath)) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy ảnh đại diện'
            ];
        }

        // Gọi API Face++ để so sánh
        $api_key = env('FACEPP_API_KEY');
        $api_secret = env('FACEPP_API_SECRET');
        
        if (!$api_key || !$api_secret) {
            return [
                'success' => false,
                'message' => 'Cấu hình API nhận diện khuôn mặt chưa hoàn chỉnh'
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::asMultipart()->post('https://api-us.faceplusplus.com/facepp/v3/compare', [
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'image_file1' => fopen($capturedImagePath, 'r'),
                'image_file2' => fopen($avatarPath, 'r'),
            ]);

            $result = $response->json();

            if (isset($result['error_message'])) {
                return [
                    'success' => false,
                    'message' => 'Lỗi API: ' . $result['error_message']
                ];
            }

            $confidence = $result['confidence'] ?? 0;
            $threshold = 70; // Ngưỡng tin cậy 70%

            if ($confidence >= $threshold) {
                return [
                    'success' => true,
                    'confidence' => round($confidence, 2),
                    'message' => 'Xác thực khuôn mặt thành công'
                ];
            } else {
                return [
                    'success' => false,
                    'confidence' => round($confidence, 2),
                    'message' => 'Khuôn mặt không khớp với ảnh đại diện (Độ tin cậy: ' . round($confidence, 2) . '%)'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi gọi API nhận diện: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra vị trí với quản lý
     */
    private function validateLocationWithManager($user, $latitude, $longitude)
    {
        // Kiểm tra xem user có quản lý không
        if (!$user->manager) {
            return [
                'success' => false,
                'message' => 'Bạn chưa được phân công quản lý'
            ];
        }

        // Lấy thông tin quản lý
        $manager = User::find($user->manager);
        if (!$manager) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy thông tin quản lý'
            ];
        }

        // Lấy vị trí làm việc của quản lý
        $managerLocation = Location::where('user_id', $manager->id)
            ->where('is_active', true)
            ->first();

        if (!$managerLocation) {
            return [
                'success' => false,
                'message' => 'Quản lý chưa thiết lập vị trí làm việc'
            ];
        }

        // Kiểm tra xem có vị trí GPS không
        if (!$latitude || !$longitude) {
            return [
                'success' => false,
                'message' => 'Không thể xác định vị trí hiện tại của bạn'
            ];
        }

        // Kiểm tra xem có trong phạm vi cho phép không
        if ($managerLocation->isWithinRadius($latitude, $longitude)) {
            return [
                'success' => true,
                'message' => 'Vị trí hợp lệ',
                'distance' => round($managerLocation->distanceTo($latitude, $longitude), 0)
            ];
        } else {
            $distance = round($managerLocation->distanceTo($latitude, $longitude), 0);
            return [
                'success' => false,
                'message' => "Bạn đang ở ngoài phạm vi làm việc (Cách " . $distance . "m, phạm vi cho phép: " . $managerLocation->radius . "m)"
            ];
        }
    }
}
