const userContainer = document.getElementById("user_list_container");
const maxDisplay = 3;

function assignTask(task_id) {
  console.log(task_id);
  document.getElementById("distributeTask").classList.remove("hidden");
}

function openUserDropdown() {
  document.getElementById("userdropdown").classList.remove("hidden");
  document.getElementById("userdropdownbackdrop").classList.remove("hidden");
}

// function openUserDropdown(query) {
//   const userContainer = document.getElementById("user_list_container");
//   const maxDisplay = 3; // Maximum number of items to display
//   const filteredNames = names.filter((name) =>
//     name.toLowerCase().includes(query.toLowerCase())
//   );

//   userContainer.innerHTML = ""; // Clear the container before updating
//   let displayed = 0; // Track the number of displayed items

//   for (const name of filteredNames) {
//     if (displayed >= maxDisplay) {
//       break; // Stop if maximum display limit is reached
//     }

//     const listItem = document.createElement("div");
//     listItem.classList =
//       "w-full border rounded-md py-2 px-3 hover:border-blue-200 transition duration-75 cursor-pointer";
//     listItem.textContent = name;
//     listItem.addEventListener("click", () => {
//       selectUser(name);
//     });
//     userContainer.appendChild(listItem);

//     displayed++; // Increment the displayed count
//   }

//   document.getElementById("userdropdown").classList.remove("hidden");
//   document.getElementById("userdropdownbackdrop").classList.remove("hidden");
// }

const names = [
  "John Doe",
  "Jane Smith",
  "David Johnson",
  "Sarah Williams",
  "Michael Brown",
  "Maria Garcia",
  "Robert Martinez",
  "Laura Davis",
  "Daniel Clark",
  "Jennifer Rodriguez",
  "William Taylor",
  "Linda Wilson",
  "Richard Anderson",
  "Emily Thomas",
  "Charles Martinez",
  "Jessica Harris",
  "Matthew King",
  "Patricia Scott",
  "James Lee",
  "Karen White",
];

function updateDisplay(query) {
  userContainer.innerHTML = ""; // Clear the container before updating

  let displayed = 0; // Track the number of displayed items
  for (const name of names) {
    // If the query is empty or the name contains the query
    if (!query || name.toLowerCase().includes(query.toLowerCase())) {
      const listItem = document.createElement("div");
      listItem.classList =
        "w-full border rounded-md py-2 px-3 hover:border-blue-200 transition duration-75 cursor-pointer";
      listItem.textContent = name;
      listItem.addEventListener("click", () => {
        selectUser(name);
      });
      userContainer.appendChild(listItem);

      displayed++; // Increment the displayed count
    }

    // Break the loop if the maximum display limit is reached
    if (displayed >= maxDisplay) {
      break;
    }
  }
}

// function openUserDropdown(query) {
//   const userContainer = document.getElementById("user_list_container");
//   const maxDisplay = 3; // Maximum number of items to display
//   const filteredNames = names.filter((name) =>
//     name.toLowerCase().includes(query.toLowerCase())
//   );

//   userContainer.innerHTML = ""; // Clear the container before updating
//   let displayed = 0; // Track the number of displayed items

//   for (const name of filteredNames) {
//     if (displayed >= maxDisplay) {
//       break; // Stop if maximum display limit is reached
//     }

//     const listItem = document.createElement("div");
//     listItem.classList =
//       "w-full border rounded-md py-2 px-3 hover:border-blue-200 transition duration-75 cursor-pointer";
//     listItem.textContent = name;
//     listItem.addEventListener("click", () => {
//       selectUser(name);
//     });
//     userContainer.appendChild(listItem);

//     displayed++; // Increment the displayed count
//   }

//   document.getElementById("userdropdown").classList.remove("hidden");
//   document.getElementById("userdropdownbackdrop").classList.remove("hidden");
// }

// Initial display with no search query
updateDisplay("");

// Function to handle search input
document
  .getElementById("searchUser")
  .addEventListener("input", function (event) {
    updateDisplay(this.value); // Update the display based on the search query
  });

function closeUserDropdown() {
  document.getElementById("searchUser").value = "";
  document.getElementById("userdropdown").classList.add("hidden");
  document.getElementById("userdropdownbackdrop").classList.add("hidden");
}

function selectUser(username) {
  const assignTo = document.getElementById("assignTo");
  assignTo.value = username;
  closeUserDropdown();
}

function closeDistribute() {
  document.getElementById("dueDate").value = "";
  document.getElementById("taskType").value = "";
  document.getElementById("assignTo").value = "";
  document.getElementById("distributeTask").classList.add("hidden");
  document.getElementById("userdropdown").classList.add("hidden");
  document.getElementById("userdropdownbackdrop").classList.add("hidden");
}
