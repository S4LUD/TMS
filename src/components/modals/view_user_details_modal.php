<div id="viewUserDetails" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeViewUserDetailsModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">User Details</h2>
            <i onclick="closeViewUserDetailsModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <input type="text" id="userId" hidden>
        <div class="mb-4">
            <label for="view_fullname" class="block text-gray-700">Fullname:</label>
            <p id="view_fullname" class="text-gray-900"></p>
        </div>

        <div class="mb-4">
            <label for="view_address" class="block text-gray-700">Address:</label>
            <p id="view_address" class="text-gray-900"></p>
        </div>

        <div class="mb-4">
            <label for="view_age" class="block text-gray-700">Age:</label>
            <p id="view_age" class="text-gray-900"></p>
        </div>

        <div class="mb-4">
            <label for="view_contact" class="block text-gray-700">Contact:</label>
            <p id="view_contact" class="text-gray-900"></p>
        </div>

        <div class="mb-4">
            <label for="view_gender" class="block text-gray-700">Gender:</label>
            <p id="view_gender" class="text-gray-900"></p>
        </div>
    </div>
</div>