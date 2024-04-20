const userContainer = document.getElementById("user_list_container");
const maxDisplay = 4;
let names = []; // Array to hold user names
let selectedUsers = [];

async function fetchUsers() {
  try {
    let response;
    let result;

    // Fetch all users
    response = await fetch(`${apiLink}/fetchallusers`);
    result = await response.json();

    // Filter out users with auth equal to 1
    const filteredUsers = result.filter((user) => user.auth !== 1);

    names = filteredUsers; // Update the names array with the filtered user data
    updateDisplay(""); // Call updateDisplay with an empty query to display all users
  } catch (error) {
    console.error("Error fetching users:", error.message);
  }
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

document.addEventListener("DOMContentLoaded", async function () {
  fetchUsers();
  // Get the select element
  const select = document.getElementById("statusFilter");

  // Create a default option
  const defaultOption = document.createElement("option");
  defaultOption.value = ""; // Set the value to an empty string or any appropriate value
  defaultOption.textContent = "Loading statuses..."; // Set the text content to indicate loading
  select.appendChild(defaultOption); // Append the default option to the select element

  // Fetch task statuses from the API
  await fetch(`${apiLink}/fetchtaskstatuses`)
    .then((response) => response.json())
    .then((data) => {
      // Clear the default option
      select.innerHTML = "";

      // Create a new default option if needed
      const newDefaultOption = document.createElement("option");
      newDefaultOption.value = ""; // Set the value to an empty string or any appropriate value
      newDefaultOption.textContent = "Select Status"; // Set the text content to indicate selection
      select.appendChild(newDefaultOption); // Append the new default option to the select element

      // Loop through the data and create an option for each status
      data.forEach((status) => {
        const option = document.createElement("option");
        option.value = status.status.replace(" ", "-"); // Set value to lowercase with hyphens
        option.textContent = status.status; // Set the text content of the option
        select.appendChild(option); // Append the option to the select element
      });
    })
    .catch((error) => console.error(error));
  //   createdBy.value = "SUPER ADMIN";
  //   currentDate.value = new Date().toISOString().slice(0, 10);

  // Get current date
  const currentDate = new Date();
  // Get first day of the current month
  const firstDayOfMonth = new Date(
    currentDate.getFullYear(),
    currentDate.getMonth(),
    1
  );
  // Get last day of the current month
  const lastDayOfMonth = new Date(
    currentDate.getFullYear(),
    currentDate.getMonth() + 1,
    0
  );

  // Set default values for date inputs
  document.getElementById("startDate").value = formatDate(firstDayOfMonth);
  document.getElementById("endDate").value = formatDate(lastDayOfMonth);

  displaySelectedUsers();
});

// Function to format date as YYYY-MM-DD
function formatDate(date) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Adding 1 because January is 0
  const day = date.getDate().toString().padStart(2, "0");
  return year + "-" + month + "-" + day;
}

function openUserDropdown() {
  document.getElementById("userdropdown").classList.remove("hidden");
  document.getElementById("userdropdownbackdrop").classList.remove("hidden");
}

function closeUserDropdown() {
  document.getElementById("searchUser").value = "";
  document.getElementById("userdropdown").classList.add("hidden");
  document.getElementById("userdropdownbackdrop").classList.add("hidden");
  updateDisplay("");
}

function GenerateReport(event) {
  event.preventDefault();
  const exportTo = document.getElementById("exportTo").value;
  if (exportTo === "excel") {
    generateCSV();
  } else {
    generatePDF();
  }
}

function generatePDF() {
  const startDate = document.getElementById("startDate").value;
  const endDate = document.getElementById("endDate").value;
  const statusFilter = document.getElementById("statusFilter").value;

  const usernames = selectedUsers.map((user) => user.username).join(",");

  // Redirect to another page
  // Construct the URL with query parameters
  let url = `/tms/preview?`;
  url += "startDate=" + encodeURIComponent(startDate);
  url += "&endDate=" + encodeURIComponent(endDate);
  url += "&statusFilter=" + encodeURIComponent(statusFilter);
  url += "&usernames=" + encodeURIComponent(usernames);

  // Redirect to the constructed URL
  window.location.href = url;
}

async function generateCSV() {
  const startDate = document.getElementById("startDate").value;
  const endDate = document.getElementById("endDate").value;
  const statusFilter = document.getElementById("statusFilter").value;
  const usernames = selectedUsers.map((user) => user.username).join(",");

  try {
    const response = await fetch(
      `${apiLink}/generatetaskreport?startDate=${startDate}&endDate=${endDate}&statusFilter=${statusFilter}&usernames=${usernames}`,
      {
        method: "GET",
        headers: { "Content-Type": "application/json" },
        redirect: "follow",
      }
    );

    if (!response.ok) {
      throw new Error("Failed to fetch data");
    }

    const tasks = await response.json();

    // Check if tasks data is empty
    if (tasks.length === 0) {
      alert("No tasks available to generate CSV.");
      return;
    }

    // Construct CSV content
    let csvContent = "data:text/csv;charset=utf-8,";

    const userDetails = JSON.parse(localStorage.getItem("user"));
    const { username } = userDetails;

    // Add header information
    const headerInfo = [
      `Created By: ${username}`,
      "Date Generated: " + new Date().toLocaleDateString(),
      `Date Range: ${startDate} to ${endDate}`,
      `Status Filter: ${statusFilter}`,
      `User Filter: ${usernames}`,
      "", // Empty line for separation
    ];
    csvContent += headerInfo.join("\r\n") + "\r\n";

    // Add CSV header
    const header = Object.keys(tasks[0]).join(",");
    csvContent += header + "\r\n";

    // Convert data array to CSV format
    tasks.forEach(function (task) {
      const row = Object.values(task)
        .map((value) => '"' + value + '"')
        .join(",");
      csvContent += row + "\r\n";
    });

    // Create a link element
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");

    // Generate file name with current date
    const currentDate = new Date();
    const fileName = `tasks_${currentDate.toLocaleString()}.csv`;

    link.setAttribute("href", encodedUri);
    link.setAttribute("download", fileName);

    // Append the link to the document
    document.body.appendChild(link);

    // Trigger the click event to download the CSV file
    link.click();
  } catch (error) {
    console.error(error);
  }
}
