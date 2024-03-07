<div id="viewTask" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeViewTaskModal()">
    <div class="bg-white p-4 rounded shadow-md w-11/12 sm:w-9/12 md:w-6/12 lg:w-5/12" onclick="event.stopPropagation();">
        <div class="mb-1">
            <p id="taskTitle" class="text-2xl font-medium"></p>
        </div>
        <div class="mb-4">
            <p id="taskDetailsContent" class="text-gray-800"></p>
        </div>
        <div id="viewFilePreview" class="hidden flex flex-col gap-2 mt-4"></div>
        <div class="mt-4">
            <button type="button" onclick="closeViewTaskModal()" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Close</button>
        </div>
    </div>
</div>