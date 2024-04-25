<div id="notification_modal" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50 hidden" onclick="closeNotificationModal()">
    <div class="bg-white p-4 rounded shadow-md sm:w-6/12 w-11/12 h-3/4 overflow-y-scroll" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">Today's Task Summary</h2>
            <i onclick="closeNotificationModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>

        <div class="min-w-full divide-y divide-gray-300">
            <div class="overflow-x-auto mb-2">
                <h3 class="text-md font-medium text-blue-500">Current Tasks</h3>
                <table id="all_tasks_table" class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900 w-1/4">TITLE</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">ASSIGNED TO</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">TASK TYPE</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DUE DATE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="all_tasks_body"></tbody>
                </table>
            </div>

            <div class="overflow-x-auto mb-2 pt-6">
                <h3 class="text-md font-medium text-orange-500">Near Deadline Tasks</h3>
                <table id="ending_due_tasks_table" class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900 w-1/4">TITLE</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">ASSIGNED TO</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">TASK TYPE</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DUE DATE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="ending_due_tasks_body"></tbody>
                </table>
            </div>

            <div class="overflow-x-auto mb-2 pt-6">
                <h3 class="text-md font-medium text-red-500">Past Due Tasks</h3>
                <table id="overdue_tasks_table" class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900 w-1/4">TITLE</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">ASSIGNED TO</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">TASK TYPE</th>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DUE DATE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="overdue_tasks_body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>