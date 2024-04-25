<div id="editTask" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeEditTaskModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-4 flex justify-between">
            <h2 class="text-md font-medium text-gray-500">Edit Task</h2>
            <i onclick="closeEditTaskModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <form id="createTaskForm" method="POST" onsubmit="submitEditTask(event)">
            <div class="mb-4">
                <label for="edit_task_title" class="block text-gray-700">Title:</label>
                <input type="text" id="edit_task_title" name="title" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required>
            </div>

            <div class="mb-4">
                <label for="edit_task_details" class="block text-gray-700">Details:</label>
                <textarea id="edit_task_details" name="details" class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75" required></textarea>
            </div>

            <div id="editfileUploadContainer" class="flex flex-col border-dashed border-2 border-gray-400 rounded-md" ondragover="handleEditDragOver(event)" ondrop="handleEditDrop(event)">
                <label for="fileEditInput" id="fileUploadText" class="text-gray-700 text-center p-4 cursor-pointer">
                    <p>Click to upload or drag & drop</p>
                </label>
                <input type="file" id="fileEditInput" onchange="handleEditFileSelection()" class="hidden" accept="image/*, video/mp4, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, text/plain" multiple />
            </div>
            <div id="fileEditPreviewContainer" class="mt-4 hidden">
                <div>
                    New Files
                </div>
                <div id="fileEditPreview" class="flex gap-1 flex-row overflow-y-auto mt-1"></div>
            </div>
            <div id="fileDBEditPreviewContainer" class="mt-4 hidden">
                <div>
                    Uploaded Files
                </div>
                <div id="fileDBEditPreview" class="flex gap-1 flex-row overflow-y-auto mt-1"></div>
            </div>

            <div class="mt-4 flex justify-between">
                <div>
                    <button onclick="handleSubmitTask(event)" id="hiding-this-submit-task" class="font-semibold bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50 hidden">Submit Task</button>
                </div>
                <div class="flex justify-end gap-1">
                    <button onclick="handleTaskActionStatus(event)" id="hiding-this-btn" class="font-semibold bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Begin Task</button>
                    <button type="submit" id="submit_edit_task" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>