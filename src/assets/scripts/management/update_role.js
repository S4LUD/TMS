const updateRoleForm = document.getElementById("updateRoleForm");
const roleUpdateInput = document.getElementById("update_role");
const visibilityUpdateInput = document.getElementById("update_visibility");

// Function to open the modal
async function openupdateRoleModal() {
  const roleId = localStorage.getItem("roleId");
  const rolesList = await fetchRoles();

  const result = rolesList.filter((role) => role.id === Number(roleId));

  if (result) {
    console.log(result);
    roleUpdateInput.value = result[0].role;
    visibilityUpdateInput.value = result[0].visibility;
  }

  document.getElementById("updateroleModalOverlay").classList.remove("hidden");
}

// Function to close the modal
function closeupdateRoleModal() {
  document.getElementById("updateroleModalOverlay").classList.add("hidden");
}

// Function to display a toast message
function showToast(message, backgroundColor) {
  Toastify({
    text: message,
    duration: 5000,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    style: {
      background: backgroundColor,
    },
  }).showToast();
}

// Function to handle form submission
updateRoleForm.addEventListener("submit", async function (event) {
  event.preventDefault();

  // Retrieve roleId from localStorage
  const roleId = localStorage.getItem("roleId");

  // Validate input fields
  const role = roleUpdateInput.value.trim();
  const visibility = visibilityUpdateInput.value;
  if (!role) {
    showToast("Please fill in role field", "#FA3636");
    return;
  }

  try {
    // Send update request to the server
    const response = await fetch(
      `${apiLink}/updaterole?roleId=${roleId}&role=${role}&visibility=${visibility}`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
      }
    );

    // Parse response as JSON
    const result = await response.json();

    // Check if response contains message or error
    if (result.message) {
      showToast(result.message, "#3CA2FA");
    } else if (result.error) {
      showToast(result.error, "#FA3636");
    }

    // Update role list after submission
    updateRoleList();
    clearRoleUpdateInputs();
    closeupdateRoleModal();
  } catch (error) {
    console.error(error);
    showToast("An error occurred while processing your request", "#FA3636");
  }
});

function clearRoleUpdateInputs() {
  roleUpdateInput.value = "";
}
