<div class="max-w-md mx-auto p-4 bg-white shadow-md rounded-lg mt-8">
    <h2 class="text-2xl font-bold mb-4">Report Task Filters</h2>
    <form onsubmit="GenerateReport(event)">
        <div class="mb-4">
            <label class="block mb-2">
                <span class="text-gray-700 font-bold">Date Range:</span>
                <div class="flex items-center gap-2">
                    <input required type="date" id="startDate" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
                    <span>to</span>
                    <input required type="date" id="endDate" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
                </div>
            </label>
            <label class="block mb-2">
                <span class="text-gray-700 font-bold">Status Filter:</span>
                <select id="statusFilter" class="bg-white w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
                </select>
            </label>
            <div id="userdropdownbackdrop" onclick="closeUserDropdown()" class="rounded absolute top-0 bottom-0 left-0 right-0 z-10 hidden"></div>
            <label class="block mb-2">
                <div class="flex flex-col">
                    <span class="text-gray-700 font-bold">User Filter:</span>
                    <span class="text-sm text-red-700">( you can leave this blank if you want to generate all tasks )</span>
                </div>
                <!-- <input onclick="openUserDropdown()" placeholder="Click to choose employee" type="text" id="assignTo" name="assignTo" class="cursor-pointer w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required onfocus="this.blur()"> -->
                <div onclick="openUserDropdown()" id="selectedUsersContainer" class="cursor-pointer w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" onfocus="this.blur()"></div>
                <div id="userdropdown" class="relative bg-red-500 hidden">
                    <div class="z-10 flex flex-col p-2 gap-2 absolute bg-white top-1 border rounded-md w-full">
                        <input type="text" id="searchUser" name="searchUser" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" placeholder="Select employee">
                        <div id="user_list_container" class="flex flex-col gap-2">
                        </div>
                    </div>
                </div>
            </label>
            <label class="block mb-2">
                <span class="text-gray-700 font-bold">Export to:</span>
                <select required id="exportTo" class="bg-white w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75">
                    <option value="">Select options</option>
                    <option value="excel">Excel</option>
                    <option value="pdf">PDF</option>
                    <!-- Add more options as needed -->
                </select>
            </label>
        </div>
        <div class="flex flex-row-reverse">
            <button id="generateReportBtn" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Generate Report</button>
        </div>
    </form>
</div>

<script src="/tms/src/assets/scripts/report/index.js"></script>