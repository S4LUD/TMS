<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/tms/src/controllers/Users/index.php');

$rolesJson = Users::fetchAllRoles();
$departmentsJson = Users::fetchAllDepartments();
$roles = json_decode($rolesJson, true);
$departments = json_decode($departmentsJson, true);
?>

<div id="createUserModalOverlay" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeCreateUserModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-xl font-medium">Create User</h2>
            <i onclick="closeCreateUserModal()" class="h-fit fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <form id="createUserForm">
            <div class="mb-4">
                <label for="createUsername" class="block text-gray-700">Username:</label>
                <input placeholder="Username" type="text" id="createUsername" name="createUsername" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required autocomplete="off">
            </div>

            <div class="mb-4">
                <label for="createPassword" class="block text-gray-700">Password:</label>
                <input placeholder="Password" type="password" id="createPassword" name="createPassword" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required autocomplete="new-password">
            </div>

            <div class="mb-4">
                <label for="createDepartment" class="block text-gray-700">Department:</label>
                <select id="createDepartment" name="createDepartment" class="bg-white w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
                    <option value="">---SELECT---</option>
                    <?php
                    foreach ($departments as $department) {
                        $departmentId = $department['id'];
                        $departmentName = $department['department'];
                        echo "<option value=\"$departmentId\">$departmentName</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="createRole" class="block text-gray-700">Role:</label>
                <select id="createRole" name="createRole" class="bg-white w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
                    <option value="">---SELECT---</option>
                    <?php
                    foreach ($roles as $role) {
                        $roleId = $role['id'];
                        $roleName = $role['role'];
                        echo "<option value=\"$roleId\">$roleName</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Create User</button>
            </div>
        </form>
    </div>
</div>