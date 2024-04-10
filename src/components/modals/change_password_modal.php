<div id="change_password_modal" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeChangePasswordModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">Change Password</h2>
            <i onclick="closeChangePasswordModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>

        <form onsubmit="ChangePassword(event)">
            <div class="mb-4">
                <label for="change_current_password" class="block text-gray-700">Current Password:</label>
                <input type="password" id="change_current_password" name="currentpassword" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
            </div>

            <div class="mb-4">
                <label for="new_password" class="block text-gray-700">New Password:</label>
                <input type="password" id="new_password" name="newpassword" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
            </div>

            <div class="mb-4">
                <label for="confirm_new_password" class="block text-gray-700">Confirm New Password:</label>
                <input type="password" id="confirm_new_password" name="confirmnewpassword" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
            </div>

            <div id="change_password_error" class="text-red-600 mb-4 hidden"></div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Save</button>
            </div>
        </form>
    </div>
</div>