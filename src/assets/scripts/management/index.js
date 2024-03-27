const userTable = document.getElementById("userTable");

function fetchUsers() {
  fetch(`http://localhost/tms/api/fetchallusers`)
    .then((response) => response.json())
    .then((users) => updateTable(users));
}

fetchUsers();

async function updateTable(users) {
  // Clear existing table content
  userTable.innerHTML = "";

  if (users.length === 0) {
    const row = document.createElement("tr");
    row.innerHTML = `
        <td class="py-3.5 pl-6 pr-3 text-left font-semibold text-red-900">ACCOUNT NOT FOUND</td>
    `;
    userTable.appendChild(row);
  } else {
    for (const user of users) {
      const row = document.createElement("tr");

      const { permissions } = await fetch(
        `http://localhost/tms/api/fetchuser?searchTerm=${user.username}`,
        {
          method: "GET",
          redirect: "follow",
        }
      )
        .then((response) => response.json())
        .then((result) => result)
        .catch((error) => console.error(error));

      row.innerHTML = `
            <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                ${user.username}
            </td>
            <td class="whitespace-nowrap px-3 py-4 text-gray-500">${
              user.role
            }</td>
            <td class="whitespace-nowrap px-3 py-4 text-gray-500">${
              user.department
            }</td>
            <td class="whitespace-nowrap px-3 py-4 text-gray-500">
                <div class="${
                  user.status === "ACTIVE" ? "text-green-500" : "text-red-500"
                }">
                    ${user.status}
                </div>
            </td>
            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
                ${
                  permissions.account_management.source.delete
                    ? '<span class="bg-red-500 hover:bg-red-600 text-white hover:text-gray-100 px-2 py-1 rounded delete-btn" style="cursor: pointer"><i class="fa-solid fa-trash-can"></i></span>'
                    : ""
                }
                ${
                  permissions.account_management.source.view
                    ? '<span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded view-btn" style="cursor: pointer"><i class="fa-solid fa-eye"></i></span>'
                    : ""
                }
                ${
                  permissions.account_management.source.edit
                    ? '<span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded edit-btn" style="cursor: pointer"><i class="fa-solid fa-pen-to-square"></i></span>'
                    : ""
                }
                ${
                  permissions.account_management.source.permissions
                    ? '<span class="bg-gray-500 hover:bg-gray-600 text-white hover:text-gray-100 px-2 py-1 rounded permission-btn" style="cursor: pointer"><i class="fa-solid fa-sliders"></i></span>'
                    : ""
                }
            </td>
        `;
      userTable.appendChild(row);

      // Add event listeners to VIEW and EDIT buttons
      row
        .querySelector(".delete-btn")
        .addEventListener("click", () => console.log(user.id));
      row
        .querySelector(".view-btn")
        .addEventListener("click", () => console.log(user.id));
      row
        .querySelector(".edit-btn")
        .addEventListener("click", () => console.log(user.id));
    }
  }
}

function clearCreateInputs() {
  document.getElementById("createUsername").value = "";
  document.getElementById("createPassword").value = "";
  document.getElementById("createDepartment").value = "";
  document.getElementById("createRole").value = "";
}

// Open the modal when the button is clicked
document
  .getElementById("openCreateUserModal")
  .addEventListener("click", openCreateUserModal);

// Function to open the modal
function openCreateUserModal() {
  document.getElementById("createUserModalOverlay").classList.remove("hidden");
}

// Function to close the modal
function closeCreateUserModal() {
  document.getElementById("createUserModalOverlay").classList.add("hidden");
  clearCreateInputs();
}
