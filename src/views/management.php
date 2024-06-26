<?php
$userData = json_decode($_SESSION['user'], true);
$permissions = json_decode($userData['permissions'], true);
?>

<div>
    <?php if ($permissions['account_management']['source']['create_user']) { ?>
        <button id="openCreateUserModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Create a user</button>
    <?php } ?>
    <?php if ($permissions['account_management']['source']['roles']) { ?>
        <button onclick="openRoleModal()" id="openRoleModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Roles</button>
    <?php } ?>
    <?php if ($permissions['account_management']['source']['departments']) { ?>
        <button onclick="openDepartmentModal()" id="openDepartmentModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Departments</button>
    <?php } ?>
</div>

<div class="bg-white p-4 mt-4 border rounded">
    <form onsubmit="return false;" class="select-none">
        <div class="flex flex-col sm:flex-row gap-1">
            <input type="text" id="searchAccount" name="searchAccount" placeholder="Search username, id" class="border rounded-md py-2 px-4 focus:outline-none focus:border-blue-500 transition duration-75">
            <button type="button" id="searchButton" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Search</button>
            <button type="button" id="clearButton" class="hidden font-semibold bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Clear</button>
        </div>
        <small class="pl-2 text-gray-500 self-end">Search is case-sensitive</small>
    </form>

    <div class="overflow-x-auto mt-4 select-none">
        <div class="md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">USERNAME</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">ROLE</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DEPARTMENT</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">STATUS</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="userTable"></tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-end items-center mt-4">
        <div class="flex justify-start">
            <button id="prevPageBtn" class="py-2 px-4"><i class="fa-solid fa-chevron-left"></i></button>
        </div>
        <div class="flex items-center gap-2">
            <div class="font-medium">Page</div>
            <div class="flex justify-center">
                <input type="text" id="limitInput" class="border text-center rounded-md py-0.5 w-10" value="1" readonly>
            </div>
            <div class="flex gap-1">
                <span class="font-medium">of</span>
                <span id="userCount" class="font-medium"></span>
            </div>
        </div>
        <div class="flex justify-end">
            <button id="nextPageBtn" class="py-2 px-4">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/register_user_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/permissions_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/edit_user_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/view_user_details_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/role_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/department_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/insert_department_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/update_department_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/insert_role_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/update_role_modal.php'); ?>
<script src="/tms/src/assets/scripts/management/index.js"></script>
<script src="/tms/src/assets/scripts/management/search.js"></script>
<script src="/tms/src/assets/scripts/management/create_user.js"></script>
<script src="/tms/src/assets/scripts/management/permissions.js"></script>
<script src="/tms/src/assets/scripts/management/update_user.js"></script>
<script src="/tms/src/assets/scripts/management/delete_user.js"></script>
<script src="/tms/src/assets/scripts/management/view_user.js"></script>
<script src="/tms/src/assets/scripts/management/roles.js"></script>
<script src="/tms/src/assets/scripts/management/department.js"></script>
<script src="/tms/src/assets/scripts/management/insert_department.js"></script>
<script src="/tms/src/assets/scripts/management/update_department.js"></script>
<script src="/tms/src/assets/scripts/management/delete_department.js"></script>
<script src="/tms/src/assets/scripts/management/insert_role.js"></script>
<script src="/tms/src/assets/scripts/management/update_role.js"></script>
<script src="/tms/src/assets/scripts/management/delete_role.js"></script>
<script src="/tms/src/assets/scripts/management/unblock_user.js"></script>