<?php
function isCurrentPage($link)
{
    $segments = explode('/', $_SERVER['PHP_SELF']);
    return ($segments[2] === $link) ? 'bg-gray-600' : '';
}

$userData = json_decode($_SESSION['user'], true);
$permissions = json_decode($userData['permissions'], true);
?>
<div onclick="toggleSideMenu()" id="backdrop" class="fixed hidden top-0 left-0 w-full h-full bg-black opacity-50 z-50"></div>
<aside id="sideMenu" class="fixed hidden bottom-0 top-0 sm:relative sm:block w-64 bg-[#343A40] text-white z-50">
    <div class="flex items-center py-4 pl-4 border-b border-gray-600 whitespace-nowrap">
        <div class="min-w-9 flex justify-center items-center pr-1.5"><i class=" fas fa-user text-lg text-gray-200"></i></div>
        <?php if ($userData['auth']) { ?>
            <span class="font-semibold text-gray-200"><?php echo $userData['username'] . " | " . $userData['role'] ?></span>
        <?php } else { ?>
            <div class="flex flex-col">
                <span class="font-semibold text-gray-200"><?php echo $userData['username']; ?></span>
                <span class="font-semibold text-gray-200"><?php echo $userData['abbreviation'] . " | " .  $userData['role']; ?></span>
            </div>
        <?php } ?>
    </div>
    <ul class="p-2">
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('dashboard'); ?>">
            <a href="/tms/dashboard" class="flex py-2 pl-2.5 whitespace-nowrap">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-home text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Dashboard</span>
            </a>
        </li>
        <?php if ($permissions['account_management']['enabled']) { ?>
            <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('management'); ?>">
                <a href="/tms/management" class="flex py-2 pl-2.5 whitespace-nowrap">
                    <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-user text-lg text-gray-200"></i></div>
                    <span class="text-gray-200">Account Management</span>
                </a>
            </li>
        <?php } ?>
        <?php if ($permissions['tasks']['enabled']) { ?>
            <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('tasks'); ?>">
                <a href="/tms/tasks" class="flex py-2 pl-2.5 whitespace-nowrap">
                    <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-tasks text-lg text-gray-200"></i></div>
                    <span class="text-gray-200">Tasks</span>
                </a>
            </li>
        <?php } ?>
        <?php if ($permissions['distribute']['enabled']) { ?>
            <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('distribution'); ?>">
                <a href="/tms/distribution" class="flex py-2 pl-2.5 whitespace-nowrap">
                    <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-people-arrows text-lg text-gray-200"></i></div>
                    <span class="text-gray-200">Distribute Task</span>
                </a>
            </li>
        <?php } ?>
        <?php if ($permissions['report']) { ?>
            <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('report'); ?>">
                <a href="/tms/report" class="flex py-2 pl-2.5 whitespace-nowrap">
                    <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-chart-bar text-lg text-gray-200"></i></div>
                    <span class="text-gray-200">Report</span>
                </a>
            </li>
        <?php } ?>
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75">
            <div class="flex py-2 pl-2.5 whitespace-nowrap" onclick="openSettingsModal()">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fa-solid fa-gear text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Settings</span>
            </div>
        </li>
        <li class="flex py-2 pl-2.5 hover:bg-gray-600 whitespace-nowrap cursor-pointer rounded-md transition duration-75" onclick="openLogoutModal()">
            <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-sign-out-alt text-lg text-gray-200"></i></div>
            <span class="text-gray-200">Logout</span>
        </li>
    </ul>
</aside>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/settings_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/user_information.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/change_password_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/logout_modal.php'); ?>