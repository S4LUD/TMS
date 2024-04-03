const userIdInput = document.getElementById("userId");
const fullnameInput = document.getElementById("edit_fullname");
const addressInput = document.getElementById("edit_address");
const ageInput = document.getElementById("edit_age");
const contackInput = document.getElementById("edit_contact");
const genderInput = document.getElementById("edit_gender");

// Function to open permissions modal
async function openUserDetailsModal(userId) {
  try {
    const result = await fetchUserData(userId);

    if (result.length > 0) {
      userIdInput.value = result[0]?.id;
      fullnameInput.value = result[0]?.full_name;
      addressInput.value = result[0]?.address;
      ageInput.value = result[0]?.age;
      contackInput.value = result[0]?.contact;
      genderInput.value = result[0]?.gender;

      document.getElementById("editUserDetails").classList.remove("hidden");
    }
  } catch (error) {
    console.error(error);
  }
}

// Function to close permissions modal
function closeUserDetailsModal() {
  document.getElementById("editUserDetails").classList.add("hidden");
  clearInputs();
}

// Add an event listener to the input element
ageInput.addEventListener("input", function (event) {
  // Get the current value of the input field
  let value = event.target.value;

  // Remove any non-numeric characters from the value
  value = value.replace(/\D/g, "");

  // Update the input field value with the sanitized value
  event.target.value = value;
});

contackInput.addEventListener("input", function (event) {
  // Get the current value of the input field
  let value = event.target.value;

  // Remove any non-numeric characters from the value
  value = value.replace(/[^\d]/g, "");

  // Update the input field value with the sanitized value
  event.target.value = value;
});

// Function to fetch user data from the API
async function fetchUserData(userId) {
  const response = await fetch(
    `${apiLink}/fetchallusers?searchaccount=${userId}`
  );
  if (!response.ok) {
    throw new Error(`Failed to fetch user data: ${response.statusText}`);
  }
  const userData = await response.json();
  return userData;
}

async function saveUserDetails() {
  await fetch(
    `http://localhost/tms/api/updateuserdetails?userId=${userIdInput.value}&fullname=${fullnameInput.value}&address=${addressInput.value}&age=${ageInput.value}&contact=${contackInput.value}&gender=${genderInput.value}`,
    {
      method: "GET",
      headers: { "Content-Type": "application/json" },
      redirect: "follow",
    }
  )
    .then((response) => response.json())
    .then((result) => {
      if (result.message) {
        Toastify({
          text: result.message,
          duration: 5000,
          gravity: "top", // `top` or `bottom`
          position: "right", // `left`, `center` or `right`
          stopOnFocus: true, // Prevents dismissing of toast on hover
          style: {
            background: "#3CA2FA",
          },
        }).showToast();
      } else if (result.error) {
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
    .finally(() => {
      closeUserDetailsModal();
      clearInputs();
    });
}

function clearInputs() {
  fullnameInput.value = "";
  addressInput.value = "";
  ageInput.value = 0;
  contackInput.value = "";
  genderInput.value = "";
  userIdInput.value = "";
}
