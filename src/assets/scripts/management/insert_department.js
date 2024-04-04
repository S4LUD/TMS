const insertDepartmentForm = document.getElementById("insertDepartmentForm");
const abbreviationInput = document.getElementById("insert_abbreviation");
const departmentInput = document.getElementById("insert_department");

// Function to open the modal
function openInsertDepartmentModal() {
  document
    .getElementById("insertdepartmentModalOverlay")
    .classList.remove("hidden");
}

// Function to close the modal
function closeInsertDepartmentModal() {
  document
    .getElementById("insertdepartmentModalOverlay")
    .classList.add("hidden");
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
insertDepartmentForm.addEventListener("submit", async function (event) {
  event.preventDefault();

  // Validate input fields
  if (!abbreviationInput.value.trim() || !departmentInput.value.trim()) {
    showToast(
      "Please fill in both abbreviation and department fields",
      "#FA3636"
    );
    return;
  }

  try {
    const response = await fetch(
      `${apiLink}/insertdepartment?abbreviation=${abbreviationInput.value}&department=${departmentInput.value}`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
      }
    );

    const result = await response.json();
    // Check if response contains message or error
    if (result.message) {
      showToast(result.message, "#3CA2FA");
    } else if (result.error) {
      showToast(result.error, "#FA3636");
    }

    // Update department list after submission
    updateDepartmentList();
    clearInsertInput();
    closeInsertDepartmentModal();
  } catch (error) {
    console.error(error);
    showToast("An error occurred while processing your request", "#FA3636");
  }
});

function clearInsertInput() {
  abbreviationInput.value = "";
  departmentInput.value = "";
}
