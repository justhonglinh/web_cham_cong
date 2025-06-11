<!-- Attendance Modal -->
<div id="attendanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Chi tiết chấm công</h2>

        <form id="attendanceForm" class="space-y-4">
            <input type="hidden" name="attendance_id" id="attendance_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nhân viên</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ngày</label>
                <input type="date" name="date" id="date" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ca làm việc</label>
                <select name="shift_id" id="shift_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giờ check-in</label>
                <input type="time" name="check_in" id="check_in" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Giờ check-out</label>
                <input type="time" name="check_out" id="check_out" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="present">Có mặt</option>
                    <option value="absent">Vắng mặt</option>
                    <option value="late">Đi muộn</option>
                    <option value="early">Về sớm</option>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition duration-300">Xác nhận</button>
                <button type="button" onclick="closeAttendanceModal()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">Huỷ</button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Attendance Modal -->
<div id="attendanceDetailModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-md relative">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Chi tiết chấm công</h2>
            <button onclick="closeAttendanceDetailModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex flex-col items-center mb-6">
            <div class="text-center">
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200" id="detailEmployeeName"></p>
                <p class="text-sm text-gray-500 dark:text-gray-400" id="detailDate"></p>
            </div>
        </div>

        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg space-y-3">
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-x-2">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <strong>Ca làm việc:</strong>
                <span id="detailShift" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-x-2">
                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <strong>Giờ check-in:</strong>
                <span id="detailCheckIn" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-x-2">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <strong>Giờ check-out:</strong>
                <span id="detailCheckOut" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-x-2">
                <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <strong>Trạng thái:</strong>
                <span id="detailStatus" class="ml-1 text-gray-800 dark:text-white italic"></span>
            </p>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="closeAttendanceDetailModal()"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-500 transition duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Đóng
            </button>
        </div>
    </div>
</div>

<script>
function openAttendanceModal(attendance = null) {
    const modal = document.getElementById('attendanceModal');
    modal.classList.remove('hidden');
    
    if (attendance) {
        // Fill form with attendance data
        document.getElementById('attendance_id').value = attendance.id;
        document.getElementById('user_id').value = attendance.user_id;
        document.getElementById('date').value = attendance.date;
        document.getElementById('shift_id').value = attendance.shift_id;
        document.getElementById('check_in').value = attendance.check_in;
        document.getElementById('check_out').value = attendance.check_out;
        document.getElementById('status').value = attendance.status;
    } else {
        // Reset form
        document.getElementById('attendanceForm').reset();
        document.getElementById('attendance_id').value = '';
    }
}

function closeAttendanceModal() {
    const modal = document.getElementById('attendanceModal');
    modal.classList.add('hidden');
}

function openAttendanceDetailModal(attendance) {
    const modal = document.getElementById('attendanceDetailModal');
    modal.classList.remove('hidden');
    
    // Fill detail data
    document.getElementById('detailEmployeeName').textContent = attendance.user.name;
    document.getElementById('detailDate').textContent = formatDate(attendance.date);
    document.getElementById('detailShift').textContent = `${attendance.shift.name} (${attendance.shift.start_time} - ${attendance.shift.end_time})`;
    document.getElementById('detailCheckIn').textContent = attendance.check_in || 'Chưa check-in';
    document.getElementById('detailCheckOut').textContent = attendance.check_out || 'Chưa check-out';
    document.getElementById('detailStatus').innerHTML = getStatusText(attendance.status);
}

function closeAttendanceDetailModal() {
    const modal = document.getElementById('attendanceDetailModal');
    modal.classList.add('hidden');
}

function getStatusText(status) {
    const statusMap = {
        'present': '<span class="text-green-500">✓ Có mặt</span>',
        'absent': '<span class="text-red-500">✗ Vắng mặt</span>',
        'late': '<span class="text-yellow-500">⚠ Đi muộn</span>',
        'early': '<span class="text-orange-500">↩ Về sớm</span>'
    };
    return statusMap[status] || status;
}

function formatDate(dateString) {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('vi-VN', options);
}

// Handle form submission
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const attendanceId = formData.get('attendance_id');
    const url = attendanceId ? `/attendance/${attendanceId}` : '/attendance';
    const method = attendanceId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAttendanceModal();
            // Reload page or update table
            window.location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
});
</script> 