<?php $userData = json_decode($_SESSION['user'], true); ?>

<div id="createTask" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeCreateTaskModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-xl font-medium">Create Task</h2>
            <i onclick="closeCreateTaskModal()" class="h-fit fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <form id="createTaskForm" method="POST" onsubmit="submitNewTask(event)">
            <input type="text" value="<?php echo $userData['role']; ?>" name="role" id="role" hidden>
            <input type="text" value="<?php echo $userData['id']; ?>" name="userId" id="userId" hidden>
            <div class="mb-4">
                <label for="task_title" class="block text-gray-700">Title:</label>
                <input type="text" id="task_title" name="title" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
            </div>

            <div class="mb-4">
                <label for="task_details" class="block text-gray-700">Details:</label>
                <textarea id="task_details" name="details" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required></textarea>
            </div>

            <div id="fileUploadContainer" class="flex flex-col border-dashed border-2 border-gray-400 rounded-md" ondragover="handleDragOver(event)" ondrop="handleDrop(event)">
                <label for="fileInput" id="fileUploadText" class="text-gray-700 text-center p-4 cursor-pointer">
                    <p>Click to upload or drag & drop</p>
                </label>
                <input type="file" id="fileInput" onchange="handleFileSelection()" class="hidden" accept="image/*, video/mp4, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, text/plain" multiple />
            </div>
            <div id="filePreview" class="hidden flex gap-1 flex-row overflow-y-auto mt-1"></div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="submit" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Create</button>
            </div>
        </form>
    </div>
</div>