<?php
$userData = json_decode($_SESSION['user'], true);
?>

<header class="bg-white p-4 border-b border-[#DFE3E7] flex justify-between">
    <button onclick="toggleSideMenu()" id="toggleSideMenu" class="sm:hidden focus:outline-none">
        <i class="fas fa-bars text-lg text-gray-600 hover:text-gray-800 transition duration-300"></i>
    </button>
    <div class="flex gap-4">
        <button id="toggleFullscreen" class="hidden sm:block ml-2 focus:outline-none">
            <i class="fas fa-expand text-lg text-gray-600 hover:text-gray-800 transition duration-300"></i>
        </button>
        <?php if ($userData['visibility'] === "PUBLIC") { ?>
            <button onclick="openNotificationModal()" class="focus:outline-none">
                <i class="fa-solid fa-bell text-lg text-blue-600 hover:text-blue-800 transition duration-300"></i>
            </button>
        <?php } ?>
    </div>
</header>