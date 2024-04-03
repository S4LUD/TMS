const performanceCheckbox = document.getElementById("performance");
const reportCheckbox = document.getElementById("report");

const accountManagementCheckbox = document.getElementById("account_management");
const createUserCheckbox = document.getElementById("create_user");
const rolesCheckbox = document.getElementById("roles");
const departmentsCheckbox = document.getElementById("departments");
const deleteCheckbox = document.getElementById("delete");
const viewCheckbox = document.getElementById("view");
const editCheckbox = document.getElementById("edit");
const permissionsCheckbox = document.getElementById("permissions");

const tasksCheckbox = document.getElementById("tasks");
const createTaskCheckbox = document.getElementById("create_task");
const deleteTaskCheckbox = document.getElementById("delete_task");
const viewTaskCheckbox = document.getElementById("view_task");
const editTaskCheckbox = document.getElementById("edit_task");

const distributeCheckbox = document.getElementById("distribute");
const assignCheckbox = document.getElementById("assign");

// Function to open the modal
function openPermissionsModal() {
  document.getElementById("permissionsModal").classList.remove("hidden");
}

// Function to close the modal
function closePermissionsModal() {
  document.getElementById("permissionsModal").classList.add("hidden");
  clearCreateInputs();
}

accountManagementCheckbox.addEventListener("change", function () {
  if (this.checked === false) {
    // Disable checkboxes if account management is unchecked
    createUserCheckbox.disabled = true;
    rolesCheckbox.disabled = true;
    departmentsCheckbox.disabled = true;
    deleteCheckbox.disabled = true;
    viewCheckbox.disabled = true;
    editCheckbox.disabled = true;
    permissionsCheckbox.disabled = true;
  } else {
    // Enable checkboxes if account management is checked
    createUserCheckbox.disabled = false;
    rolesCheckbox.disabled = false;
    departmentsCheckbox.disabled = false;
    deleteCheckbox.disabled = false;
    viewCheckbox.disabled = false;
    editCheckbox.disabled = false;
    permissionsCheckbox.disabled = false;
  }
});

tasksCheckbox.addEventListener("change", function () {
  if (this.checked === false) {
    // Disable checkboxes if account management is unchecked
    viewTaskCheckbox.disabled = true;
    createTaskCheckbox.disabled = true;
    deleteTaskCheckbox.disabled = true;
    editTaskCheckbox.disabled = true;
  } else {
    // Enable checkboxes if account management is checked
    viewTaskCheckbox.disabled = false;
    createTaskCheckbox.disabled = false;
    deleteTaskCheckbox.disabled = false;
    editTaskCheckbox.disabled = false;
  }
});

distributeCheckbox.addEventListener("change", function () {
  if (this.checked === false) {
    // Disable checkboxes if account management is unchecked
    assignCheckbox.disabled = true;
  } else {
    // Enable checkboxes if account management is checked
    assignCheckbox.disabled = false;
  }
});

function clearCreateInputs() {
  // Uncheck all checkboxes
  performanceCheckbox.checked = false;
  reportCheckbox.checked = false;
  accountManagementCheckbox.checked = false;
  createUserCheckbox.checked = false;
  rolesCheckbox.checked = false;
  departmentsCheckbox.checked = false;
  deleteCheckbox.checked = false;
  viewCheckbox.checked = false;
  editCheckbox.checked = false;
  permissionsCheckbox.checked = false;
  tasksCheckbox.checked = false;
  createTaskCheckbox.checked = false;
  deleteTaskCheckbox.checked = false;
  viewTaskCheckbox.checked = false;
  editTaskCheckbox.checked = false;
  distributeCheckbox.checked = false;
  assignCheckbox.checked = false;
  createUserCheckbox.disabled = true;
  rolesCheckbox.disabled = true;
  departmentsCheckbox.disabled = true;
  deleteCheckbox.disabled = true;
  viewCheckbox.disabled = true;
  editCheckbox.disabled = true;
  permissionsCheckbox.disabled = true;
  viewTaskCheckbox.disabled = true;
  createTaskCheckbox.disabled = true;
  deleteTaskCheckbox.disabled = true;
  editTaskCheckbox.disabled = true;
  assignCheckbox.disabled = true;
}

async function savePermissions() {
  const permissions = {
    performance: performanceCheckbox.checked,
    report: reportCheckbox.checked,
    account_management: {
      enabled: accountManagementCheckbox.checked,
      source: {
        create_user: createUserCheckbox.checked,
        roles: rolesCheckbox.checked,
        departments: departmentsCheckbox.checked,
        delete: deleteCheckbox.checked,
        view: viewCheckbox.checked,
        edit: editCheckbox.checked,
        permissions: permissionsCheckbox.checked,
      },
    },
    tasks: {
      enabled: tasksCheckbox.checked,
      source: {
        create_task: createTaskCheckbox.checked,
        delete: deleteTaskCheckbox.checked,
        view: viewTaskCheckbox.checked,
        edit: editTaskCheckbox.checked,
      },
    },
    distribute: {
      enabled: distributeCheckbox.checked,
      source: {
        assign: assignCheckbox.checked,
      },
    },
  };

  console.log(permissions);
}
