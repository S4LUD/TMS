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
  let url = `${apiLink}/fetchalltasks`;

  // Construct query parameters
  const params = new URLSearchParams();

  params.append("startDate", startDate);
  params.append("endDate", endDate);

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

// Function to fetch user data from the API
async function fetchUserData(userId) {
  const response = await fetch(
    `${apiLink}/fetchallusers?searchaccount=${userId}`
  );
  if (!response.ok) {
    throw new Error(`Failed to fetch user data: ${response.statusText}`);
  }
  const userData = await response.json();
  return userData[0].username; // Assuming username is needed
}

async function updateTable(tasks) {
  // Clear existing table content
  taskTable.innerHTML = "";

  if (tasks.length === 0) {
    // If no tasks found, display a message
    const row = document.createElement("tr");
    row.innerHTML = `<td colspan="4" class="text-center py-3">No tasks found.</td>`;
    taskTable.appendChild(row);
  } else {
    // Iterate over tasks and populate the table rows
    for (const task of tasks) {
      const dateCreated = formatDate(new Date(task.createdAt));
      let userData = "N/A"; // Default value if user_id is null or undefined
      let dueDate = "N/A";
      let startedAt = "N/A";
      let endedAt = "N/A";
      if (task?.dueAt) {
        dueDate = formatDate(new Date(task?.dueAt));
      }
      if (task?.startedAt) {
        startedAt = formatDate(new Date(task?.startedAt));
      }
      if (task?.endedAt) {
        endedAt = formatDate(new Date(task?.endedAt));
      }
      if (task.user_id) {
        userData = await fetchUserData(task.user_id);
      }
      const { distribute: distributePermissions } = JSON.parse(
        localStorage.getItem("permissions")
      );
      const { source } = distributePermissions;

      const row = document.createElement("tr");
      row.innerHTML = `
                <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">${
                  task.title
                }</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${userData}</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${
                  task?.task_type ? task?.task_type : "N/A"
                }</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${dateCreated}</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${dueDate}</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${startedAt}</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${endedAt}</td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
                    ${
                      source.assign
                        ? '<span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded assign-btn" style="cursor: pointer"><i class="fas fa-people-arrows"></i></span>'
                        : ""
                    }
                </td>
            `;
      taskTable.appendChild(row);

      let assignBtn = row.querySelector(".assign-btn");

      // Add event listeners only if the buttons exist
      if (assignBtn) {
        assignBtn.addEventListener("click", () => openDistribute(task.id));
      }
    }
  }
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
