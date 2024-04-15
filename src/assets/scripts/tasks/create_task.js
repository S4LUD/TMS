const fileInput = document.getElementById("fileInput");
const filePreviewContainer = document.getElementById("filePreview");
const selectedFiles = [];

// Check if the element with ID "openCreateTaskModal" exists before adding the event listener
let openCreateTaskModalButton = document.getElementById("openCreateTaskModal");
if (openCreateTaskModalButton) {
  openCreateTaskModalButton.addEventListener("click", openCreateTaskModal);
}

fileInput.addEventListener("change", () =>
  handleFileSelection(fileInput.files)
);
filePreviewContainer.addEventListener("dragover", (e) => handleDragOver(e));
filePreviewContainer.addEventListener("drop", (e) => handleDrop(e));

async function openCreateTaskModal() {
  const taskDepartmentSelect = document.getElementById("taskDepartment");

  // Fetch departments from the API
  try {
    if (taskDepartmentSelect) {
      const response = await fetch(`${apiLink}/fetchdepartments`, {
        method: "GET",
        headers: { "Content-Type": "application/json" },
        redirect: "follow",
      });
      const departments = await response.json();

      // Clear any existing options
      taskDepartmentSelect.innerHTML =
        '<option value="">--- Select Task Department ---</option>';

      // Populate the select with department options, skipping departments with super true
      departments.forEach((department) => {
        if (!department.super) {
          const option = document.createElement("option");
          option.value = department.id;
          option.textContent = department.department;
          taskDepartmentSelect.appendChild(option);
        }
      });
    }

    // Show the create task modal
    document.getElementById("createTask").classList.remove("hidden");
  } catch (error) {
    console.error(error);
  }
}

function closeCreateTaskModal() {
  document.getElementById("createTask").classList.add("hidden");
  clearCreateInputs();
}

function clearCreateInputs() {
  filePreviewContainer.innerHTML = "";
  selectedFiles.splice(0, selectedFiles.length);
  document.getElementById("task_title").value = "";
  document.getElementById("task_details").value = "";
}

async function submitNewTask(event) {
  event.preventDefault();

  // Access form fields
  const title = document.getElementById("task_title").value;
  const details = document.getElementById("task_details").value;
  const role = document.getElementById("role").value;
  const userId = document.getElementById("userId").value;
  let taskDepartment = "";

  if (role === "SUPER ADMIN") {
    taskDepartment = document.getElementById("taskDepartment").value;
  }

  // Check if the number of files exceeds the limit
  if (selectedFiles.length > 5) {
    alert("Please select up to 5 files.");
    return;
  }

  // Create FormData object
  const formData = new FormData();

  // Append form fields to FormData
  formData.append("title", title);
  formData.append("details", details);
  formData.append("role", role);
  formData.append("createdBy", userId);

  if (role === "SUPER ADMIN") {
    formData.append("department_id", taskDepartment);
  }

  let filesExceedSizeLimit = false;

  // Check file size and append files to FormData
  if (selectedFiles.length !== 0) {
    for (let i = 0; i < selectedFiles.length; i++) {
      const file = selectedFiles[i];

      // Check if file size is less than 5MB
      if (file.size > 5 * 1024 * 1024) {
        alert("File size should be less than 5MB.");
        filesExceedSizeLimit = true;
        break; // Exit the loop
      }

      formData.append("files[]", file);
    }
  }

  if (filesExceedSizeLimit) {
    return; // Don't proceed with the API request
  }

  // Perform API request using fetch
  await fetch(`${apiLink}/createtask/`, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
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
    .catch((error) => {
      // Handle errors
      console.error("Error:", error);
    })
    .finally(async () => {
      tasks = await fetchTasks();
      taskCount.innerText = Math.ceil(tasks.length / itemsPerPage);
      await updateTableForCurrentPage();
      closeCreateTaskModal();
    });
}

function removeFile(file) {
  // Remove the file from the selectedFiles array
  const index = selectedFiles.findIndex(
    (selectedFile) => selectedFile.name === file.name
  );
  if (index !== -1) {
    selectedFiles.splice(index, 1);
  }
}

function createFilePreview(file) {
  const preview = document.createElement("div");
  preview.className =
    "file-preview flex items-center border rounded-md p-2 relative";

  const fileNameContainer = document.createElement("div");
  fileNameContainer.className = "flex flex-col w-40";

  const fileName = document.createElement("div");
  fileName.className = "max-w-36 truncate";
  fileName.textContent = file.name;

  const fileSize = document.createElement("div");
  fileSize.className = "text-gray-500";
  fileSize.textContent = formatFileSize(file.size);

  const removeButton = document.createElement("button");
  removeButton.className =
    "remove-button text-gray-600 hover:text-red-600 cursor-pointer pl-1";
  removeButton.innerHTML = '<i class="fas fa-times"></i>';
  removeButton.addEventListener("click", () => {
    removeFile(file);
    updateFilePreview();
  });

  fileNameContainer.appendChild(fileName);
  fileNameContainer.appendChild(fileSize);
  preview.appendChild(fileNameContainer);
  preview.appendChild(removeButton);

  // Change text color to red if file size is over 5MB
  if (file.size > 5 * 1024 * 1024) {
    fileName.classList = "max-w-36 truncate text-red-500";
    fileSize.classList = "text-red-500";
    removeButton.classList =
      "remove-button text-red-500 hover:text-red-600 cursor-pointer pl-1";
  }

  return preview;
}

function handleDragOver(event) {
  event.preventDefault();
  event.dataTransfer.dropEffect = "copy";
}

function isFileTypeAllowed(fileType) {
  // Define the allowed file types
  const allowedTypes = [
    "image/jpeg",
    "image/png",
    "video/mp4",
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    "application/vnd.ms-excel",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    "text/plain",
  ];

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
      if (droppedItem.kind === "file") {
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

function toggleFilePreviewVisibility() {
  const hasFiles = selectedFiles.length > 0;
  filePreviewContainer.classList.toggle("hidden", !hasFiles);
}

function updateFilePreview() {
  // Clear existing preview
  filePreviewContainer.innerHTML = "";

  // Create preview for each selected file
  for (const file of selectedFiles) {
    const preview = createFilePreview(file);
    filePreviewContainer.appendChild(preview);
  }

  toggleFilePreviewVisibility();
}

function handleFileSelection(files) {
  if (!files || files.length === 0) {
    return;
  }

  for (const file of files) {
    if (!isFileTypeAllowed(file.type)) {
      alert(`File type not supported: ${file.name}`);
      continue;
    }

    // Check if the file is not already in the selectedFiles array
    if (
      !selectedFiles.some((selectedFile) => selectedFile.name === file.name)
    ) {
      selectedFiles.push(file);
      updateFilePreview();
    }
  }
}
