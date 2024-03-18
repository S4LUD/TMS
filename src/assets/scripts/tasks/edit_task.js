const fileEditInput = document.getElementById("fileEditInput");
const fileEditPreview = document.getElementById("fileEditPreview");
const fileDBEditPreview = document.getElementById("fileDBEditPreview");
const selectedEditFiles = [];

fileEditInput.addEventListener("change", () =>
  handleEditFileSelection(fileEditInput.files)
);
fileEditPreview.addEventListener("dragover", (e) => handleEditDragOver(e));
fileEditPreview.addEventListener("drop", (e) => handleEditDrop(e));

async function handleEditTask(taskId) {
  localStorage.setItem("taskId", taskId);
  try {
    const response = await fetch(
      `http://localhost/tms/api/viewtask?task_id=${taskId}`
    );
    const tasks = await response.json();

    if (tasks) {
      const titleElement = document.getElementById("edit_task_title");
      const detailsElement = document.getElementById("edit_task_details");

      if (titleElement && detailsElement) {
        titleElement.value = tasks.title;
        detailsElement.value = tasks.detail;
      }

      updateDBFilePreview(tasks.files);

      document.getElementById("editTask").classList.remove("hidden");
    }
  } catch (error) {
    console.error("Error fetching task data:", error);
  }
}

function closeEditTaskModal() {
  document.getElementById("editTask").classList.add("hidden");
  clearEditInputs();
}

function clearEditInputs() {
  fileEditPreview.innerHTML = "";
  fileDBEditPreview.innerHTML = "";
  selectedEditFiles.splice(0, selectedEditFiles.length);
  document.getElementById("edit_task_title").value = "";
  document.getElementById("edit_task_details").value = "";
  document.getElementById("fileEditPreviewContainer").classList.add("hidden");
  document.getElementById("fileDBEditPreviewContainer").classList.add("hidden");
  localStorage.removeItem("taskId");
}

function removeEditFile(file) {
  const index = selectedEditFiles.findIndex(
    (selectedFile) => selectedFile.name === file.name
  );
  if (index !== -1) {
    selectedEditFiles.splice(index, 1);
  }
}

function handleEditDragOver(event) {
  event.preventDefault();
  event.dataTransfer.dropEffect = "copy";
}

function handleEditDrop(event) {
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
          handleEditFileSelection([file]);
        } else {
          alert(`File type not supported: ${file.name}`);
        }
      }
    }
  }
}

function updateDBFilePreview(viewFiles) {
  // Clear existing preview
  fileDBEditPreview.innerHTML = "";

  // Create preview for each selected file
  for (const file of viewFiles) {
    const preview = createDBEditFilePreview(file);
    fileDBEditPreview.appendChild(preview);
  }

  if (viewFiles.length > 0) {
    document
      .getElementById("fileDBEditPreviewContainer")
      .classList.remove("hidden");
  }
}

async function onRefresh() {
  const taskId = localStorage.getItem("taskId");
  try {
    const response = await fetch(
      `http://localhost/tms/api/viewtask?task_id=${taskId}`
    );
    const tasks = await response.json();

    if (tasks) {
      const titleElement = document.getElementById("edit_task_title");
      const detailsElement = document.getElementById("edit_task_details");

      if (titleElement && detailsElement) {
        titleElement.value = tasks.title;
        detailsElement.value = tasks.detail;
      }

      updateDBFilePreview(tasks.files);

      if (!tasks.files.length) {
        fileDBEditPreview.innerHTML = "";
        document
          .getElementById("fileDBEditPreviewContainer")
          .classList.add("hidden");
      }
    }
  } catch (error) {
    console.error("Error fetching task data:", error);
  }
}

async function removeOnDB(file) {
  // Ask for confirmation using window.confirm
  const isConfirmed = window.confirm(
    `Are you sure you want to remove ${file.filename}?`
  );

  // Check user's confirmation
  if (isConfirmed) {
    await fetch(`http://localhost/tms/api/removefile?file_id=${file.file_id}`, {
      method: "GET",
      redirect: "follow",
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
      .catch((error) => console.error(error))
      .finally(() => onRefresh());
  } else {
    // User canceled, do nothing
    console.log("Removal canceled.");
  }
}

function createDBEditFilePreview(file) {
  const preview = document.createElement("div");
  preview.className = "flex items-center border rounded-md p-2";

  const fileNameContainer = document.createElement("div");
  fileNameContainer.className = "flex flex-col w-48";

  const fileName = document.createElement("div");
  fileName.className = "w-full text-gray-600 truncate";
  fileName.textContent = file.filename;

  const fileDetailsContainer = document.createElement("div");
  fileDetailsContainer.className = "flex items-center justify-between";

  const fileSizeElement = document.createElement("div");
  fileSizeElement.className = "text-gray-500";
  fileSizeElement.textContent = formatFileSize(file.file_size);

  const removeButton = document.createElement("div");
  removeButton.className = "cursor-pointer text-red-600";
  removeButton.textContent = "Remove";
  removeButton.addEventListener("click", () => removeOnDB(file));

  fileDetailsContainer.appendChild(fileSizeElement);
  fileDetailsContainer.appendChild(removeButton);

  fileNameContainer.appendChild(fileName);
  fileNameContainer.appendChild(fileDetailsContainer);
  preview.appendChild(fileNameContainer);

  return preview;
}

function handleEditFileSelection(files) {
  if (!files || files.length === 0) {
    return;
  }

  for (const file of files) {
    if (!isFileTypeAllowed(file.type)) {
      alert(`File type not supported: ${file.name}`);
      continue;
    }

    if (
      !selectedEditFiles.some((selectedFile) => selectedFile.name === file.name)
    ) {
      selectedEditFiles.push(file);
      updateEditFilePreview();
    }
  }
}

function updateEditFilePreview() {
  // Clear existing preview
  fileEditPreview.innerHTML = "";

  // Create preview for each selected file
  for (const file of selectedEditFiles) {
    const preview = createEditFilePreview(file);
    fileEditPreview.appendChild(preview);
  }

  toggleEditFilePreviewVisibility();
}

function toggleEditFilePreviewVisibility() {
  document
    .getElementById("fileEditPreviewContainer")
    .classList.remove("hidden");
}

function createEditFilePreview(file) {
  const preview = document.createElement("div");
  preview.className =
    "file-preview flex items-center border rounded-md p-2 relative";

  const fileEditPreview = document.createElement("div");
  fileEditPreview.className = "flex flex-col w-48";

  const fileName = document.createElement("div");
  fileName.className = "w-full text-gray-600 truncate";
  fileName.textContent = file.name;

  const fileSize = document.createElement("div");
  fileSize.className = "text-gray-500 flex flex=row justify-between";
  fileSize.innerHTML = `<div>
                          ${formatFileSize(file.size)}
                        </div>
                        <div class="remove-button text-red-500 hover:text-red-600 cursor-pointer pl-1">
                          Remove
                        </div>`;

  const removeButton = fileSize.querySelector(".remove-button");
  removeButton.addEventListener("click", () => {
    removeEditFile(file);
    updateEditFilePreview();
  });

  fileEditPreview.appendChild(fileName);
  fileEditPreview.appendChild(fileSize);
  preview.appendChild(fileEditPreview);

  if (file.size > 5 * 1024 * 1024) {
    fileName.classList = "w-full truncate text-red-500";
    fileSize.classList = "text-red-500 flex flex=row justify-between";
  }

  return preview;
}

async function submitEditTask(event) {
  event.preventDefault();

  // Access form fields
  const title = document.getElementById("edit_task_title").value;
  const details = document.getElementById("edit_task_details").value;
  const taskId = localStorage.getItem("taskId");

  // Check if the number of files exceeds the limit
  if (selectedEditFiles.length > 5) {
    alert("Please select up to 5 files.");
    return;
  }

  // Create FormData object
  const formData = new FormData();

  // Append form fields to FormData
  formData.append("title", title);
  formData.append("details", details);
  formData.append("taskId", taskId);

  let filesExceedSizeLimit = false;

  // Check file size and append files to FormData
  if (selectedEditFiles.length !== 0) {
    for (let i = 0; i < selectedEditFiles.length; i++) {
      const file = selectedEditFiles[i];

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
  await fetch("http://localhost/tms/api/updatetask/", {
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
        closeEditTaskModal();
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
    .catch((error) => {
      // Handle errors
      console.error("Error:", error);
    });
}
