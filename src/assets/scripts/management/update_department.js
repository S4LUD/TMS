const updateDepartmentForm = document.getElementById("updateDepartmentForm");
const abbreviationUpdateInput = document.getElementById("update_abbreviation");
const departmentUpdateInput = document.getElementById("update_department");

// Function to open the modal
async function openupdateDepartmentModal() {
  const departmentId = localStorage.getItem("departmentId");
  const departmentsList = await fetchDepartments();

  const result = departmentsList.filter(
    (department) => department.id === Number(departmentId)
  );

  if (result) {
    abbreviationUpdateInput.value = result[0].abbreviation;
    departmentUpdateInput.value = result[0].department;
  }

  document
    .getElementById("updatedepartmentModalOverlay")
    .classList.remove("hidden");
}

// Function to close the modal
function closeupdateDepartmentModal() {
  document
    .getElementById("updatedepartmentModalOverlay")
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
updateDepartmentForm.addEventListener("submit", async function (event) {
  event.preventDefault();

  // Retrieve departmentId from localStorage
  const departmentId = localStorage.getItem("departmentId");

  // Validate input fields
  const abbreviation = abbreviationUpdateInput.value.trim();
  const department = departmentUpdateInput.value.trim();
  if (!abbreviation || !department) {
    showToast(
      "Please fill in both abbreviation and department fields",
      "#FA3636"
    );
    return;
  }

  try {
    // Send update request to the server
    const response = await fetch(
      `${apiLink}/updatedepartment?departmentId=${departmentId}&abbreviation=${abbreviation}&department=${department}`,
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

    // Update department list after submission
    updateDepartmentList();
    clearUpdateInputs();
    closeupdateDepartmentModal();
  } catch (error) {
    console.error(error);
    showToast("An error occurred while processing your request", "#FA3636");
  }
});

function clearUpdateInputs() {
  abbreviationUpdateInput.value = "";
  departmentUpdateInput.value = "";
}
