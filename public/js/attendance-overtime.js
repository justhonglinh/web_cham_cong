document.addEventListener('DOMContentLoaded', () => {
    console.log("Attendance Overtime JS loaded");
    
    // === Các biến modal ===
    const detailModal = document.getElementById('attendanceOvertimeDetailModal');
    const editModal = document.getElementById('attendanceOvertimeEditModal');

    console.log("Detail modal element:", detailModal);
    console.log("Edit modal element:", editModal);

    // === Nút đóng modal ===
    const closeDetailModalButton = document.getElementById('closeAttendanceOvertimeDetailModal');
    const closeEditModalButton = document.getElementById('closeAttendanceOvertimeEditModal');

    // === Đóng modal chi tiết ===
    if (closeDetailModalButton) {
        closeDetailModalButton.addEventListener('click', function() {
            if (detailModal) {
                detailModal.classList.add('hidden');
            }
        });
    }

    // === Đóng modal sửa ===
    if (closeEditModalButton) {
        closeEditModalButton.addEventListener('click', function() {
            if (editModal) {
                editModal.classList.add('hidden');
            }
        });
    }

    // === Mở modal xem chi tiết ===
    const detailButtons = document.querySelectorAll('.openAttendanceOvertimeDetailModal');
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const attendanceData = JSON.parse(this.getAttribute('data-attendance'));
            console.log('Attendance data:', attendanceData); // Debug log
            
            // Hiển thị thông tin trong modal
            document.getElementById('attendanceOvertimeDetailUserAvatar').src = attendanceData.user.avatar ? `/storage/${attendanceData.user.avatar}` : '/images/default-avatar.png';
            document.getElementById('attendanceOvertimeDetailFaceImage').src = attendanceData.face_image ? `/storage/${attendanceData.face_image}` : '/images/default-face.png';
            document.getElementById('attendanceOvertimeDetailUserName').textContent = attendanceData.user.name;
            document.getElementById('attendanceOvertimeDetailUserEmail').textContent = attendanceData.user.email;
            document.getElementById('attendanceOvertimeDetailDate').textContent = formatDate(attendanceData.date);
            document.getElementById('attendanceOvertimeDetailShift').textContent = attendanceData.overtime_shift ? attendanceData.overtime_shift.name : '—';
            document.getElementById('attendanceOvertimeDetailCheckIn').textContent = formatTime(attendanceData.check_in_time);
            document.getElementById('attendanceOvertimeDetailCheckOut').textContent = formatTime(attendanceData.check_out_time);
            
            const statusElement = document.getElementById('attendanceOvertimeDetailStatus');
            statusElement.textContent = convertStatusToText(attendanceData.status);
            statusElement.className = 'text-sm font-medium ' + getStatusClass(attendanceData.status);
            
            detailModal.classList.remove('hidden');
        });
    });

    // === Mở modal sửa ===
    document.querySelectorAll('.openAttendanceOvertimeEditModal').forEach(button => {
        button.addEventListener('click', function() {
            const attendanceData = JSON.parse(this.getAttribute('data-attendance'));
            console.log('Edit attendance data:', attendanceData); // Debug log
            
            // Điền thông tin vào form
            document.getElementById('editAttendanceOvertimeId').value = attendanceData.id;
            document.getElementById('editAttendanceOvertimeUserId').value = attendanceData.user_id;
            document.getElementById('editAttendanceOvertimeDate').value = attendanceData.date;
            document.getElementById('editAttendanceOvertimeShiftId').value = attendanceData.overtime_id;
            document.getElementById('editAttendanceOvertimeCheckIn').value = attendanceData.check_in_time;
            document.getElementById('editAttendanceOvertimeCheckOut').value = attendanceData.check_out_time;
            document.getElementById('editAttendanceOvertimeStatus').value = attendanceData.status;
            
            // Cập nhật action của form
            const editForm = document.getElementById('editAttendanceOvertimeForm');
            editForm.action = `/attendance-overtime/management/${attendanceData.id}`;
            
            editModal.classList.remove('hidden');
        });
    });

    // === Xử lý form sửa ===
    const editForm = document.getElementById('editAttendanceOvertimeForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const attendanceId = document.getElementById('editAttendanceOvertimeId').value;
            
            // Gửi request PUT
            fetch(`/attendance-overtime/management/${attendanceId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
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
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    });

    // === Đóng modal khi nhấn phím ESC ===
    document.addEventListener('keydown', function(e) {
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