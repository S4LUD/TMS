<div id="insertroleModalOverlay" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeInsertRoleModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">New Role</h2>
            <i onclick="closeInsertRoleModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <form id="insertRoleForm">
            <div class="mb-4">
                <label for="insert_role" class="block text-gray-700">Role:</label>
                <input type="text" id="insert_role" name="role" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
            </div>

            <div class="mb-4">
                <label for="insert_visibility" class="block text-gray-700">Role:</label>
                <!-- Dropdown for role selection -->
                <select id="insert_visibility" name="visibility" class="bg-white w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
                    <option value="PUBLIC">PUBLIC</option>
                    <option value="PRIVATE">PRIVATE</option>
                </select>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" onclick="" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Save</button>
            </div>
        </form>
    </div>
</div>