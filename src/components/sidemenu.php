<?php
function isCurrentPage($link)
{
    $segments = explode('/', $_SERVER['PHP_SELF']);
    return ($segments[2] === $link) ? 'bg-gray-600' : '';
}

$userData = json_decode($_SESSION['user'], true);
?>
<div onclick="toggleSideMenu()" id="backdrop" class="fixed hidden top-0 left-0 w-full h-full bg-black opacity-50 z-50"></div>
<aside id="sideMenu" class="fixed hidden bottom-0 top-0 sm:relative sm:block w-64 bg-[#343A40] text-white z-50">
    <div class="flex items-center py-4 pl-4 border-b border-gray-600 whitespace-nowrap">
        <div class="min-w-9 flex justify-center items-center pr-1.5"><i class=" fas fa-user text-lg text-gray-200"></i></div>
        <span class="font-semibold text-gray-200"><?php echo $userData['username'] . " | " . $userData['abbreviation'] . " | " . $userData['role'] ?></span>
    </div>
    <ul class="p-2">
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('dashboard'); ?>">
            <a href="/dashboard" class="flex py-2 pl-2.5 whitespace-nowrap">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-home text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Dashboard</span>
            </a>
        </li>
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('management'); ?>">
            <a href="/management" class="flex py-2 pl-2.5 whitespace-nowrap">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-user text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Account Management</span>
            </a>
        </li>
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('tasks'); ?>">
            <a href="/tasks" class="flex py-2 pl-2.5 whitespace-nowrap">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-tasks text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Tasks</span>
            </a>
        </li>
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('distribution'); ?>">
            <a href="/distribution" class="flex py-2 pl-2.5 whitespace-nowrap">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-people-arrows text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Distribute Task</span>
            </a>
        </li>
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('performance'); ?>">
            <a href="/performance" class="flex py-2 pl-2.5 whitespace-nowrap">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-chart-line text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Employees Performance</span>
            </a>
        </li>
        <li class="hover:bg-gray-600 cursor-pointer rounded-md transition duration-75 <?php echo isCurrentPage('report'); ?>">
            <a href="/report" class="flex py-2 pl-2.5 whitespace-nowrap">
                <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-chart-bar text-lg text-gray-200"></i></div>
                <span class="text-gray-200">Report</span>
            </a>
        </li>
        <li class="flex py-2 pl-2.5 hover:bg-gray-600 whitespace-nowrap cursor-pointer rounded-md transition duration-75" onclick="openLogoutModal()">
            <div class="min-w-7 flex justify-center items-center mr-1.5"><i class="fas fa-sign-out-alt text-lg text-gray-200"></i></div>
            <span class="text-gray-200">Logout</span>
        </li>
    </ul>
</aside>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/components/modals/logout_modal.php'); ?>