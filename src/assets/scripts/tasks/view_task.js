const viewPreviewContainer = document.getElementById("viewFilePreview");
const viewTask = document.getElementById("viewTask");

function formatReadableDate(dateString) {
  const date = new Date(dateString);

  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
    hour12: true,
  };

  return date.toLocaleDateString("en-PH", options);
}

async function handleViewTask(taskId) {
  try {
    const response = await fetch(
      `https://tms-project.000webhostapp.com/api/viewtask?task_id=${taskId}`
    );
    const tasks = await response.json();

    if (tasks) {
      // Update modal elements with task data
      const titleElement = document.getElementById("taskTitle");
      const detailsElement = document.getElementById("taskDetailsContent");

      if (titleElement && detailsElement) {
        titleElement.innerHTML = tasks.title;
        detailsElement.innerHTML = tasks.detail;
        // Update other modal elements as needed
      }

      updateViewFilePreview(tasks.files);

      var task_status = document.getElementById("task_status");
      task_status.className = getStatusColor(tasks?.status);
      task_status.innerText = formatStatus(tasks?.status);

      var task_assigned = document.getElementById("task_assigned");
      task_assigned.className = "text-sm";
      task_assigned.innerText = tasks?.assigned;

      var due_date = document.getElementById("due_date");
      due_date.className = "text-sm";
      due_date.innerText =
        tasks?.dueAt === "Not Set"
          ? "Not Set"
          : formatReadableDate(tasks?.dueAt);

      var tabLinks = document.getElementsByClassName("tab");
      var tabContent = document.getElementsByClassName("tab-content");
      var defaultactive = document.getElementById("tab1");
      var underline = tabLinks[1].getElementsByTagName("div")[0];

      tabContent[1].style.display = "none";

      tabLinks[1].classList.remove("text-black", "relative", "font-semibold");
      tabLinks[1].classList.add("text-gray-500");
      underline.style.display = "none";

      defaultactive.style.display = "block";
      tabLinks[0].classList.remove("text-gray-500");
      tabLinks[0].classList.add("text-black", "relative", "font-semibold");
      tabLinks[0].getElementsByTagName("div")[0].style.display = "block";

      // Show the modal
      document.getElementById("viewTask").classList.remove("hidden");
    }
  } catch (error) {
    console.error("Error fetching task data:", error);
  }
}

function closeViewTaskModal() {
  viewPreviewContainer.innerHTML = "";
  document.getElementById("viewFilePreview").classList.add("hidden");
  document.getElementById("viewTask").classList.add("hidden");
}

function updateViewFilePreview(viewFiles) {
  // Clear existing preview
  viewPreviewContainer.innerHTML = "";

  // Create preview for each selected file
  for (const file of viewFiles) {
    const preview = createviewFilePreview(file);
    viewPreviewContainer.appendChild(preview);
  }

  if (viewFiles.length === 0) {
    // If no files, create a preview indicating no files uploaded
    const noFilePreview = document.createElement("div");
    noFilePreview.classList =
      "flex text-gray-600 items-center justify-center h-full";
    noFilePreview.textContent = "No files uploaded.";
    viewPreviewContainer.appendChild(noFilePreview);
  }

  document.getElementById("viewFilePreview").classList.remove("hidden");
}

function createviewFilePreview(file) {
  const preview = document.createElement("div");
  preview.className = "flex items-center border rounded-md p-2";

  const fileNameContainer = document.createElement("div");
  fileNameContainer.className = "flex flex-col w-full";

  const fileName = document.createElement("div");
  fileName.className = "w-full text-gray-600 truncate";
  fileName.textContent = file.filename;

  const fileDetailsContainer = document.createElement("div");
  fileDetailsContainer.className = "flex items-center justify-between";
  fileDetailsContainer.innerHTML = `<div class="text-gray-500">${formatFileSize(
    file.file_size
  )}</div><a class="text-blue-600" href="/download.php?file=${
    file.file_destination
  }">Download</a>`;

  fileNameContainer.appendChild(fileName);
  fileNameContainer.appendChild(fileDetailsContainer);
  preview.appendChild(fileNameContainer);

  return preview;
}

function formatFileSize(size) {
  const kbSize = size / 1024;
  if (kbSize < 1024) {
    return kbSize.toFixed(2) + " KB";
  } else {
    const mbSize = kbSize / 1024;
    if (mbSize < 1024) {
      return mbSize.toFixed(2) + " MB";
    } else {
      const gbSize = mbSize / 1024;
      return gbSize.toFixed(2) + " GB";
    }
  }
}

function openTab(evt, tabName) {
  var i, tabContent, tabLinks;
  tabContent = document.getElementsByClassName("tab-content");
  for (i = 0; i < tabContent.length; i++) {
    tabContent[i].style.display = "none";
  }
  tabLinks = document.getElementsByClassName("tab");
  for (i = 0; i < tabLinks.length; i++) {
    tabLinks[i].classList.remove("text-black", "relative", "font-semibold");
    tabLinks[i].classList.add("text-gray-500");
    var underline = tabLinks[i].getElementsByTagName("div")[0];
    underline.style.display = "none";
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.classList.remove("text-gray-500");
  evt.currentTarget.classList.add("text-black", "relative", "font-semibold");
  var underline = evt.currentTarget.getElementsByTagName("div")[0];
  underline.style.display = "block";
}

function getStatusColor(status) {
  switch (status.toUpperCase()) {
    case "DONE":
      return "bg-green-100 text-green-600 px-2 rounded text-sm"; // Green background for 'DONE'
    case "FAILED":
    case "REJECTED":
    case "LATE":
      return "bg-red-100 text-red-600 px-2 rounded text-sm"; // Red background for 'FAILED', 'REJECTED', 'LATE'
    case "PENDING":
      return "bg-yellow-100 text-yellow-600 px-2 rounded text-sm"; // Yellow background for 'PENDING'
    case "IN_REVIEW":
    case "IN_PROGRESS":
      return "bg-blue-100 text-blue-600 px-2 rounded text-sm"; // Blue background for 'IN REVIEW', 'IN PROGRESS'
    default:
      return "bg-gray-100 text-gray-600 px-2 rounded text-sm"; // Default gray background
  }
}

function formatStatus(status) {
  // Check if the status has an underscore character
  if (status.includes("_")) {
    // Remove the underscore character and capitalize the words
    return status
      .split("_")
      .map((word) => word.charAt(0) + word.slice(1))
      .join(" ");
  } else {
    return status;
  }
}
