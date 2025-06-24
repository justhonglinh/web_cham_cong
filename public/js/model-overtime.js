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
            e.preventDefault();
            try {
                const shift = JSON.parse(button.getAttribute('data-shift'));

                // Xử lý lại các trường ngày/giờ nếu là object (Carbon)
                function getTime(val) {
                    if (!val) return '';
                    if (typeof val === 'string') return val.slice(0,5);
                    if (typeof val === 'object' && val.date) return val.date.slice(11,16); // 'YYYY-MM-DD HH:MM:SS'
                    return '';
                }
                function getDate(val) {
                    if (!val) return '';
                    if (typeof val === 'string') {
                        const d = new Date(val);
                        const year = d.getFullYear();
                        const month = String(d.getMonth() + 1).padStart(2, '0');
                        const day = String(d.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    }
                    if (typeof val === 'object' && val.date) return val.date.slice(0,10);
                    return '';
                }

                document.getElementById('edit_name').value = shift.name || '';
                document.getElementById('edit_start_time').value = getTime(shift.start_time);
                document.getElementById('edit_end_time').value = getTime(shift.end_time);
                document.getElementById('edit_description').value = shift.description || '';
                document.getElementById('edit_max_registrations').value = shift.max_registrations || '';
                document.getElementById('edit_date').value = getDate(shift.date);

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
