document.addEventListener('DOMContentLoaded', () => {
    // === Các biến modal ===
    const detailModal = document.getElementById('attendanceOvertimeDetailModal');
    const editModal = document.getElementById('attendanceOvertimeEditModal');

    // === Nút đóng modal ===
    const closeDetailModalButton = document.getElementById('closeAttendanceOvertimeDetailModal');
    const closeEditModalButton = document.getElementById('closeAttendanceOvertimeEditModal');
    const cancelEditModalButton = document.getElementById('cancelAttendanceOvertimeEditModal');

    // === Đóng modal chi tiết ===
    if (closeDetailModalButton) {
        closeDetailModalButton.addEventListener('click', function () {
            if (detailModal) {
                detailModal.classList.add('hidden');
            }
        });
    }

    // === Đóng modal sửa ===
    if (closeEditModalButton) {
        closeEditModalButton.addEventListener('click', function () {
            if (editModal) {
                editModal.classList.add('hidden');
            }
        });
    }

    // === Đóng modal sửa khi nhấn nút Cancel ===
    if (cancelEditModalButton) {
        cancelEditModalButton.addEventListener('click', function () {
            if (editModal) {
                editModal.classList.add('hidden');
            }
        });
    }

    // === Mở modal xem chi tiết ===
    const detailButtons = document.querySelectorAll('.openOvertimeDetailModal');
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const overtimeData = JSON.parse(this.getAttribute('data-overtime'));

            // Hiển thị thông tin trong modal
            document.getElementById('attendanceOvertimeDetailUserAvatar').src = overtimeData.user.avatar ? `/storage/${overtimeData.user.avatar}` : '/images/default-avatar.png';
            document.getElementById('attendanceOvertimeDetailFaceImage').src = overtimeData.face_image ? `/storage/${overtimeData.face_image}` : '/images/default-face.png';
            document.getElementById('attendanceOvertimeDetailUserName').textContent = overtimeData.user.name;
            document.getElementById('attendanceOvertimeDetailUserEmail').textContent = overtimeData.user.email;
            document.getElementById('attendanceOvertimeDetailDate').textContent = formatDate(overtimeData.date);
            document.getElementById('attendanceOvertimeDetailShift').textContent = overtimeData.overtime_shift ? overtimeData.overtime_shift.name : '—';
            document.getElementById('attendanceOvertimeDetailCheckIn').textContent = formatTime(overtimeData.check_in_time);
            document.getElementById('attendanceOvertimeDetailCheckOut').textContent = formatTime(overtimeData.check_out_time);

            const statusElement = document.getElementById('attendanceOvertimeDetailStatus');
            statusElement.textContent = convertStatusToText(overtimeData.status);
            statusElement.className = 'text-sm font-medium ' + getStatusClass(overtimeData.status);

            detailModal.classList.remove('hidden');
        });
    });

    // === Mở modal sửa ===
    const editButtons = document.querySelectorAll('.openOvertimeEditModal');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const overtimeData = JSON.parse(this.getAttribute('data-overtime'));

            // Điền thông tin vào form
            const setValue = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.value = value ?? '';
            };
            setValue('editAttendanceOvertimeId', overtimeData.id);
            setValue('editAttendanceOvertimeUserId', overtimeData.user_id);
            setValue('editAttendanceOvertimeDate', overtimeData.date);
            setValue('editAttendanceOvertimeCheckIn', overtimeData.check_in_time);
            setValue('editAttendanceOvertimeCheckOut', overtimeData.check_out_time);
            setValue('editAttendanceOvertimeStatus', overtimeData.status);

            // Set đúng option ca làm
            const shiftSelect = document.getElementById('editAttendanceOvertimeShiftId');
            if (shiftSelect) {
                shiftSelect.value = overtimeData.overtime_shift_id ?? '';
            }

            // Cập nhật action của form
            const editForm = document.getElementById('editAttendanceOvertimeForm');
            if (editForm) {
                console.log("hello");
                editForm.action = `/attendance-overtime/management/${overtimeData.id}`;
            }

            if (editModal) {
                editModal.classList.remove('hidden');
            }
        });
    });

    // === Xử lý form sửa ===
    const editForm = document.getElementById('editAttendanceOvertimeForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const overtimeId = document.getElementById('editAttendanceOvertimeId').value;

            // Gửi request POST với _method=PUT (Laravel style)
            const dataObj = Object.fromEntries(formData);
            dataObj._method = 'PUT';
            fetch(`/attendance-overtime/management/${overtimeId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dataObj)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi cập nhật thông tin');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật thông tin');
                });
        });
    }

    // === Đóng modal khi click bên ngoài ===
    const modals = ['attendanceOvertimeDetailModal', 'attendanceOvertimeEditModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    });

    // === Đóng modal khi nhấn phím ESC ===
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        }
    });

    // === Hàm helper để format ngày ===
    function formatDate(dateString) {
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
        return new Date(dateString).toLocaleDateString('vi-VN', options);
    }

    // === Hàm helper để format giờ ===
    function formatTime(timeString) {
        if (!timeString) return 'N/A';
        return timeString.substring(0, 5);
    }

    // === Hàm chuyển đổi trạng thái sang tiếng Việt
    function convertStatusToText(status) {
        const statusMap = {
            'present': 'Đã chấm công',
            'absent': 'Vắng mặt',
            'late': 'Đi muộn',
            'early_leave': 'Về sớm',
            'leave': 'Nghỉ phép'
        };
        return statusMap[status] || status;
    }

    // === Hàm lấy class màu cho trạng thái
    function getStatusClass(status) {
        const classMap = {
            'present': 'text-green-600',
            'absent': 'text-red-600',
            'late': 'text-yellow-600',
            'early_leave': 'text-orange-600',
            'leave': 'text-blue-600'
        };
        return classMap[status] || 'text-gray-600';
    }
}); 