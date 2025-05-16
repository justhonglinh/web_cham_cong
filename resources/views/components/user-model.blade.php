<!-- resources/views/components/user-model.blade.php -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4">Create New User</h2>

        <!-- Form tạo mới người dùng -->
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700">Upload Avatar</label>
                <input
                    type="file"
                    id="avatar"
                    name="avatar"
                    accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500
               file:mr-4 file:py-2 file:px-4
               file:rounded-full file:border-0
               file:text-sm file:font-semibold
               file:bg-green-50 file:text-green-700
               hover:file:bg-green-100"
                />
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition duration-300">Submit</button>
                <button type="button" id="closeUserModal" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="userEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3 max-w-lg">
        <h2 class="text-xl font-semibold mb-4">Edit User</h2>

        <form id="editUserForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="editUserId" />

            <div class="mb-4">
                <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="editName" name="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editEmail" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="editEmail" name="email" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editPassword" class="block text-sm font-medium text-gray-700">Password <small>(để trống nếu không đổi)</small></label>
                <input type="password" id="editPassword" name="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editAvatar" class="block text-sm font-medium text-gray-700">Upload Avatar</label>
                <input type="file" id="editAvatar" name="avatar" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                      file:text-sm file:font-semibold file:bg-green-50 file:text-green-700
                      hover:file:bg-green-100" />
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">
                    Update
                </button>
                <button type="button" id="closeEditModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail User Modal -->
<div id="userDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3 max-w-lg">
        <h2 class="text-xl font-semibold mb-4">User Details</h2>

        <div class="mb-4">
            <strong>Name:</strong>
            <p id="detailName" class="mt-1 text-gray-700"></p>
        </div>

        <div class="mb-4">
            <strong>Email:</strong>
            <p id="detailEmail" class="mt-1 text-gray-700"></p>
        </div>

        <div class="mb-4">
            <strong>Avatar:</strong>
            <div class="mt-1">
                <img id="detailAvatar" src="" alt="Avatar" class="w-20 h-20 rounded-full object-cover">
            </div>
        </div>

        <div class="flex justify-end">
            <button id="closeDetailModal" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">Close</button>
        </div>
    </div>
</div>


