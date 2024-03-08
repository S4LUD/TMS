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
            <button type="button" id="clearButton" class="h-fit hidden font-semibold bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50 sm:self-end">Clear</button>
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

<script>
    const taskTable = document.getElementById('taskTable');
    const fileInput = document.getElementById('fileInput');
    const filePreviewContainer = document.getElementById('filePreview');
    const filterButton = document.getElementById('filterButton');
    const clearButton = document.getElementById('clearButton');
    const selectedFiles = [];

    filterButton.addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // clearButton.classList.remove('hidden');
        fetch(`http://localhost/tms/api/fetchalltasks?startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(users => updateTable(users));

    });

    function fetchTasks(params) {
        fetch(`http://localhost/tms/api/fetchalltasks`)
            .then(response => response.json())
            .then(tasks => updateTable(tasks));
    }

    fetchTasks();

    function updateTable(tasks) {
        // Clear existing table content
        taskTable.innerHTML = '';

        if (tasks.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="py-3.5 pl-6 pr-3 text-left font-semibold text-red-900">NO TASK FOUND BETWEEN THE RANGED DATE</td>
            `;
            taskTable.appendChild(row);
        } else {
            for (const task of tasks) {
                const dateTime = new Date(task.createdAt);
                const options = {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                };
                const formattedDate = new Intl.DateTimeFormat('en-PH', options).format(dateTime);
                const row = document.createElement('tr');
                row.id = `userRow_${task.id}`;
                row.innerHTML = `
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                        ${task.title}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">${formattedDate}</td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
                        <span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded view-btn" style="cursor: pointer">VIEW</span>
                        <span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded edit-btn" style="cursor: pointer">EDIT</span>
                    </td>
                `;
                taskTable.appendChild(row);


                // Add event listeners to VIEW and EDIT buttons
                row.querySelector('.view-btn').addEventListener('click', () => handleViewTask(task.id));
                row.querySelector('.edit-btn').addEventListener('click', () => handleEditTask(task.id));
            }
        }
    }

    function handleViewTask(taskId) {
        console.log('View task:', taskId);
        // Add your logic to handle viewing the task
    }

    function handleEditTask(taskId) {
        console.log('Edit task:', taskId);
        // Add your logic to handle editing the task
    }

    fileInput.addEventListener('change', () => handleFileSelection(fileInput.files));
    filePreviewContainer.addEventListener('dragover', (e) => handleDragOver(e));
    filePreviewContainer.addEventListener('drop', (e) => handleDrop(e));

    function handleFileSelection(files) {
        if (!files || files.length === 0) {
            return;
        }

        for (const file of files) {
            const allowedTypes = ['image/jpeg', 'image/png', 'video/mp4', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            if (!isFileTypeAllowed(file.type)) {
                alert(`File type not supported: ${file.name}`);
                continue;
            }

            // Check if the file is not already in the selectedFiles array
            if (!selectedFiles.some(selectedFile => selectedFile.name === file.name)) {
                selectedFiles.push(file);
                updateFilePreview();
            }
        }
    }

    function updateFilePreview() {
        // Clear existing preview
        filePreviewContainer.innerHTML = '';

        // Create preview for each selected file
        for (const file of selectedFiles) {
            const preview = createFilePreview(file);
            filePreviewContainer.appendChild(preview);
        }

        toggleFilePreviewVisibility();
    }

    function toggleFilePreviewVisibility() {
        const hasFiles = selectedFiles.length > 0;
        filePreviewContainer.classList.toggle('hidden', !hasFiles);
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'copy';
    }

    function isFileTypeAllowed(fileType) {
        // Define the allowed file types
        const allowedTypes = ['image/jpeg', 'image/png', 'video/mp4', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain'];

        // Check if the given file type is in the allowed types array
        return allowedTypes.includes(fileType);
    }

    function handleDrop(event) {
        event.preventDefault();

        // Check if DataTransfer items are available
        if (event.dataTransfer.items) {
            // Use DataTransfer items to get dropped items
            const droppedItems = event.dataTransfer.items;

            // Check each dropped item for file type
            for (let i = 0; i < droppedItems.length; i++) {
                const droppedItem = droppedItems[i];

                // Check if the item is a file
                if (droppedItem.kind === 'file') {
                    // Get the dropped file
                    const file = droppedItem.getAsFile();

                    // Check if the file type is allowed
                    if (isFileTypeAllowed(file.type)) {
                        // Handle the allowed file
                        handleFileSelection([file]);
                    } else {
                        alert(`File type not supported: ${file.name}`);
                    }
                }
            }
        }
    }

    function createFilePreview(file) {
        const preview = document.createElement('div');
        preview.className = 'file-preview flex items-center border rounded-md p-2 relative';

        const fileNameContainer = document.createElement('div');
        fileNameContainer.className = 'flex flex-col w-40';

        const fileName = document.createElement('div');
        fileName.className = 'max-w-36 truncate';
        fileName.textContent = file.name;

        const fileSize = document.createElement('div');
        fileSize.className = 'text-gray-500';
        fileSize.textContent = formatFileSize(file.size);

        const removeButton = document.createElement('button');
        removeButton.className = 'remove-button text-gray-600 hover:text-red-600 cursor-pointer pl-1';
        removeButton.innerHTML = '<i class="fas fa-times"></i>';
        removeButton.addEventListener('click', () => {
            removeFile(file);
            updateFilePreview();
        });

        fileNameContainer.appendChild(fileName);
        fileNameContainer.appendChild(fileSize);
        preview.appendChild(fileNameContainer);
        preview.appendChild(removeButton);

        // Change text color to red if file size is over 5MB
        if (file.size > 5 * 1024 * 1024) {
            fileName.classList = 'max-w-36 truncate text-red-500';
            fileSize.classList = 'text-red-500';
            removeButton.classList = 'remove-button text-red-500 hover:text-red-600 cursor-pointer pl-1';
        }

        return preview;
    }

    function removeFile(file) {
        // Remove the file from the selectedFiles array
        const index = selectedFiles.findIndex(selectedFile => selectedFile.name === file.name);
        if (index !== -1) {
            selectedFiles.splice(index, 1);
        }
    }

    function formatFileSize(size) {
        const kbSize = size / 1024;
        if (kbSize < 1024) {
            return kbSize.toFixed(2) + ' KB';
        } else {
            const mbSize = kbSize / 1024;
            if (mbSize < 1024) {
                return mbSize.toFixed(2) + ' MB';
            } else {
                const gbSize = mbSize / 1024;
                return gbSize.toFixed(2) + ' GB';
            }
        }
    }

    function clearCreateInputs() {
        filePreviewContainer.innerHTML = '';
        selectedFiles.splice(0, selectedFiles.length);
        document.getElementById('task_title').value = "";
        document.getElementById('task_details').value = "";
    }

    document.getElementById('openCreateTaskModal').addEventListener('click', openCreateTaskModal);

    function openCreateTaskModal() {
        document.getElementById('createTask').classList.remove('hidden');
    }

    function closeCreateTaskModal() {
        document.getElementById('createTask').classList.add('hidden');
        clearCreateInputs();
    }

    async function submitNewTask(event) {
        event.preventDefault();

        // Access form fields
        const title = document.getElementById('task_title').value;
        const details = document.getElementById('task_details').value;

        // Check if the number of files exceeds the limit
        if (selectedFiles.length > 5) {
            alert('Please select up to 5 files.');
            return;
        }

        // Create FormData object
        const formData = new FormData();

        // Append form fields to FormData
        formData.append('title', title);
        formData.append('details', details);

        let filesExceedSizeLimit = false;

        // Check file size and append files to FormData
        if (selectedFiles.length !== 0) {
            for (let i = 0; i < selectedFiles.length; i++) {
                const file = selectedFiles[i];

                // Check if file size is less than 5MB
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size should be less than 5MB.');
                    filesExceedSizeLimit = true;
                    break; // Exit the loop
                }

                formData.append('files[]', file);
            }
        }

        if (filesExceedSizeLimit) {
            return; // Don't proceed with the API request
        }

        // Perform API request using fetch
        await fetch('http://localhost/tms/api/createtask/', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                if (result.message) {
                    Toastify({
                        text: result.message,
                        duration: 5000,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "#3CA2FA",
                        },
                    }).showToast();
                    closeCreateTaskModal();
                    fetchTasks();
                } else if (result.error) {
                    Toastify({
                        text: result.error,
                        duration: 5000,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "#FA3636",
                        },
                    }).showToast();
                }
            })
            .catch(error => {
                // Handle errors
                console.error('Error:', error);
            });
    }
</script>