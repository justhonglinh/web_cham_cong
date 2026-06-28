document.addEventListener('DOMContentLoaded', () => {
    // --- SHIFT MODAL ---
    const openCreateShiftBtn = document.getElementById('openCreateShiftModal');
    const createShiftModal = document.getElementById('createShiftModal');
    const closeCreateShiftBtn = document.getElementById('closeShiftModal');

    if (openCreateShiftBtn && createShiftModal && closeCreateShiftBtn) {
        openCreateShiftBtn.addEventListener('click', e => {
            e.preventDefault();
            createShiftModal.classList.remove('hidden');
        });

        closeCreateShiftBtn.addEventListener('click', () => {
            createShiftModal.classList.add('hidden');
        });
    }

    // === Mở modal sửa === (Logic giống hệt nút sửa nhân viên)
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const shift = JSON.parse(button.getAttribute('data-user'));

            document.getElementById('editShiftId').value = shift.id;
            document.getElementById('editShiftName').value = shift.name;
            document.getElementById('editStartTime').value = shift.start_time;
            document.getElementById('editEndTime').value = shift.end_time;

            document.getElementById('editShiftForm').action = `/shift/management/${shift.id}`;

            document.getElementById('editShiftModal').classList.remove('hidden');
        });
    });

    // === Đóng modal sửa ===
    const closeEditShiftBtn = document.getElementById('closeEditShiftModal');
    if (closeEditShiftBtn) {
        closeEditShiftBtn.addEventListener('click', () => {
            document.getElementById('editShiftModal').classList.add('hidden');
        });
    }

    // === Click ngoài modal để đóng ===
    window.addEventListener('click', e => {
        if (e.target === createShiftModal) {
            createShiftModal.classList.add('hidden');
        }
        if (e.target === document.getElementById('editShiftModal')) {
            document.getElementById('editShiftModal').classList.add('hidden');
        }
    });
});
