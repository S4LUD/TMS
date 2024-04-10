<div id="user_information_modal" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeUserInformationModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">User Information</h2>
            <i onclick="closeUserInformationModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>

        <div class="mb-4">
            <label for="user_information_edit_fullname" class="block text-gray-700">Fullname:</label>
            <input type="text" id="user_information_edit_fullname" name="fullname" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
        </div>

        <div class="mb-4">
            <label for="user_information_edit_address" class="block text-gray-700">Address:</label>
            <input type="text" id="user_information_edit_address" name="address" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
        </div>

        <div class="mb-4">
            <label for="user_information_edit_age" class="block text-gray-700">Age:</label>
            <input type="number" id="user_information_edit_age" name="age" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
        </div>

        <div class="mb-4">
            <label for="user_information_edit_contact" class="block text-gray-700">Contact:</label>
            <input type="text" id="user_information_edit_contact" name="contact" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
        </div>

        <div class="mb-4">
            <label for="user_information_edit_gender" class="block text-gray-700">Gender:</label>
            <select id="user_information_edit_gender" name="gender" class="bg-white w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
                <option value="">---select---</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit" onclick="saveUserDetails()" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Save</button>
        </div>
    </div>
</div>