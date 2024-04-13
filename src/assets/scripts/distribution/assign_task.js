const userContainer = document.getElementById("user_list_container");
const maxDisplay = 4;
let names = []; // Array to hold user names
let selectedUsers = [];

async function fetchUsers() {
  try {
    const userDetails = JSON.parse(localStorage.getItem("user"));
    const role = userDetails?.role;
    const abbreviation = userDetails?.abbreviation;

    let response;
    let result;

    if (role === "SUPER ADMIN") {
      // Fetch all users
      response = await fetch(`${apiLink}/fetchallusers`);
    } else {
      // Fetch only EMPLOYEE users
      response = await fetch(
        `${apiLink}/fetchallusers?abbreviation=${abbreviation}`
      );
    }

    result = await response.json();

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
    if (!query || user.username.toLowerCase().includes(query.toLowerCase())) {
      const listItem = document.createElement("div");
      listItem.classList =
        "w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 transition duration-75";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.value = user.id;
      checkbox.classList = "mr-2";

      // Check if user is already selected
      const isSelected = selectedUsers.some((u) => u.id === user.id);
      if (isSelected) {
        checkbox.checked = true; // Set the checkbox to checked if user is selected
      }

      checkbox.addEventListener("click", () => {
        selectUser(user.username, user.id);
      });
      listItem.appendChild(checkbox);

      const label = document.createElement("label");
      label.textContent = user.username;
      listItem.appendChild(label);

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
  updateDisplay("");
}

function selectUser(username, id) {
  const user = { username, id };
  const index = selectedUsers.findIndex((u) => u.id === id); // Check if user is already selected

  if (index === -1) {
    // If user is not already selected, push it into the array
    selectedUsers.push(user);
  } else {
    // If user is already selected, remove it from the array
    selectedUsers.splice(index, 1);
  }

  // Display selected usernames
  displaySelectedUsers();
}

function displaySelectedUsers() {
  const selectedUsersContainer = document.getElementById(
    "selectedUsersContainer"
  );
  selectedUsersContainer.innerHTML = ""; // Clear previous content

  if (selectedUsers.length > 0) {
    // If there are selected users, display them
    selectedUsers.forEach((user) => {
      const usernameElement = document.createElement("span");
      usernameElement.textContent = user.username;
      selectedUsersContainer.appendChild(usernameElement);

      // Add a comma and space after each username except the last one
      if (selectedUsers.indexOf(user) !== selectedUsers.length - 1) {
        const comma = document.createElement("span");
        comma.textContent = ", ";
        selectedUsersContainer.appendChild(comma);
      }
    });
  } else {
    // If no users are selected, prompt the user to select users first
    const promptMessage = document.createElement("span");
    promptMessage.textContent = "Click here to select users";
    selectedUsersContainer.appendChild(promptMessage);
  }
}

function closeDistribute() {
  document.getElementById("dueDate").value = "";
  document.getElementById("taskType").value = "";
  document.getElementById("distributeTask").classList.add("hidden");
  document.getElementById("userdropdown").classList.add("hidden");
  document.getElementById("userdropdownbackdrop").classList.add("hidden");
  localStorage.removeItem("userId");
  localStorage.removeItem("taskId");
  selectedUsers = [];
  displaySelectedUsers();
  updateDisplay("");
}

async function assigntask(event) {
  event.preventDefault();
  const dueDate = document.getElementById("dueDate").value;
  const taskType = document.getElementById("taskType").value;
  const taskId = localStorage.getItem("taskId");

  // Check if there are selected users
  if (selectedUsers.length === 0) {
    Toastify({
      text: "No users selected",
      duration: 5000,
      gravity: "top",
      position: "right",
      stopOnFocus: true,
      style: {
        background: "#FA3636",
      },
    }).showToast();
    return;
  }

  try {
    const userDetails = JSON.parse(localStorage.getItem("user"));
    const { role } = userDetails;

    const requests = selectedUsers.map((user) => {
      return fetch(
        `${apiLink}/distributetask?task_type=${taskType}&role=${role}&user_id=${user.id}&dueAt=${dueDate}&task_id=${taskId}`,
        {
          method: "GET",
        }
      );
    });

    const responses = await Promise.all(requests);

    let allSuccessful = true;

    responses.forEach(async (response) => {
      const result = await response.json();

      if (!result.message) {
        allSuccessful = false;
      }
    });

    if (allSuccessful) {
      Toastify({
        text: "All tasks assigned successfully",
        duration: 5000,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        style: {
          background: "#3CA2FA",
        },
      }).showToast();
    } else {
      Toastify({
        text: "Failed to assign tasks",
        duration: 5000,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        style: {
          background: "#FA3636",
        },
      }).showToast();
    }

    // Perform other tasks after all API requests are completed
    tasks = await fetchTasks();
    taskCount.innerText = Math.ceil(tasks.length / itemsPerPage);
    await updateTableForCurrentPage();
    fetchUsers();
    // Clear selected users after successful assignment
    selectedUsers = [];
    displaySelectedUsers();
    closeDistribute();
    updateDisplay("");
  } catch (error) {
    console.error("Error:", error);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // Load selected users from wherever you store them initially
  // For example, if selectedUsers is initially populated from an API response
  // or some other data source, you can skip this step.

  // Display the selected users initially
  displaySelectedUsers();
});
