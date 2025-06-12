document.addEventListener('DOMContentLoaded', () => {
    // === Các biến modal ===
    const detailModal = document.getElementById('attendanceDetailModal');
    const editModal = document.getElementById('attendanceEditModal');

    // === Nút đóng modal ===
    const closeDetailModalButton = document.getElementById('closeDetailModal');
    const closeEditModalButton = document.getElementById('closeEditModal');

    // === Mở modal xem chi tiết ===
    document.querySelectorAll('.openDetailModal').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const attendance = JSON.parse(button.getAttribute('data-attendance'));

            // Cập nhật thông tin trong modal chi tiết
            document.getElementById('detailUserName').textContent = attendance.user.name || '';
            document.getElementById('detailUserEmail').textContent = attendance.user.email || '';
            document.getElementById('detailDate').textContent = attendance.date || '';
            document.getElementById('detailShift').textContent = attendance.shift ? attendance.shift.name : '—';
            document.getElementById('detailCheckIn').textContent = attendance.check_in_time || '—';
            document.getElementById('detailCheckOut').textContent = attendance.check_out_time || '—';
            document.getElementById('detailStatus').textContent = getStatusText(attendance.status);
            document.getElementById('detailStatus').className = getStatusClass(attendance.status);

            detailModal.classList.remove('hidden');
        });
    });

    // === Mở modal sửa ===
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const attendance = JSON.parse(button.getAttribute('data-attendance'));

            // Cập nhật thông tin trong modal sửa
            document.getElementById('editAttendanceId').value = attendance.id;
            document.getElementById('editUserId').value = attendance.user_id;
            document.getElementById('editDate').value = attendance.date;
            document.getElementById('editShiftId').value = attendance.shift_id || '';
            document.getElementById('editCheckIn').value = attendance.check_in_time || '';
            document.getElementById('editCheckOut').value = attendance.check_out_time || '';
            document.getElementById('editStatus').value = attendance.status;
            document.getElementById('editForm').action = `/attendance/${attendance.id}`;
            
            editModal.classList.remove('hidden');
        });
    });

    // === Đóng modal chi tiết ===
    closeDetailModalButton.addEventListener('click', () => {
        detailModal.classList.add('hidden');
    });

    // === Đóng modal sửa ===
    closeEditModalButton.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });

    // === Click ngoài modal để đóng ===
    window.addEventListener('click', e => {
        if ([detailModal, editModal].includes(e.target)) {
            e.target.classList.add('hidden');
        }
    });

    // === Hàm helper để lấy text trạng thái ===
    function getStatusText(status) {
        const statusTexts = {
            'present': 'Đã chấm công',
            'absent': 'Vắng mặt',
            'leave': 'Nghỉ phép',
            'late': 'Đi muộn',
            'early_leave': 'Về sớm'
        };
        return statusTexts[status] || '—';
    }

    // === Hàm helper để lấy class trạng thái ===
    function getStatusClass(status) {
        const statusClasses = {
            'present': 'text-green-600 dark:text-green-400',
            'absent': 'text-red-600 dark:text-red-400',
            'leave': 'text-yellow-600 dark:text-yellow-400',
            'late': 'text-orange-600 dark:text-orange-400',
            'early_leave': 'text-orange-600 dark:text-orange-400'
        };
        return statusClasses[status] || 'text-gray-600 dark:text-gray-400';
    }
});
