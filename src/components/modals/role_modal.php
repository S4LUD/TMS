<div id="roleModalOverlay" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeRoleModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-xl font-medium">Roles List</h2>
            <i onclick="closeRoleModal()" class="h-fit fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <button onclick="" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded">Add New Role</button>
        <div class="mt-4">
            <!-- Role list -->
            <ul id="roleList" class="divide-y divide-gray-300">
                <!-- Role items will be dynamically added here -->
                <li class="py-2 flex justify-between items-center">
                    <div>
                        <p class="font-bold">Role Abbreviation: <span class="text-blue-500">R1</span></p>
                        <p class="text-sm text-gray-600">Meaning: Role 1</p>
                    </div>
                    <div>
                        <span class="bg-red-500 hover:bg-red-600 text-white hover:text-gray-100 px-2 py-1 rounded delete-btn" style="cursor: pointer"><i class="fa-solid fa-trash-can"></i></span>
                        <span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded edit-btn" style="cursor: pointer"><i class="fa-solid fa-pen-to-square"></i></span>
                    </div>
                </li>
                <li class="py-2 flex justify-between items-center">
                    <div>
                        <p class="font-bold">Role Abbreviation: <span class="text-blue-500">R2</span></p>
                        <p class="text-sm text-gray-600">Meaning: Role 2</p>
                    </div>
                    <div>
                        <span class="bg-red-500 hover:bg-red-600 text-white hover:text-gray-100 px-2 py-1 rounded delete-btn" style="cursor: pointer"><i class="fa-solid fa-trash-can"></i></span>
                        <span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded edit-btn" style="cursor: pointer"><i class="fa-solid fa-pen-to-square"></i></span>
                    </div>
                </li>
                <li class="py-2 flex justify-between items-center">
                    <div>
                        <p class="font-bold">Role Abbreviation: <span class="text-blue-500">R3</span></p>
                        <p class="text-sm text-gray-600">Meaning: Role 3</p>
                    </div>
                    <div>
                        <span class="bg-red-500 hover:bg-red-600 text-white hover:text-gray-100 px-2 py-1 rounded delete-btn" style="cursor: pointer"><i class="fa-solid fa-trash-can"></i></span>
                        <span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded edit-btn" style="cursor: pointer"><i class="fa-solid fa-pen-to-square"></i></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>