document.addEventListener('DOMContentLoaded', function() {
    // === Lấy các element cần thiết ===
    const detailModal = document.getElementById('attendanceDetailModal');
    const editModal = document.getElementById('attendanceEditModal');
    const detailForm = document.getElementById('attendanceDetailForm');
    const editForm = document.getElementById('attendanceEditForm');

    // === Xử lý nút đóng modal ===
    const closeDetailModalButton = document.getElementById('closeDetailModal');
    const closeEditModalButton = document.getElementById('closeEditModal');
    const cancelEditModalButton = document.getElementById('cancelEditModal');

    // Đóng modal chi tiết
    if (closeDetailModalButton) {
        closeDetailModalButton.addEventListener('click', function() {
            console.log('Close detail modal clicked');
            if (detailModal) {
                detailModal.classList.add('hidden');
            }
        });
    }

    // Đóng modal edit
    if (closeEditModalButton) {
        closeEditModalButton.addEventListener('click', function() {
            console.log('Close edit modal clicked');
            if (editModal) {
                editModal.classList.add('hidden');
            }
        });
    }

    // Đóng modal edit khi click nút huỷ
    if (cancelEditModalButton) {
        cancelEditModalButton.addEventListener('click', function() {
            console.log('Cancel edit modal clicked');
            if (editModal) {
                editModal.classList.add('hidden');
            }
        });
    }

    // === Mở modal xem chi tiết ===
    const detailButtons = document.querySelectorAll('.openDetailModal');
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const attendanceData = JSON.parse(this.getAttribute('data-attendance'));
            console.log('Detail Data:', attendanceData);

            // Lấy các element trong modal
            const userAvatar = document.getElementById('detailUserAvatar');
            const userName = document.getElementById('detailUserName');
            const userEmail = document.getElementById('detailUserEmail');
            const faceImage = document.getElementById('detailFaceImage');
            const date = document.getElementById('detailDate');
            const shift = document.getElementById('detailShift');
            const checkIn = document.getElementById('detailCheckIn');
            const checkOut = document.getElementById('detailCheckOut');
            const status = document.getElementById('detailStatus');

            // Cập nhật thông tin
            if (userAvatar) userAvatar.src = attendanceData.user.avatar ? `/storage/${attendanceData.user.avatar}` : 'https://via.placeholder.com/150';
            if (userName) userName.textContent = attendanceData.user.name;
            if (userEmail) userEmail.textContent = attendanceData.user.email;
            if (faceImage) faceImage.src = attendanceData.face_image ? `/storage/${attendanceData.face_image}` : 'https://via.placeholder.com/300x200';
            if (date) date.textContent = new Date(attendanceData.date).toLocaleDateString('vi-VN');
            if (shift) shift.textContent = attendanceData.shift ? attendanceData.shift.name : '—';
            if (checkIn) {
                if (attendanceData.check_in_time) {
                    const [hours, minutes] = attendanceData.check_in_time.split(':');
                    checkIn.textContent = `${hours}:${minutes}`;
                } else {
                    checkIn.textContent = '—';
                }
            }
            if (checkOut) {
                if (attendanceData.check_out_time) {
                    const [hours, minutes] = attendanceData.check_out_time.split(':');
                    checkOut.textContent = `${hours}:${minutes}`;
                } else {
                    checkOut.textContent = '—';
                }
            }
            if (status) {
                status.textContent = getStatusText(attendanceData.status);
                status.className = getStatusClass(attendanceData.status);
            }

            // Hiển thị modal
            if (detailModal) {
                detailModal.classList.remove('hidden');
            }
        });
    });

    // === Hàm dùng chung để binding dữ liệu vào form sửa ===
    function fillEditForm(data, type) {
        if (type === 'overtime') {
            document.getElementById('editAttendanceOvertimeId').value = data.id;
            document.getElementById('editAttendanceOvertimeUserId').value = data.user_id;
            document.getElementById('editAttendanceOvertimeDate').value = data.date;
            document.getElementById('editAttendanceOvertimeCheckIn').value = data.check_in_time || '';
            document.getElementById('editAttendanceOvertimeCheckOut').value = data.check_out_time || '';
            document.getElementById('editAttendanceOvertimeStatus').value = data.status;
            // Chỉ set overtime_shift_id
            const shiftSelect = document.getElementById('editAttendanceOvertimeShiftId');
            if (shiftSelect) {
                Array.from(shiftSelect.options).forEach(opt => {
                    opt.selected = (opt.value == String(data.overtime_id || data.overtime_shift_id));
                });
            }
        } else {
            document.getElementById('editAttendanceId').value = data.id;
            document.getElementById('editUserId').value = data.user_id;
            document.getElementById('editDate').value = data.date;
            document.getElementById('editCheckIn').value = data.check_in_time || '';
            document.getElementById('editCheckOut').value = data.check_out_time || '';
            document.getElementById('editStatus').value = data.status;
            // Chỉ set shift_id
            const shiftSelect = document.getElementById('editShiftId');
            if (shiftSelect) {
                Array.from(shiftSelect.options).forEach(opt => {
                    opt.selected = (opt.value == String(data.shift_id));
                });
            }
        }
    }

    // === Mở modal chỉnh sửa ca làm việc ===
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const attendanceData = JSON.parse(this.getAttribute('data-attendance'));
            if (!attendanceData.id) {
                alert('Lỗi: Không tìm thấy ID của bản ghi chấm công!');
                if (editForm) editForm.action = '';
                return;
            }
            if (editForm) {
                editForm.action = `/attendance/management/${attendanceData.id}`;
            }
            fillEditForm(attendanceData, 'normal');
            editModal.classList.remove('hidden');
        });
    });

    // === Mở modal sửa tăng ca ===
    document.querySelectorAll('.openOvertimeEditModal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const overtimeData = JSON.parse(this.getAttribute('data-overtime'));
            const overtimeForm = document.getElementById('editAttendanceOvertimeForm');
            if (overtimeForm && overtimeData.id) {
                overtimeForm.action = `/attendance/management/${overtimeData.id}`;
            }
            fillEditForm(overtimeData, 'overtime');
            document.getElementById('attendanceOvertimeEditModal').classList.remove('hidden');
        });
    });

    // === Đóng modal khi click bên ngoài
    window.addEventListener('click', function(event) {
        if (event.target === detailModal) {
            console.log('Clicked outside detail modal');
            detailModal.classList.add('hidden');
        }
        if (event.target === editModal) {
            console.log('Clicked outside edit modal');
            editModal.classList.add('hidden');
        }
    });

    // === Đóng modal khi nhấn phím ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            console.log('ESC key pressed');
            if (detailModal && !detailModal.classList.contains('hidden')) {
                detailModal.classList.add('hidden');
            }
            if (editModal && !editModal.classList.contains('hidden')) {
                editModal.classList.add('hidden');
            }
        }
    });

    // === Đóng modal khi click vào nút có data-modal-hide ===
    document.querySelectorAll('[data-modal-hide]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-hide');
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        });
    });

    // === Hàm helper để format ngày ===
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }

    // === Hàm helper để format thời gian ===
    function formatTime(timeString) {
        if (!timeString) return '—';
        const [hours, minutes] = timeString.split(':');
        return `${hours}:${minutes}`;
    }

    // === Hàm helper để chuyển đổi status sang text ===
    function getStatusText(status) {
        const statusMap = {
            'present': 'Đã chấm công',
            'absent': 'Vắng mặt',
            'leave': 'Nghỉ phép',
            'late': 'Đi muộn',
            'early_leave': 'Về sớm'
        };
        return statusMap[status] || '—';
    }

    // === Hàm helper để lấy class CSS cho status ===
    function getStatusClass(status) {
        const classMap = {
            'present': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'absent': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'leave': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'late': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'early_leave': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
        };
        return classMap[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }

    // === Xử lý submit form sửa ca làm việc (AJAX) ===
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.delete('overtime_shift_id');
            formData.append('_method', 'PUT'); // Laravel nhận diện là PUT
            const attendanceId = document.getElementById('editAttendanceId').value;
            fetch(`/attendance/management/${attendanceId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
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

    // === Xử lý submit form sửa tăng ca (AJAX) ===
    const overtimeForm = document.getElementById('editAttendanceOvertimeForm');
    if (overtimeForm) {
        overtimeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            // Đảm bảo chỉ gửi overtime_shift_id, không gửi shift_id
            formData.delete('shift_id');
            formData.append('_method', 'PUT'); // Laravel nhận diện là PUT
            const overtimeId = document.getElementById('editAttendanceOvertimeId').value;
            fetch(`/attendance/management/${overtimeId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
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
});
