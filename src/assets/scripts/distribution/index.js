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
    // If no tasks found, display a message
    const row = document.createElement("tr");
    row.innerHTML = `<td colspan="4" class="text-center py-3">No tasks found.</td>`;
    taskTable.appendChild(row);
  } else {
    // Iterate over tasks and populate the table rows
    for (const task of tasks) {
      const dateCreated = formatDate(new Date(task.createdAt));
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
      const { distribute: distributePermissions } = JSON.parse(
        localStorage.getItem("permissions")
      );
      const { source } = distributePermissions;

      const row = document.createElement("tr");
      
      row.innerHTML = `
                <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">${
                  task.title
                }</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${
                  task?.assigned_users ? task?.assigned_users : "N/A"
                }</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${
                  task?.task_type ? task?.task_type : "N/A"
                }</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${dateCreated}</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${dueDate}</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${startedAt}</td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${endedAt}</td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
                  ${
                    source?.assign &&
                    !!!task?.assigned_users &&
                    ![1, 2, 3, 5, 7].includes(task?.status_id)
                      ? '<span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded assign-btn" style="cursor: pointer"><i class="fas fa-people-arrows"></i></span>'
                      : ""
                  }
                  ${
                    source?.view
                      ? '<span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded view-btn" style="cursor: pointer"><i class="fa-solid fa-eye"></i></span>'
                      : ""
                  }
                  ${
                    task?.assigned_users &&
                    task?.status_id !== 6 &&
                    ![1, 2, 3, 5, 7].includes(task?.status_id)
                      ? '<span class="bg-red-500 hover:bg-red-600 text-white hover:text-gray-100 px-2 py-1 rounded unassign-btn" style="cursor: pointer"><i class="fas fa-people-arrows"></i></span>'
                      : ""
                  }
                  ${
                    source?.action_status &&
                    ![1, 2, 3, 5, 7].includes(task?.status_id)
                      ? `<div class="relative inline-block group overflow-visible">
                      <span
                          class="bg-green-500 hover:bg-green-600 text-white hover:text-gray-100 px-2 py-1 rounded cursor-pointer"
                      >
                          <i class="fa-solid fa-wrench"></i>
                      </span>
                  
                      <!-- Dropdown Menu -->
                      <div
                          class="absolute hidden bg-white rounded-lg shadow-lg mt-2 right-0 bottom-0 origin-top-right z-10 group-hover:block"
                      >
                          <!-- Menu Items -->
                          <div class="flex flex-col p-2 gap-1">
                              <button class="done-btn rounded block w-full text-white text-left px-4 py-2 hover:bg-green-600 bg-green-500">DONE</button>
                              <button class="fail-btn rounded block w-full text-white text-left px-4 py-2 hover:bg-red-600 bg-red-500">FAIL</button>
                              <button class="reject-btn rounded block w-full text-white text-left px-4 py-2 hover:bg-red-700 bg-red-600">REJECT</button>
                          </div>
                      </div>
                  </div>`
                      : ""
                  }               
              </td>
            `;
      taskTable.appendChild(row);

      let assignBtn = row.querySelector(".assign-btn");
      let viewBtn = row.querySelector(".view-btn");
      let unassignBtn = row.querySelector(".unassign-btn");
      let doneBtn = row.querySelector(".done-btn");
      let failBtn = row.querySelector(".fail-btn");
      let rejectBtn = row.querySelector(".reject-btn");

      if (doneBtn) {
        doneBtn.addEventListener("click", () =>
          handleTaskActionStatus(task, 1)
        );
      }

      if (failBtn) {
        failBtn.addEventListener("click", () =>
          handleTaskActionStatus(task, 2)
        );
      }

      if (rejectBtn) {
        rejectBtn.addEventListener("click", () =>
          handleTaskActionStatus(task, 3)
        );
      }

      // Add event listeners only if the buttons exist
      if (assignBtn) {
        assignBtn.addEventListener("click", () => openDistribute(task));
      }

      if (viewBtn) {
        viewBtn.addEventListener("click", () =>
          handleDistributedViewTask(task.id)
        );
      }

      if (unassignBtn) {
        unassignBtn.addEventListener("click", () =>
          handleUnassignTask(task, task?.assigned_users)
        );
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
