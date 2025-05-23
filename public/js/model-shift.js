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

    const editShiftButtons = document.querySelectorAll('.openEditModal');
    const editShiftModal = document.getElementById('editShiftModal');
    const closeEditShiftBtn = document.getElementById('closeEditShiftModal');
    const editShiftForm = document.getElementById('editShiftForm');

    if (editShiftButtons.length > 0 && editShiftModal && closeEditShiftBtn && editShiftForm) {
        editShiftButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                try {
                    const shift = JSON.parse(btn.dataset.shift);
                    if (!shift) return;

                    editShiftModal.classList.remove('hidden');

                    document.getElementById('editShiftId').value = shift.id;
                    document.getElementById('editShiftName').value = shift.name;
                    document.getElementById('editStartTime').value = shift.start_time;
                    document.getElementById('editEndTime').value = shift.end_time;

                    editShiftForm.action = `/shift/management/${shift.id}`;
                } catch (error) {
                    console.error('Lỗi khi phân tích dữ liệu shift:', error);
                }
            });
        });

        closeEditShiftBtn.addEventListener('click', () => {
            editShiftModal.classList.add('hidden');
        });
    }
});
