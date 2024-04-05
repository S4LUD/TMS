const performanceCheckbox = document.getElementById("performance");
const reportCheckbox = document.getElementById("report");
const accountManagementCheckbox = document.getElementById("account_management");
const createUserCheckbox = document.getElementById("create_user");
const rolesCheckbox = document.getElementById("roles");
const departmentsCheckbox = document.getElementById("departments");
const deleteCheckbox = document.getElementById("delete");
const editCheckbox = document.getElementById("edit");
const viewCheckbox = document.getElementById("view");
const permissionsCheckbox = document.getElementById("permissions");
const tasksCheckbox = document.getElementById("tasks");
const createTaskCheckbox = document.getElementById("create_task");
const deleteTaskCheckbox = document.getElementById("delete_task");
const viewTaskCheckbox = document.getElementById("view_task");
const editTaskCheckbox = document.getElementById("edit_task");
const distributeCheckbox = document.getElementById("distribute");
const assignCheckbox = document.getElementById("assign");

// Function to enable or disable child checkboxes based on parent checkbox state
function handleChildCheckboxes(parentCheckbox, childCheckboxes) {
  const isChecked = parentCheckbox.checked;
  childCheckboxes.forEach((checkbox) => {
    checkbox.disabled = !isChecked;
    if (!isChecked) checkbox.checked = false;
  });
}

// Event listeners for account management checkbox
accountManagementCheckbox.addEventListener("change", function () {
  handleChildCheckboxes(this, [
    createUserCheckbox,
    rolesCheckbox,
    departmentsCheckbox,
    deleteCheckbox,
    viewCheckbox,
    editCheckbox,
    permissionsCheckbox,
  ]);
});

// Event listener for tasks checkbox
tasksCheckbox.addEventListener("change", function () {
  handleChildCheckboxes(this, [
    createTaskCheckbox,
    deleteTaskCheckbox,
    viewTaskCheckbox,
    editTaskCheckbox,
  ]);
});

// Event listener for distribute checkbox
distributeCheckbox.addEventListener("change", function () {
  handleChildCheckboxes(this, [assignCheckbox]);
});

// Function to clear inputs
function clearInputs() {
  const checkboxes = [
    performanceCheckbox,
    reportCheckbox,
    accountManagementCheckbox,
    createUserCheckbox,
    rolesCheckbox,
    departmentsCheckbox,
    deleteCheckbox,
    viewCheckbox,
    editCheckbox,
    permissionsCheckbox,
    tasksCheckbox,
    createTaskCheckbox,
    deleteTaskCheckbox,
    viewTaskCheckbox,
    editTaskCheckbox,
    distributeCheckbox,
    assignCheckbox,
  ];

  checkboxes.forEach((checkbox) => {
    checkbox.checked = false;
  });
}

// Function to open permissions modal
async function openPermissionsModal(userId) {
  localStorage.setItem("manage_id", userId);

  try {
    const response = await fetch(
      `${apiLink}/fetchuser?searchTerm=${userId}`,
      {
        method: "GET",
        redirect: "follow",
      }
    );
    const result = await response.json();
    const permissions = result.permissions;
    if (permissions) {
      performanceCheckbox.checked = permissions.performance;
      reportCheckbox.checked = permissions.report;
      accountManagementCheckbox.checked =
        permissions.account_management.enabled;
      createUserCheckbox.checked =
        permissions.account_management.source.create_user;
      rolesCheckbox.checked = permissions.account_management.source.roles;
      departmentsCheckbox.checked =
        permissions.account_management.source.departments;
      deleteCheckbox.checked = permissions.account_management.source.delete;
      viewCheckbox.checked = permissions.account_management.source.view;
      editCheckbox.checked = permissions.account_management.source.edit;
      permissionsCheckbox.checked =
        permissions.account_management.source.permissions;
      tasksCheckbox.checked = permissions.tasks.enabled;
      createTaskCheckbox.checked = permissions.tasks.source.create_task;
      deleteTaskCheckbox.checked = permissions.tasks.source.delete;
      viewTaskCheckbox.checked = permissions.tasks.source.view;
      editTaskCheckbox.checked = permissions.tasks.source.edit;
      distributeCheckbox.checked = permissions.distribute.enabled;
      assignCheckbox.checked = permissions.distribute.source.assign;

      // Enable child checkboxes if parent is checked
      handleChildCheckboxes(accountManagementCheckbox, [
        createUserCheckbox,
        rolesCheckbox,
        departmentsCheckbox,
        deleteCheckbox,
        viewCheckbox,
        editCheckbox,
        permissionsCheckbox,
      ]);
      handleChildCheckboxes(tasksCheckbox, [
        createTaskCheckbox,
        deleteTaskCheckbox,
        viewTaskCheckbox,
        editTaskCheckbox,
      ]);
      handleChildCheckboxes(distributeCheckbox, [assignCheckbox]);
    }
  } catch (error) {
    console.error(error);
  }

  document.getElementById("permissionsModal").classList.remove("hidden");
}

// Function to close permissions modal
function closePermissionsModal() {
  document.getElementById("permissionsModal").classList.add("hidden");
  clearInputs();
}

// Function to save permissions
async function savePermissions() {
  const manageId = localStorage.getItem("manage_id");
  try {
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
    const jsonString = JSON.stringify(permissions, null, 0)
      .replace(/\n/g, "")
      .replace(/\s{2,}/g, " ");

    await fetch(
      `${apiLink}/updatepermissions?userId=${manageId}&permissions=${jsonString}`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        redirect: "follow",
      }
    )
      .then((response) => response.json())
      .then((result) => {
        if (result?.message) {
          Toastify({
            text: result?.message,
            duration: 5000,
            gravity: "top", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
              background: "#3CA2FA",
            },
          }).showToast();
        } else {
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
      .finally(() => closePermissionsModal());
  } catch (error) {
    console.error(error);
  }
}
