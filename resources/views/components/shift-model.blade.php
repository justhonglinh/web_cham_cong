<!-- resources/views/components/shift-modal.blade.php -->
<!-- Add Shift Modal -->
<div id="createShiftModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4">Create New Shift</h2>

        <form action="{{ route('shifts.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="shiftName" class="block text-sm font-medium text-gray-700">Shift Name</label>
                <input type="text" id="shiftName" name="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="startTime" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" id="startTime" name="start_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="endTime" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" id="endTime" name="end_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition duration-300">Submit
                </button>
                <button type="button" id="closeShiftModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Shift Modal -->
<div id="editShiftModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3 max-w-lg">
        <h2 class="text-xl font-semibold mb-4">Edit Shift</h2>

        <form id="editShiftForm" action="" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden id input: bạn có thể bỏ name="id" nếu id chỉ lấy từ URL -->
            <input type="hidden" id="editShiftId" name="id" />

            <div class="mb-4">
                <label for="editShiftName" class="block text-sm font-medium text-gray-700">Shift Name</label>
                <input type="text" id="editShiftName" name="name" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editStartTime" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" id="editStartTime" name="start_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="mb-4">
                <label for="editEndTime" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" id="editEndTime" name="end_time" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition duration-300">Update
                </button>
                <button type="button" id="closeEditShiftModal"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition duration-300">Cancel
                </button>
            </div>
        </form>
    </div>
</div>
