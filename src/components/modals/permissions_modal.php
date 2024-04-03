<!-- Permissions Modal -->
<div id="permissionsModal" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closePermissionsModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">Permissions</h2>
            <i onclick="closePermissionsModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>

        <!-- Container for checkboxes -->
        <div id="permissionsContainer">
            <div class="mb-4">
                <!-- Performance -->
                <div class="flex items-center">
                    <input type="checkbox" id="performance">
                    <label for="performance" class="ml-2">Performance</label>
                </div>

                <!-- Report -->
                <div class="flex items-center">
                    <input type="checkbox" id="report">
                    <label for="report" class="ml-2">Report</label>
                </div>
            </div>

            <!-- Account Management -->
            <div class="mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="account_management">
                    <label for="account_management" class="ml-2">Account Management</label>
                </div>
                <!-- Sub-Permissions -->
                <div class="ml-6">
                    <!-- Create User -->
                    <div class="flex items-center">
                        <input type="checkbox" id="create_user" disabled>
                        <label for="create_user" class="ml-2">Create User</label>
                    </div>
                    <!-- Roles -->
                    <div class="flex items-center">
                        <input type="checkbox" id="roles" disabled>
                        <label for="roles" class="ml-2">Roles</label>
                    </div>
                    <!-- Departments -->
                    <div class="flex items-center">
                        <input type="checkbox" id="departments" disabled>
                        <label for="departments" class="ml-2">Departments</label>
                    </div>
                    <!-- Delete -->
                    <div class="flex items-center">
                        <input type="checkbox" id="delete" disabled>
                        <label for="delete" class="ml-2">Delete</label>
                    </div>
                    <!-- View -->
                    <div class="flex items-center">
                        <input type="checkbox" id="view" disabled>
                        <label for="view" class="ml-2">View</label>
                    </div>
                    <!-- Edit -->
                    <div class="flex items-center">
                        <input type="checkbox" id="edit" disabled>
                        <label for="edit" class="ml-2">Edit</label>
                    </div>
                    <!-- Permissions -->
                    <div class="flex items-center">
                        <input type="checkbox" id="permissions" disabled>
                        <label for="permissions" class="ml-2">Permissions</label>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            <div class="mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="tasks">
                    <label for="tasks" class="ml-2">Tasks</label>
                </div>
                <!-- Sub-Permissions -->
                <div class="ml-6">
                    <!-- Create Task -->
                    <div class="flex items-center">
                        <input type="checkbox" id="create_task" disabled>
                        <label for="create_task" class="ml-2">Create Task</label>
                    </div>
                    <!-- Delete -->
                    <div class="flex items-center">
                        <input type="checkbox" id="delete_task" disabled>
                        <label for="delete_task" class="ml-2">Delete</label>
                    </div>
                    <!-- View -->
                    <div class="flex items-center">
                        <input type="checkbox" id="view_task" disabled>
                        <label for="view_task" class="ml-2">View</label>
                    </div>
                    <!-- Edit -->
                    <div class="flex items-center">
                        <input type="checkbox" id="edit_task" disabled>
                        <label for="edit_task" class="ml-2">Edit</label>
                    </div>
                </div>
            </div>

            <!-- Distribute -->
            <div class="mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="distribute">
                    <label for="distribute" class="ml-2">Distribute</label>
                </div>
                <!-- Sub-Permissions -->
                <div class="ml-6">
                    <!-- Assign -->
                    <div class="flex items-center">
                        <input type="checkbox" id="assign" disabled>
                        <label for="assign" class="ml-2">Assign</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button onclick="savePermissions()" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Save</button>
        </div>
    </div>
</div>