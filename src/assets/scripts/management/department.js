const departmentList = document.getElementById("departmentList");
const prevDepartmentPageBtn = document.getElementById("prevDepartmentPageBtn");
const nextDepartmentPageBtn = document.getElementById("nextDepartmentPageBtn");
const limitDepartmentInput = document.getElementById("limitDepartmentInput");
const departmentCount = document.getElementById("departmentCount");
let departments = []; // Array to hold all tasks
let currentDepartmentPage = 1; // Current page number
const departmentsItemsPerPage = 5; // Number of tasks per page

async function fetchDepartments() {
  const response = await fetch(`${apiLink}/fetchdepartments`, {
    method: "GET",
    headers: { "Content-Type": "application/json" },
    redirect: "follow",
  });
  const result = await response.json();
  return result;
  //   departmentCount.innerText = Math.ceil(result.length / itemsPerPage);
}

async function fetchDepartmentsWithPagination(page) {
  const startIndex = (page - 1) * departmentsItemsPerPage;
  const endIndex = startIndex + departmentsItemsPerPage;
  const departmentsOnPage = departments.slice(startIndex, endIndex);
  return departmentsOnPage;
}

async function updateDepartmentTableForCurrentPage() {
  const departmentsOnPage = await fetchDepartmentsWithPagination(
    currentDepartmentPage
  );
  updateDepartmensTable(departmentsOnPage);
}

// Function to open the modal
async function openDepartmentModal() {
  document.getElementById("departmentModalOverlay").classList.remove("hidden");
}

async function updateDepartmensTable(departments) {
  departmentList.innerHTML = ""; // Clear the department list

  if (departments.length === 0) {
    // If no tasks found, display a message
    const container = document.createElement("div");
    container.innerHTML = `<div colspan="4" class="text-center py-3">No departments found.</div>`;
    departmentList.appendChild(container);
  } else {
    // Iterate over tasks and populate the table rows
    for (const department of departments) {
      const listItem = document.createElement("li");
      listItem.classList.add("py-2", "flex", "justify-between", "items-center");

      const departmentInfo = document.createElement("div");
      const departmentName = document.createElement("p");
      departmentName.classList.add("font-bold", "text-blue-500");
      departmentName.textContent = department.abbreviation;
      const departmentDescription = document.createElement("p");
      departmentDescription.classList.add("text-sm", "text-gray-600");
      departmentDescription.textContent = department.department;

      departmentInfo.appendChild(departmentName);
      departmentInfo.appendChild(departmentDescription);

      const actionButtons = document.createElement("div");
      actionButtons.classList.add("flex", "gap-1");
      const deleteButton = document.createElement("span");
      deleteButton.classList.add(
        "bg-red-500",
        "hover:bg-red-600",
        "text-white",
        "hover:text-gray-100",
        "px-2",
        "py-1",
        "rounded",
        "delete-btn"
      );
      deleteButton.style.cursor = "pointer";
      deleteButton.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
      deleteButton.addEventListener("click", () => {
        // Call function to handle delete operation
        deleteDepartment(department.id); // Pass the department id as parameter
      });

      const editButton = document.createElement("span");
      editButton.classList.add(
        "bg-yellow-500",
        "hover:bg-yellow-600",
        "text-white",
        "hover:text-gray-100",
        "px-2",
        "py-1",
        "rounded",
        "edit-btn"
      );
      editButton.style.cursor = "pointer";
      editButton.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>';
      editButton.addEventListener("click", () => {
        // Call function to handle edit operation
        localStorage.setItem("departmentId", department.id);
        openupdateDepartmentModal();
      });

      actionButtons.appendChild(deleteButton);
      actionButtons.appendChild(editButton);

      listItem.appendChild(departmentInfo);
      listItem.appendChild(actionButtons);

      departmentList.appendChild(listItem);
    }
  }
}

// Function to close the modal
function closeDepartmentModal() {
  document.getElementById("departmentModalOverlay").classList.add("hidden");
}

// Function to handle previous page button click
async function handleDepartmentPrevPageButtonClick() {
  if (currentDepartmentPage > 1) {
    currentDepartmentPage--;
    limitDepartmentInput.value = currentDepartmentPage;
    await updateDepartmentTableForCurrentPage();
  }
}

// Function to handle next page button click
async function handleDepartmentNextPageButtonClick() {
  const totalPages = Math.ceil(departments.length / departmentsItemsPerPage);
  if (currentDepartmentPage < totalPages) {
    currentDepartmentPage++;
    limitDepartmentInput.value = currentDepartmentPage;
    await updateDepartmentTableForCurrentPage();
  }
}

prevDepartmentPageBtn.addEventListener(
  "click",
  handleDepartmentPrevPageButtonClick
);
nextDepartmentPageBtn.addEventListener(
  "click",
  handleDepartmentNextPageButtonClick
);

async function updateDepartmentList() {
  // Fetch tasks without filtering initially
  departments = await fetchDepartments();
  departmentCount.innerText = Math.ceil(
    departments.length / departmentsItemsPerPage
  );
  await updateDepartmentTableForCurrentPage();
}

// Initial fetch of tasks when the page loads
document.addEventListener("DOMContentLoaded", async () => {
  try {
    // Fetch tasks without filtering initially
    departments = await fetchDepartments();
    departmentCount.innerText = Math.ceil(
      departments.length / departmentsItemsPerPage
    );
    await updateDepartmentTableForCurrentPage();
  } catch (error) {
    console.error("Error fetching tasks:", error.message);
  }
});
