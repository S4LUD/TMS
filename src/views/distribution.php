<?php
// Set the timezone to your desired timezone
date_default_timezone_set('Asia/Manila');

// Get the first day of the current month
$firstDayOfMonth = new DateTime('first day of this month');

// Get the last day of the current month
$lastDayOfMonth = new DateTime('last day of this month');

// Format the dates as required (Y-m-d)
$firstDayOfMonthFormatted = $firstDayOfMonth->format('Y-m-d');
$lastDayOfMonthFormatted = $lastDayOfMonth->format('Y-m-d');
?>

<div class="bg-white p-4 mt-4 border rounded">
    <form id="searchForm" onsubmit="return false;" class="flex flex-col md:flex-row gap-1 select-none">
        <div class="flex flex-col">
            <label for="startDate">From Date:</label>
            <input value="<?= $firstDayOfMonthFormatted ?>" type="date" id="startDate" name="startDate" class="border rounded-md py-2 px-4 focus:outline-none focus:border-blue-500 transition duration-75">
        </div>
        <div class="flex flex-col">
            <label for="endDate">To Date:</label>
            <input value="<?= $lastDayOfMonthFormatted ?>" type="date" id="endDate" name="endDate" class="border rounded-md py-2 px-4 focus:outline-none focus:border-blue-500 transition duration-75">
        </div>
        <div class="flex flex-col gap-1 sm:flex-row justify-end">
            <button type="button" id="filterButton" class="h-fit font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50 sm:self-end">Filter</button>
        </div>
    </form>
    <div class="overflow-x-auto mt-0 sm:mt-4 select-none">
        <div class="md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">TITLE</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">ASSIGNED TO</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">TASK TYPE</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DATE CREATED</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DUE DATE</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="taskTable"></tbody>
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
                <span id="taskCount" class="font-medium"></span>
            </div>
        </div>
        <div class="flex justify-end">
            <button id="nextPageBtn" class="py-2 px-4">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/components/modals/distribute_task_modal.php'); ?>
<script src="/src/assets/scripts/distribution/index.js"></script>
<script src="/src/assets/scripts/distribution/assign_task.js"></script>