document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.openDetailModal').forEach(button => {
        button.addEventListener('click', () => {
            // Lấy dữ liệu attendance từ thuộc tính data-attendance (nếu có), fallback về data-user nếu chưa cập nhật blade
            let attendance = button.getAttribute('data-attendance')
                ? JSON.parse(button.getAttribute('data-attendance'))
                : JSON.parse(button.getAttribute('data-user'));

            // Gán vào modal
            document.getElementById('detailUserName').textContent = attendance.user?.name || '';
            document.getElementById('detailUserEmail').textContent = attendance.user?.email || '';
            document.getElementById('detailUserAvatar').src = attendance.user?.avatar ? `/storage/${attendance.user.avatar}` : '/images/default-avatar.png';
            document.getElementById('detailFaceImage').src = attendance.face_image ? `/storage/${attendance.face_image}` : '/images/default-face.png';
            document.getElementById('detailDate').textContent = attendance.date || '';
            document.getElementById('detailShift').textContent = attendance.shift?.name || '';
            document.getElementById('detailCheckIn').textContent = attendance.check_in_time || '';
            document.getElementById('detailCheckOut').textContent = attendance.check_out_time || '';
            document.getElementById('detailStatus').textContent = attendance.status || '';

            // Hiển thị trạng thái tiếng Việt
            const statusMap = {
                'present': 'Có mặt',
                'leave': 'Nghỉ phép',
                'absent': 'Vắng mặt',
                'late': 'Đi muộn',
                'early_leave': 'Về sớm',
            };
            document.getElementById('detailStatus').textContent = statusMap[attendance.status] || attendance.status || '';

            // Hiển thị modal
            document.getElementById('attendanceDetailModal').classList.remove('hidden');
        });
    });

    // Logic cho nút sửa
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', () => {
            let attendance = button.getAttribute('data-attendance')
                ? JSON.parse(button.getAttribute('data-attendance'))
                : JSON.parse(button.getAttribute('data-user'));

            document.getElementById('editAttendanceId').value = attendance.id || '';
            document.getElementById('editUserId').value = attendance.user_id || (attendance.user?.id || '');
            document.getElementById('editDate').value = attendance.date || '';
            document.getElementById('editShiftId').value = attendance.shift_id || (attendance.shift?.id || '');
            document.getElementById('editCheckIn').value = attendance.check_in_time || '';
            document.getElementById('editCheckOut').value = attendance.check_out_time || '';
            document.getElementById('editStatus').value = attendance.status || '';

            // Set action cho form
            document.getElementById('editAttendanceForm').action = `/attendance/management/${attendance.id}`;

            document.getElementById('attendanceEditModal').classList.remove('hidden');
        });
    });

    // Đóng modal khi bấm nút đóng
    if (document.querySelector('[data-modal-hide="attendanceDetailModal"]')) {
        document.querySelector('[data-modal-hide="attendanceDetailModal"]').addEventListener('click', () => {
            document.getElementById('attendanceDetailModal').classList.add('hidden');
        });
    }

    // Đóng modal sửa khi bấm nút huỷ
    if (document.querySelector('[data-modal-hide="attendanceEditModal"]')) {
        document.querySelector('[data-modal-hide="attendanceEditModal"]').addEventListener('click', () => {
            document.getElementById('attendanceEditModal').classList.add('hidden');
        });
    }

    // Logic cho nút chi tiết tăng ca
    document.querySelectorAll('.openDetailOvertimeModal').forEach(button => {
        button.addEventListener('click', () => {
            let overtime = button.getAttribute('data-attendance')
                ? JSON.parse(button.getAttribute('data-attendance'))
                : {};

            // Gán vào modal tăng ca
            document.getElementById('attendanceOvertimeDetailUserName').textContent = overtime.user?.name || '';
            document.getElementById('attendanceOvertimeDetailUserEmail').textContent = overtime.user?.email || '';
            document.getElementById('attendanceOvertimeDetailUserAvatar').src = overtime.user?.avatar ? `/storage/${overtime.user.avatar}` : '/images/default-avatar.png';
            document.getElementById('attendanceOvertimeDetailFaceImage').src = overtime.face_image ? `/storage/${overtime.face_image}` : '/images/default-face.png';
            document.getElementById('attendanceOvertimeDetailDate').textContent = overtime.date || '';
            document.getElementById('attendanceOvertimeDetailShift').textContent = overtime.overtime_shift?.name || '';
            document.getElementById('attendanceOvertimeDetailCheckIn').textContent = overtime.check_in_time || '';
            document.getElementById('attendanceOvertimeDetailCheckOut').textContent = overtime.check_out_time || '';
            document.getElementById('attendanceOvertimeDetailStatus').textContent = overtime.status || '';

            // Hiển thị modal tăng ca
            document.getElementById('attendanceOvertimeDetailModal').classList.remove('hidden');
        });
    });

    // Đóng modal chi tiết tăng ca
    if (document.querySelector('[data-modal-hide="attendanceOvertimeDetailModal"]')) {
        document.querySelector('[data-modal-hide="attendanceOvertimeDetailModal"]').addEventListener('click', () => {
            document.getElementById('attendanceOvertimeDetailModal').classList.add('hidden');
        });
    }

    document.querySelectorAll('.openEditOvertimeModal').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault(); // Ngăn hành vi mặc định

            // 1. Lấy dữ liệu từ thuộc tính `data-attendance`
            const overtime = JSON.parse(button.getAttribute('data-attendance'));

            // 2. Fill dữ liệu vào form
            document.getElementById('editAttendanceOvertimeId').value = overtime.id;
            document.getElementById('editAttendanceOvertimeUserId').value = overtime.user_id;
            document.getElementById('editAttendanceOvertimeDate').value = overtime.date;
            document.getElementById('editAttendanceOvertimeCheckIn').value = overtime.check_in_time || '';
            document.getElementById('editAttendanceOvertimeCheckOut').value = overtime.check_out_time || '';
            document.getElementById('editAttendanceOvertimeStatus').value = overtime.status;

            // 3. Fill ca làm tăng ca (dropdown)
            // Gán trực tiếp ID của ca tăng ca (`overtime_id` từ bảng `attendances`) vào value của select
            document.getElementById('editAttendanceOvertimeShiftId').value = overtime.overtime_id;

            // 4. Set action cho form
            const form = document.getElementById('editAttendanceOvertimeForm');
            if (form) {
                 // Route này cần là PUT trong web.php: /attendance-overtime/management/{id}
                form.action = `/attendance/management/${overtime.id}`;
            }

            // 5. Hiển thị modal
            document.getElementById('attendanceOvertimeEditModal').classList.remove('hidden');
        });
    });

    // Đóng modal sửa tăng ca khi bấm nút huỷ
    if (document.getElementById('cancelAttendanceOvertimeEditModal')) {
        document.getElementById('cancelAttendanceOvertimeEditModal').addEventListener('click', () => {
            document.getElementById('attendanceOvertimeEditModal').classList.add('hidden');
        });
    }
});
