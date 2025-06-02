document.addEventListener('DOMContentLoaded', function () {
    // --- Modal CREATE ---
    let openBtn = document.getElementById('openCreateOvertimeModal');
    let closeBtn = document.getElementById('closeCreateOvertimeModal');
    let modal = document.getElementById('createOvertimeModal');

    if (openBtn && closeBtn && modal) {
        openBtn.addEventListener('click', function () {
            modal.classList.remove('hidden');
        });
        closeBtn.addEventListener('click', function () {
            modal.classList.add('hidden');
        });
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }

    // --- Modal EDIT ---
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', e => {
            console.log('CLICKED EDIT BUTTON');

            e.preventDefault();
            try {
                const shift = JSON.parse(button.getAttribute('data-shift'));
                document.getElementById('edit_name').value = shift.name || '';
                document.getElementById('edit_start_time').value = shift.start_time ? shift.start_time.slice(0,5) : '';
                document.getElementById('edit_end_time').value = shift.end_time ? shift.end_time.slice(0,5) : '';
                document.getElementById('edit_description').value = shift.description || '';
                document.getElementById('edit_max_registrations').value = shift.max_registrations || '';
                document.getElementById('edit_date').value = shift.date || '';
                document.getElementById('editOvertimeForm').action = `/overtime/management/${shift.id}`;
                document.getElementById('editOvertimeModal').classList.remove('hidden');
            } catch (err) {
                console.error('Có lỗi khi phân tích dữ liệu hoặc set giá trị:', err);
            }
        });
    });

    if(document.getElementById('closeEditOvertimeModal')) {
        document.getElementById('closeEditOvertimeModal').addEventListener('click', () => {
            document.getElementById('editOvertimeModal').classList.add('hidden');
        });
    }
    window.addEventListener('click', e => {
        const overtimeModal = document.getElementById('editOvertimeModal');
        if (e.target === overtimeModal) {
            overtimeModal.classList.add('hidden');
        }
    });
});
