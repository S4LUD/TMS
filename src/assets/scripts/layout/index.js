const mediaQuery = gsap.matchMedia();
const fullnameSettingsInput = document.getElementById(
  "user_information_edit_fullname"
);
const addressSettingsInput = document.getElementById(
  "user_information_edit_address"
);
const ageSettingsInput = document.getElementById("user_information_edit_age");
const contackSettingsInput = document.getElementById(
  "user_information_edit_contact"
);
const genderSettingsInput = document.getElementById(
  "user_information_edit_gender"
);

window.addEventListener("resize", function () {
  (async () => {
    var backdrop = document.getElementById("backdrop");
    var isLargeScreen = window.matchMedia("(min-width: 640px)").matches;
    var isBackdrop = !backdrop.classList.contains("hidden");

    if (isBackdrop && isLargeScreen) {
      toggleSideMenu();
    }
  })();
});

async function toggleSideMenu() {
  var sideMenu = document.getElementById("sideMenu");
  var backdrop = document.getElementById("backdrop");
  var container = document.getElementById("main-container");

  sideMenu.classList.toggle("hidden");
  backdrop.classList.toggle("hidden");
  container.classList.toggle("overflow-hidden");
}

const toggleFullscreenButton = document.getElementById("toggleFullscreen");

toggleFullscreenButton.addEventListener("click", () => {
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    document.documentElement.requestFullscreen();
  }
});

function openLogoutModal() {
  document.getElementById("logoutModal").classList.remove("hidden");
}

function closeLogoutModal() {
  document.getElementById("logoutModal").classList.add("hidden");
}

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
  const userDetails = await JSON.parse(localStorage.getItem("user"));
  const { id } = await userDetails;

  await fetch(
    `${apiLink}/updateuserdetails?userId=${id}&fullname=${fullnameSettingsInput.value}&address=${addressSettingsInput.value}&age=${ageSettingsInput.value}&contact=${contackSettingsInput.value}&gender=${genderSettingsInput.value}`,
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
    .catch((error) => console.error(error));
}

async function openSettingsModal() {
  try {
    document.getElementById("settings_modal").classList.remove("hidden");
  } catch (error) {
    console.error(error);
  }
}

function closeSettingsModal() {
  try {
    document.getElementById("settings_modal").classList.add("hidden");
  } catch (error) {
    console.error(error);
  }
}

// Add an event listener to the input element
ageSettingsInput.addEventListener("input", function (event) {
  // Get the current value of the input field
  let value = event.target.value;

  // Remove any non-numeric characters from the value
  value = value.replace(/\D/g, "");

  // Update the input field value with the sanitized value
  event.target.value = value;
});

contackSettingsInput.addEventListener("input", function (event) {
  // Get the current value of the input field
  let value = event.target.value;

  // Remove any non-numeric characters from the value
  value = value.replace(/[^\d]/g, "");

  // Update the input field value with the sanitized value
  event.target.value = value;
});

async function openUserInformationModal() {
  try {
    const userDetails = await JSON.parse(localStorage.getItem("user"));
    const { id } = await userDetails;

    const result = await fetchUserData(id);

    if (result.length > 0) {
      fullnameSettingsInput.value = result[0]?.full_name;
      addressSettingsInput.value = result[0]?.address;
      ageSettingsInput.value = result[0]?.age;
      contackSettingsInput.value = result[0]?.contact;
      genderSettingsInput.value = result[0]?.gender;

      document
        .getElementById("user_information_modal")
        .classList.remove("hidden");
    }
  } catch (error) {
    console.error(error);
  }
}

function closeUserInformationModal() {
  try {
    document.getElementById("user_information_modal").classList.add("hidden");
  } catch (error) {
    console.error(error);
  }
}

async function openChangePasswordModal() {
  try {
    document.getElementById("change_password_modal").classList.remove("hidden");
  } catch (error) {
    console.error(error);
  }
}

function closeChangePasswordModal() {
  try {
    document.getElementById("change_password_modal").classList.add("hidden");
  } catch (error) {
    console.error(error);
  }
}

async function ChangePassword(event) {
  event.preventDefault();

  try {
    // Retrieve user ID from localStorage
    const userDetails = JSON.parse(localStorage.getItem("user"));
    const userId = userDetails?.id;

    // Retrieve input elements and error element
    const currentPassword = document.getElementById(
      "change_current_password"
    ).value;
    const newPassword = document.getElementById("new_password").value;
    const confirmNewPassword = document.getElementById(
      "confirm_new_password"
    ).value;
    const errorElement = document.getElementById("change_password_error");

    // Check if new password and confirm new password match
    if (newPassword !== confirmNewPassword) {
      errorElement.innerText = "Passwords do not match.";
      errorElement.classList.remove("hidden");
      return;
    } else if (newPassword.length < 6) {
      errorElement.innerText = "Password must be at least 6 characters long.";
      errorElement.classList.remove("hidden");
      return;
    } else {
      errorElement.classList.add("hidden");
      errorElement.innerText = "";
    }

    // Make API call to change password
    const response = await fetch(
      `${apiLink}/changepassword?userId=${userId}&currentPassword=${currentPassword}&newPassword=${newPassword}`,
      {
        method: "GET",
        headers: { "Content-Type": "application/json" },
        redirect: "follow",
      }
    );

    if (!response.ok) {
      Toastify({
        text: `Failed to change password: ${response.statusText}`,
        duration: 5000,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
          background: "#FA3636",
        },
      }).showToast();
      return;
    }

    const result = await response.json();
    console.log(result);
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
      document.getElementById("change_current_password").value = "";
      document.getElementById("new_password").value = "";
      document.getElementById("confirm_new_password").value = "";
      closeChangePasswordModal();
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
  } catch (error) {
    console.error(error);
  }
}
