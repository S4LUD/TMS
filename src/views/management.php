<?php
$userData = json_decode($_SESSION['user'], true);
$permissions = json_decode($userData['permissions'], true);
?>

<div>
    <?php if ($permissions['account_management']['source']['create_user']) { ?>
        <button id="openCreateUserModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Create a user</button>
    <?php } ?>
    <?php if ($permissions['account_management']['source']['roles']) { ?>
        <button id="openRoleModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Roles</button>
    <?php } ?>
    <?php if ($permissions['account_management']['source']['departments']) { ?>
        <button id="openDepartmentModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Departments</button>
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
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/register_user_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/permissions_modal.php'); ?>
<script src="/tms/src/assets/scripts/management/index.js"></script>
<script src="/tms/src/assets/scripts/management/search.js"></script>
<script src="/tms/src/assets/scripts/management/create_user.js"></script>
<script src="/tms/src/assets/scripts/management/permissions.js"></script>