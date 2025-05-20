    document.addEventListener('DOMContentLoaded', () => {
    // === Các biến modal ===
    const userModal = document.getElementById('userModal');
    const editModal = document.getElementById('userEditModal');
    const detailModal = document.getElementById('userDetailModal');

    // === Nút đóng modal ===
    const closeUserModalButton = document.getElementById('closeUserModal');
    const closeEditModalButton = document.getElementById('closeEditModal');
    const closeDetailModalButton = document.getElementById('closeDetailModal');

    // === Mở modal tạo mới ===
    document.getElementById('openUserModal').addEventListener('click', e => {
        e.preventDefault();
        userModal.classList.remove('hidden');
    });

    // === Đóng modal tạo mới ===
    closeUserModalButton.addEventListener('click', () => {
        userModal.classList.add('hidden');
    });

    // === Mở modal sửa ===
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const user = JSON.parse(button.getAttribute('data-user'));

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            if (document.getElementById('editPosition')) {
                document.getElementById('editPosition').value = user.position || '';
            }

            document.getElementById('editPassword').value = '';
            document.getElementById('editAvatar').value = '';
            document.getElementById('editUserForm').action = `/users/${user.id}`;

            editModal.classList.remove('hidden');
        });
    });

    // === Đóng modal sửa ===
    closeEditModalButton.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });

    // === Mở modal xem chi tiết ===
    document.querySelectorAll('.openDetailModal').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const user = JSON.parse(button.getAttribute('data-user'));

            document.getElementById('detailName').textContent = user.name || '';
            document.getElementById('detailEmail').textContent = user.email || '';

            const [datePart, timeWithZone] = user.created_at.split('T');
            const timePart = timeWithZone ? timeWithZone.split('.')[0] : ''; // bỏ .000000Z

            document.getElementById('detailCreatedDate').textContent = datePart || '';
            document.getElementById('detailCreatedTime').textContent = timePart || '';

            document.getElementById('detailAvatar').src = user.avatar ? `/storage/${user.avatar}` : 'https://via.placeholder.com/80';

            detailModal.classList.remove('hidden');
        });
    });

    // === Đóng modal xem chi tiết ===
    closeDetailModalButton.addEventListener('click', () => {
        detailModal.classList.add('hidden');
    });

    // === Click ngoài modal để đóng ===
    window.addEventListener('click', e => {
        if ([userModal, editModal, detailModal].includes(e.target)) {
            e.target.classList.add('hidden');
        }
    });
});
