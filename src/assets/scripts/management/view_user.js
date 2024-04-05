const fullnameParagraph = document.getElementById("view_fullname");
const addressParagraph = document.getElementById("view_address");
const ageParagraph = document.getElementById("view_age");
const contactParagraph = document.getElementById("view_contact");
const genderParagraph = document.getElementById("view_gender");

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

// Function to open permissions modal
async function openViewUserDetailsModal(userId) {
  try {
    const result = await fetchUserData(userId);

    if (result.length > 0) {
      fullnameParagraph.textContent = result[0]?.full_name || "Not available";
      addressParagraph.textContent = result[0]?.address || "Not available";
      ageParagraph.textContent = result[0]?.age || "Not available";
      contactParagraph.textContent = result[0]?.contact || "Not available";
      genderParagraph.textContent = result[0]?.gender || "Not available";

      document.getElementById("viewUserDetails").classList.remove("hidden");
    } else {
      // Display a message indicating that user details are not available
      fullnameParagraph.textContent = "Not available";
      addressParagraph.textContent = "Not available";
      ageParagraph.textContent = "Not available";
      contactParagraph.textContent = "Not available";
      genderParagraph.textContent = "Not available";

      document.getElementById("viewUserDetails").classList.remove("hidden");
    }
  } catch (error) {
    console.error(error);
  }
}

// Function to close permissions modal
function closeViewUserDetailsModal() {
  document.getElementById("viewUserDetails").classList.add("hidden");
  //   clearInputs();
}
