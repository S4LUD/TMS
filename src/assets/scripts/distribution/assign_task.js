const userContainer = document.getElementById("user_list_container");
const maxDisplay = 4;
let names = []; // Array to hold user names

async function fetchUsers() {
  try {
    const response = await fetch("http://localhost/tms/api/fetchallusers");
    const result = await response.json();
    names = result; // Update the names array with the fetched user data
    updateDisplay(""); // Call updateDisplay with an empty query to display all users
  } catch (error) {
    console.error("Error fetching users:", error.message);
  }
}

fetchUsers();

function openDistribute(task_id) {
  localStorage.setItem("taskId", task_id);
  document.getElementById("distributeTask").classList.remove("hidden");
}

function openUserDropdown() {
  document.getElementById("userdropdown").classList.remove("hidden");
  document.getElementById("userdropdownbackdrop").classList.remove("hidden");
}

function updateDisplay(query) {
  userContainer.innerHTML = ""; // Clear the container before updating

  let displayed = 0; // Track the number of displayed items
  let found = false; // Flag to track if any user is found

  for (const user of names) {
    // If the query is empty or the username contains the query
    if (!query || user.username.toLowerCase().includes(query.toLowerCase())) {
      const listItem = document.createElement("div");
      listItem.classList =
        "w-full border rounded-md py-2 px-3 hover:border-blue-200 transition duration-75 cursor-pointer";
      listItem.textContent = user.username;
      listItem.addEventListener("click", () => {
        selectUser(user.id, user.username);
      });
      userContainer.appendChild(listItem);

      displayed++; // Increment the displayed count
      found = true; // Set found flag to true
    }

    // Break the loop if the maximum display limit is reached
    if (displayed >= maxDisplay) {
      break;
    }
  }

  // If no user is found, display a message
  if (!found) {
    const notFoundMessage = document.createElement("div");
    notFoundMessage.classList =
      "w-full text-gray-500 flex font-light justify-center items-center";
    notFoundMessage.textContent = "No user found";
    userContainer.appendChild(notFoundMessage);
  }
}

// Function to handle search input
document.getElementById("searchUser").addEventListener("input", function () {
  updateDisplay(this.value); // Update the display based on the search query
});

function closeUserDropdown() {
  document.getElementById("searchUser").value = "";
  document.getElementById("userdropdown").classList.add("hidden");
  document.getElementById("userdropdownbackdrop").classList.add("hidden");
  localStorage.removeItem("taskId");
}

function selectUser(user_id, username) {
  localStorage.setItem("user_id", user_id);
  const assignTo = document.getElementById("assignTo");
  assignTo.value = username;
  closeUserDropdown();
}

function closeDistribute() {
  localStorage.removeItem("user_id");
  document.getElementById("dueDate").value = "";
  document.getElementById("taskType").value = "";
  document.getElementById("assignTo").value = "";
  document.getElementById("distributeTask").classList.add("hidden");
  document.getElementById("userdropdown").classList.add("hidden");
  document.getElementById("userdropdownbackdrop").classList.add("hidden");
  localStorage.removeItem("user_id");
}

function assigntask() {
  const taskId = localStorage.getItem("taskId");
  console.log(taskId);
}
