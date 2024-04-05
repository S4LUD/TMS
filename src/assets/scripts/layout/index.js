const mediaQuery = gsap.matchMedia();
const userIdSettingsInput = document.getElementById("settings_userId");
const fullnameSettingsInput = document.getElementById("settings_edit_fullname");
const addressSettingsInput = document.getElementById("settings_edit_address");
const ageSettingsInput = document.getElementById("settings_edit_age");
const contackSettingsInput = document.getElementById("settings_edit_contact");
const genderSettingsInput = document.getElementById("settings_edit_gender");

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
    `${apiLink}/updateuserdetails?userId=${userIdSettingsInput.value}&fullname=${fullnameSettingsInput.value}&address=${addressSettingsInput.value}&age=${ageSettingsInput.value}&contact=${contackSettingsInput.value}&gender=${genderSettingsInput.value}`,
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

async function openSettingsModal(userId) {
  try {
    const result = await fetchUserData(userId);

    if (result.length > 0) {
      userIdSettingsInput.value = result[0]?.id;
      fullnameSettingsInput.value = result[0]?.full_name;
      addressSettingsInput.value = result[0]?.address;
      ageSettingsInput.value = result[0]?.age;
      contackSettingsInput.value = result[0]?.contact;
      genderSettingsInput.value = result[0]?.gender;
      document.getElementById("settings_modal").classList.remove("hidden");
    }
  } catch (error) {
    console.error(error);
  }
}

function closeSettingsModal() {
  document.getElementById("settings_modal").classList.add("hidden");
}
