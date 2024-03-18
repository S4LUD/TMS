<div id="distributeTask" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50 hidden" onclick="closeDistribute()">
    <div class="bg-white p-4 rounded shadow-md w-96 relative" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">Distribute Task</h2>
            <i onclick="closeDistribute()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <form>
            <div class="mb-4">
                <label for="taskType" class="block text-gray-700">
                    Task Type:
                </label>
                <select id="taskType" name="taskType" class="w-full bg-white border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
                    <option value="">--- Select Task Type ---</option>
                    <option value="ACADEMIC">ACADEMIC</option>
                    <option value="NON ACADEMIC">NON ACADEMIC</option>
                </select>
            </div>
            <div id="userdropdownbackdrop" onclick="closeUserDropdown()" class="rounded absolute top-0 bottom-0 left-0 right-0 z-10 hidden"></div>
            <div class="mb-4">
                <label for="assignTo" class="block text-gray-700">
                    Assign To:
                </label>
                <input onclick="openUserDropdown()" placeholder="Click to choose employee" type="text" id="assignTo" name="assignTo" class="cursor-pointer w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
                <div id="userdropdown" class="relative bg-red-500 hidden"> <!-- This should be hidden -->
                    <div class="z-10 flex flex-col p-2 gap-2 absolute bg-white top-1 border rounded-md w-full">
                        <input type="text" id="searchUser" name="searchUser" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" placeholder="Select employee">
                        <div id="user_list_container" class="flex flex-col gap-2">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="dueDate" class="block text-gray-700">
                    Due Date:
                </label>
                <input type="datetime-local" id="dueDate" name="dueDate" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" id="cancelButton" class="font-semibold bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50" onclick="closeDistribute()">Cancel</button>
                <button type="submit" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Submit</button>
            </div>
        </form>
    </div>
</div>