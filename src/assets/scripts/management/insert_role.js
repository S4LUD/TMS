const insertRoleForm = document.getElementById("insertRoleForm");
const roleInput = document.getElementById("insert_role");
const visibilityInput = document.getElementById("insert_visibility");

// Function to open the modal
function openInsertRoleModal() {
  document.getElementById("insertroleModalOverlay").classList.remove("hidden");
}

// Function to close the modal
function closeInsertRoleModal() {
  document.getElementById("insertroleModalOverlay").classList.add("hidden");
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
insertRoleForm.addEventListener("submit", async function (event) {
  event.preventDefault();

  // Validate input fields
  if (!roleInput.value.trim()) {
    showToast("Please fill in role field", "#FA3636");
    return;
  }

  try {
    const response = await fetch(
      `${apiLink}/insertrole?role=${roleInput.value}&visibility=${visibilityInput.value}`,
      {
        method: "GET",
      }
    );

    const result = await response.json();
    // Check if response contains message or error
    if (result.message) {
      showToast(result.message, "#3CA2FA");
    } else if (result.error) {
      showToast(result.error, "#FA3636");
    }

    // Update role list after submission
    updateRoleList();
    clearInsertInput();
    closeInsertRoleModal();
  } catch (error) {
    console.error(error);
    showToast("An error occurred while processing your request", "#FA3636");
  }
});

function clearInsertInput() {
  roleInput.value = "";
}
