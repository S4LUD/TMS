const roleList = document.getElementById("roleList");
const prevRolePageBtn = document.getElementById("prevRolePageBtn");
const nextRolePageBtn = document.getElementById("nextRolePageBtn");
const limitRoleInput = document.getElementById("limitRoleInput");
const roleCount = document.getElementById("roleCount");
let roles = []; // Array to hold all tasks
let currentRolePage = 1; // Current page number
const rolesItemsPerPage = 5; // Number of tasks per page

async function fetchRoles() {
  const response = await fetch(`${apiLink}/fetchroles`, {
    method: "GET",
    headers: { "Content-Type": "application/json" },
    redirect: "follow",
  });
  const result = await response.json();

  // Filter out roles with super value of 1
  const filteredRoles = result.filter((role) => role.super !== 1);

  return filteredRoles;
}

async function fetchRolesWithPagination(page) {
  const startIndex = (page - 1) * rolesItemsPerPage;
  const endIndex = startIndex + rolesItemsPerPage;
  const rolesOnPage = roles.slice(startIndex, endIndex);
  return rolesOnPage;
}

async function updateRoleTableForCurrentPage() {
  const rolesOnPage = await fetchRolesWithPagination(currentRolePage);
  updateRolesTable(rolesOnPage);
}

async function updateRolesTable(roles) {
  roleList.innerHTML = ""; // Clear the roles list

  if (roles.length === 0) {
    // If no tasks found, display a message
    const container = document.createElement("div");
    container.innerHTML = `<div colspan="4" class="text-center py-3">No roles found.</div>`;
    roleList.appendChild(container);
  } else {
    // Iterate over tasks and populate the table rows
    for (const role of roles) {
      const listItem = document.createElement("li");
      listItem.classList.add("py-2", "flex", "justify-between", "items-center");

      const roleInfo = document.createElement("div");
      const roleName = document.createElement("p");
      roleName.classList.add("font-bold", "text-blue-500");
      roleName.textContent = role.role;
      roleInfo.appendChild(roleName);

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
        deleteRole(role.id); // Pass the role id as parameter
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
        localStorage.setItem("roleId", role.id);
        openupdateRoleModal();
      });

      actionButtons.appendChild(deleteButton);
      actionButtons.appendChild(editButton);

      listItem.appendChild(roleInfo);
      listItem.appendChild(actionButtons);

      roleList.appendChild(listItem);
    }
  }
}

// Function to handle previous page button click
async function handleRolePrevPageButtonClick() {
  if (currentRolePage > 1) {
    currentRolePage--;
    limitRoleInput.value = currentRolePage;
    await updateRoleTableForCurrentPage();
  }
}

// Function to handle next page button click
async function handleRoleNextPageButtonClick() {
  const totalPages = Math.ceil(roles.length / rolesItemsPerPage);
  if (currentRolePage < totalPages) {
    currentRolePage++;
    limitRoleInput.value = currentRolePage;
    await updateRoleTableForCurrentPage();
  }
}

prevRolePageBtn.addEventListener("click", handleRolePrevPageButtonClick);
nextRolePageBtn.addEventListener("click", handleRoleNextPageButtonClick);

async function updateRoleList() {
  // Fetch tasks without filtering initially
  roles = await fetchRoles();
  roleCount.innerText = Math.ceil(roles.length / rolesItemsPerPage);
  await updateRoleTableForCurrentPage();
}

// Function to open the modal
function openRoleModal() {
  document.getElementById("roleModalOverlay").classList.remove("hidden");
}

// Function to close the modal
function closeRoleModal() {
  document.getElementById("roleModalOverlay").classList.add("hidden");
}

// Initial fetch of tasks when the page loads
document.addEventListener("DOMContentLoaded", async () => {
  try {
    // Fetch tasks without filtering initially
    roles = await fetchRoles();
    roleCount.innerText = Math.ceil(roles.length / rolesItemsPerPage);
    await updateRoleTableForCurrentPage();
  } catch (error) {
    console.error("Error fetching tasks:", error.message);
  }
});
