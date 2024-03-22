<!-- Logout Modal -->
<div id="logoutModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeLogoutModal()">
    <div class="bg-white p-4 rounded shadow-md" onclick="event.stopPropagation();">
        <h2 class="text-2xl mb-4">Confirmation</h2>
        <p class="mb-4">Are you sure you want to logout?</p>
        <div class="flex justify-end">
            <button class=" bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 hover:text-gray-100 transition duration-75 mr-2" onclick="closeLogoutModal()">Cancel</button>
            <a href="/logout.php" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 hover:text-gray-100 transition duration-75">Logout</a>
        </div>
    </div>
</div>