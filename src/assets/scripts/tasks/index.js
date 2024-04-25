const taskTable = document.getElementById("taskTable");
const filterButton = document.getElementById("filterButton");
const prevPageBtn = document.getElementById("prevPageBtn");
const nextPageBtn = document.getElementById("nextPageBtn");
const limitInput = document.getElementById("limitInput");
const taskCount = document.getElementById("taskCount");
let tasks = []; // Array to hold all tasks
let currentPage = 1; // Current page number
const itemsPerPage = 10; // Number of tasks per page

// Function to fetch tasks from the API
async function fetchTasks(startDate = "", endDate = "") {
  const userDetails = JSON.parse(localStorage.getItem("user"));
  const { id, role } = userDetails;

  let url = `${apiLink}/fetchalltasks`;

  // Construct query parameters
  const params = new URLSearchParams();

  params.append("startDate", startDate);
  params.append("endDate", endDate);
  params.append("role", role);
  params.append("userId", id);

  // Append query parameters to the URL
  url += "?" + params.toString();

  // Fetch tasks from the API
  const response = await fetch(url);

  // Check if the response is successful
  if (!response.ok) {
    throw new Error(`Failed to fetch tasks: ${response.statusText}`);
  }

  // Parse response data as JSON and return
  return response.json();
}

// Function to fetch tasks from the API with pagination
async function fetchTasksWithPagination(page) {
  const startIndex = (page - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const tasksOnPage = tasks.slice(startIndex, endIndex);
  return tasksOnPage;
}

// Function to update the table with tasks for the current page
async function updateTableForCurrentPage() {
  // Calculate the total number of pages after the deletion
  const totalPagesAfterDeletion = Math.ceil(tasks.length / itemsPerPage);

  // Check if the current page is beyond the total pages after deletion
  if (currentPage > totalPagesAfterDeletion) {
    // If so, decrement the current page to go back to the previous page
    currentPage--;
    limitInput.value = currentPage;
  }

  const tasksOnPage = await fetchTasksWithPagination(currentPage);
  updateTable(tasksOnPage);
}

// Function to format date
function formatDate(date) {
  const options = {
    weekday: "long",
    day: "numeric",
    month: "long",
  };
  return new Intl.DateTimeFormat("en-PH", options).format(date);
}

async function updateTable(tasks) {
  // Clear existing table content
  taskTable.innerHTML = "";

  if (tasks.length === 0) {
    const row = document.createElement("tr");
    row.innerHTML = `<td colspan="4" class="text-center py-3">No tasks found.</td>`;
    taskTable.appendChild(row);
    return;
  }

  for (const task of tasks) {
    try {
      const response = await fetch(`${apiLink}/viewtask?task_id=${task.id}`);
      if (!response.ok) {
        throw new Error(`Failed to fetch viewtask for task ${task.id}`);
      }
      const viewtask = await response.json();

      const userDetails = JSON.parse(localStorage.getItem("user"));
      const { visibility } = userDetails;

      const { tasks: taskPermissions } = JSON.parse(
        localStorage.getItem("permissions")
      );
      const { source } = taskPermissions;
      const formattedDate = formatDate(new Date(task.createdAt));

      const row = createTaskRow(
        task,
        viewtask,
        source,
        formattedDate,
        visibility
      );
      taskTable.appendChild(row);
    } catch (error) {
      console.error("Error updating table:", error.message);
    }
  }
}

function createTaskRow(task, viewtask, source, formattedDate, visibility) {
  const userDetails = JSON.parse(localStorage.getItem("user"));
  const { username, id } = userDetails;
  const assigneeString = task?.assigned_users || "";
  const isAssignedToMe = assigneeString.includes(username);
  const row = document.createElement("tr");
  row.innerHTML = `
    <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
      ${task.title}
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-gray-500">${formattedDate}</td>
    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
      ${
        (source.delete && viewtask.status === "IN_REVIEW") ||
        visibility !== "PUBLIC"
          ? '<span class="bg-red-500 hover:bg-red-600 text-white hover:text-gray-100 px-2 py-1 rounded delete-btn" style="cursor: pointer"><i class="fa-solid fa-trash-can"></i></span>'
          : ""
      }
      ${
        source.view
          ? '<span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded view-btn" style="cursor: pointer"><i class="fa-solid fa-eye"></i></span>'
          : ""
      }
      ${
        visibility !== "PUBLIC" ||
        isAssignedToMe ||
        (task?.createdBy === id && [4, 6].includes(tasks?.status_id))
          ? '<span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded edit-btn" style="cursor: pointer"><i class="fa-solid fa-pen-to-square"></i></span>'
          : ""
      }
    </td>
  `;

  // Get the buttons in the row
  let deleteBtn = row.querySelector(".delete-btn");
  let viewBtn = row.querySelector(".view-btn");
  let editBtn = row.querySelector(".edit-btn");

  // Add event listeners only if the buttons exist
  if (deleteBtn) {
    deleteBtn.addEventListener("click", () => handleDeleteTask(task.id));
  }

  if (viewBtn) {
    viewBtn.addEventListener("click", () => handleViewTask(task.id));
  }

  if (editBtn) {
    editBtn.addEventListener("click", () => handleEditTask(task.id));
  }

  return row;
}

// Function to handle filter button click
async function handleFilterButtonClick() {
  try {
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    tasks = await fetchTasks(startDate, endDate);
    currentPage = 1; // Reset to first page after filtering
    await updateTableForCurrentPage();
  } catch (error) {
    console.error("Error fetching tasks:", error.message);
  }
}

// Function to handle previous page button click
async function handlePrevPageButtonClick() {
  if (currentPage > 1) {
    currentPage--;
    limitInput.value = currentPage;
    await updateTableForCurrentPage();
  }
}

// Function to handle next page button click
async function handleNextPageButtonClick() {
  const totalPages = Math.ceil(tasks.length / itemsPerPage);
  if (currentPage < totalPages) {
    currentPage++;
    limitInput.value = currentPage;
    await updateTableForCurrentPage();
  }
}

// Attach event listeners
filterButton.addEventListener("click", handleFilterButtonClick);
prevPageBtn.addEventListener("click", handlePrevPageButtonClick);
nextPageBtn.addEventListener("click", handleNextPageButtonClick);

// Initial fetch of tasks when the page loads
document.addEventListener("DOMContentLoaded", async () => {
  try {
    // Fetch tasks without filtering initially
    tasks = await fetchTasks();
    taskCount.innerText = Math.ceil(tasks.length / itemsPerPage);
    await updateTableForCurrentPage();
  } catch (error) {
    console.error("Error fetching tasks:", error.message);
  }
});
