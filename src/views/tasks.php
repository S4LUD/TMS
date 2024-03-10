<h1 class="text-xl font-bold mb-4">Tasks</h1>
<button id="openCreateTaskModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Create task</button>
<div class="bg-white p-4 mt-4 border rounded">
    <form id="searchForm" onsubmit="return false;" class="flex flex-col md:flex-row gap-1 select-none">
        <div class="flex flex-col">
            <label for="startDate">From Date:</label>
            <input type="date" id="startDate" name="startDate" class="border rounded-md py-2 px-4 focus:outline-none focus:border-blue-500 transition duration-75">
        </div>
        <div class="flex flex-col">
            <label for="endDate">To Date:</label>
            <input type="date" id="endDate" name="endDate" class="border rounded-md py-2 px-4 focus:outline-none focus:border-blue-500 transition duration-75">
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
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DATE CREATED</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="taskTable"></tbody>
            </table>
        </div>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/create_task_modal.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/view_task.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/edit_task.php'); ?>
<script src="/tms/src/assets/scripts/tasks/index.js"></script>
<script src="/tms/src/assets/scripts/tasks/create_task.js"></script>
<script src="/tms/src/assets/scripts/tasks/view_task.js"></script>
<script src="/tms/src/assets/scripts/tasks/edit_task.js"></script>
<script src="/tms/src/assets/scripts/tasks/delete_task.js"></script>