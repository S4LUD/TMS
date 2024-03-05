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
                <tbody class="divide-y divide-gray-200" id="userTable">
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">Lorem ipsum dolor sit amet consectetur adipisicing elit.</td>
                        <td class="whitespace-nowrap px-3 py-4 text-gray-500">03/03/2024</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
                            <span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded" style="cursor: pointer">VIEW</span>
                            <span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded" style="cursor: pointer">EDIT</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/create_task_modal.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const createTaskForm = document.getElementById('createTaskForm');
        const clearButton = document.getElementById('clearButton');

        createTaskForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const title = document.getElementById('task_title').value;
            const details = document.getElementById('task_details').value;

            console.log({
                title,
                details
            });

            // const url = `http://localhost/tms/api/register?username=${username}&password=${password}&department_id=${departmentId}&role_id=${roleId}`;

            // console.log(url);

            // fetch(url)
            //     .then(response => response.json())
            //     .then(result => {
            //         if (result.message) {
            //             Toastify({
            //                 text: result.message,
            //                 duration: 5000,
            //                 gravity: "top", // `top` or `bottom`
            //                 position: "right", // `left`, `center` or `right`
            //                 stopOnFocus: true, // Prevents dismissing of toast on hover
            //                 style: {
            //                     background: "#3CA2FA",
            //                 },
            //             }).showToast();
            //             closeCreateUserModal();
            //             clearCreateInputs();
            //             fetchUsers();
            //         } else if (result.error) {
            //             Toastify({
            //                 text: result.error,
            //                 duration: 5000,
            //                 gravity: "top", // `top` or `bottom`
            //                 position: "right", // `left`, `center` or `right`
            //                 stopOnFocus: true, // Prevents dismissing of toast on hover
            //                 style: {
            //                     background: "#FA3636",
            //                 },
            //             }).showToast();
            //         }
            //     })
            //     .catch(error => {
            //         console.error('Error:', error);
            //     });
        });
    });

    const fileInput = document.getElementById('fileInput');
    const filePreviewContainer = document.getElementById('filePreview');

    fileInput.addEventListener('change', () => handleFileSelection(fileInput.files));
    filePreviewContainer.addEventListener('dragover', (e) => handleDragOver(e));
    filePreviewContainer.addEventListener('drop', (e) => handleDrop(e));

    function handleFileSelection(files) {
        if (!files || files.length === 0) {
            return;
        }

        for (const file of files) {
            const allowedTypes = ['image/jpeg', 'image/png', 'video/mp4', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            if (!allowedTypes.includes(file.type)) {
                alert(`File type not supported: ${file.name}`);
                continue;
            }

            const preview = createFilePreview(file);
            filePreviewContainer.appendChild(preview);
        }
        toggleFilePreviewVisibility();
    }

    function toggleFilePreviewVisibility() {
        const hasFiles = filePreviewContainer.children.length > 0;
        filePreviewContainer.classList.toggle('hidden', !hasFiles);
    }

    function createFilePreview(file) {
        const preview = document.createElement('div');
        preview.className = 'file-preview flex items-center border rounded-md p-2 relative';

        const fileNameContainer = document.createElement('div');
        fileNameContainer.className = 'w-40';

        const fileName = document.createElement('div');
        fileName.className = 'max-w-36 truncate';
        fileName.textContent = file.name;

        const removeButton = document.createElement('button');
        removeButton.className = 'remove-button text-gray-600 hover:text-red-600 cursor-pointer pl-1';
        removeButton.innerHTML = '<i class="fas fa-times"></i>';
        removeButton.addEventListener('click', () => {
            filePreviewContainer.removeChild(preview);
            toggleFilePreviewVisibility();
        });

        fileNameContainer.appendChild(fileName);
        preview.appendChild(fileNameContainer);
        preview.appendChild(removeButton);

        return preview;
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'copy';
    }

    function handleDrop(event) {
        event.preventDefault();
        handleFileSelection(event.dataTransfer.files);
    }

    function clearCreateInputs() {
        filePreviewContainer.innerHTML = '';
        fileInput.value = null;
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
        const fileInput = document.getElementById('fileInput');
        const files = fileInput.files;

        // Check if the number of files exceeds the limit
        if (files.length > 5) {
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
        if (files.length !== 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

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
                closeCreateTaskModal();
            })
            .catch(error => {
                // Handle errors
                console.error('Error:', error);
            });
    }
</script>