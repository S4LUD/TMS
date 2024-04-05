<div id="roleModalOverlay" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeRoleModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-xl font-medium">Roles List</h2>
            <i onclick="closeRoleModal()" class="h-fit fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <button onclick="openInsertRoleModal()" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded">Add New Role</button>
        <div class="mt-4">
            <!-- Role list -->
            <ul id="roleList" class="divide-y divide-gray-300">
                <!-- Role items will be dynamically added here -->
            </ul>
        </div>
        <div class="flex justify-end items-center mt-4">
            <div class="flex justify-start">
                <button id="prevRolePageBtn" class="py-2 px-4"><i class="fa-solid fa-chevron-left"></i></button>
            </div>
            <div class="flex items-center gap-2">
                <div class="font-medium">Page</div>
                <div class="flex justify-center">
                    <input type="text" id="limitRoleInput" class="border text-center rounded-md py-0.5 w-10" value="1" readonly>
                </div>
                <div class="flex gap-1">
                    <span class="font-medium">of</span>
                    <span id="roleCount" class="font-medium"></span>
                </div>
            </div>
            <div class="flex justify-end">
                <button id="nextRolePageBtn" class="py-2 px-4">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>