<h1 class="text-xl font-bold mb-4">Account Management</h1>
<button id="openCreateUserModal" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75">Create a user</button>

<div class="bg-white p-4 mt-4 border rounded">
    <!-- <form id="searchForm" onsubmit="return false;" class="flex">
        <div class="flex flex-col">
            <input type="text" id="searchAccount" name="searchAccount" placeholder="Search username, id" class="border rounded-md py-2 px-4 focus:outline-none focus:border-blue-500 transition duration-75">
            <small class="pl-2 text-gray-500">Search is case-sensitive</small>
        </div>
        <button type="button" id="searchButton" class="h-fit ml-1 font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Search</button>
        <button type="button" id="clearButton" class="h-fit hidden ml-1 font-semibold bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Clear</button>
    </form> -->

    <form id="searchForm" onsubmit="return false;">
        <div class="flex flex-col sm:flex-row gap-1">
            <input type="text" id="searchAccount" name="searchAccount" placeholder="Search username, id" class="border rounded-md py-2 px-4 focus:outline-none focus:border-blue-500 transition duration-75">
            <button type="button" id="searchButton" class="font-semibold bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Search</button>
            <button type="button" id="clearButton" class="hidden font-semibold bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 hover:text-gray-100 transition duration-75 min-w-50 max-w-50">Clear</button>
        </div>
        <small class="pl-2 text-gray-500 self-end">Search is case-sensitive</small>
    </form>



    <div class="overflow-x-auto mt-4 select-none">
        <div class="md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">USERNAME</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">ROLE</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">DEPARTMENT</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">STATUS</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="userTable"></tbody>
            </table>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/modals/register_user_modal.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const createUserForm = document.getElementById('createUserForm');
        const searchButton = document.getElementById('searchButton');
        const clearButton = document.getElementById('clearButton');
        const userTable = document.getElementById('userTable');
        let isSearchPerformed = false;

        function fetchUsers(params) {
            fetch(`http://localhost/tms/api/fetchallusers`)
                .then(response => response.json())
                .then(users => updateTable(users));
        }
        fetchUsers();

        searchButton.addEventListener('click', function() {
            const searchAccount = document.getElementById('searchAccount').value;

            if (searchAccount === "") {
                return;
            }

            clearButton.classList.remove('hidden');
            fetch(`http://localhost/tms/api/fetchallusers?searchaccount=${searchAccount}`)
                .then(response => response.json())
                .then(users => updateTable(users));

        });

        clearButton.addEventListener('click', function() {
            isSearchPerformed = false;
            document.getElementById('searchAccount').value = "";
            clearButton.classList.add('hidden');
            fetch(`http://localhost/tms/api/fetchallusers`)
                .then(response => response.json())
                .then(users => updateTable(users));
        });

        function updateTable(users) {
            // Clear existing table content
            userTable.innerHTML = '';

            if (users.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td class="py-3.5 pl-6 pr-3 text-left font-semibold text-red-900">ACCOUNT NOT FOUND</td>
            `;
                userTable.appendChild(row);
            } else {
                for (const user of users) {
                    const row = document.createElement('tr');
                    row.id = `userRow_${user.id}`;
                    row.innerHTML = `
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                        ${user.username}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">${user.role}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">${user.department}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-500">
                        <div class="${user.status === 'ACTIVE' ? 'text-green-500' : 'text-red-500'}">
                            ${user.status}
                        </div>
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right font-medium sm:pr-6">
                        <span class="text-blue-600 hover:text-blue-900" style="cursor: pointer">EDIT</span>
                    </td>
                `;
                    userTable.appendChild(row);
                }
            }
        }

        createUserForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const username = document.getElementById('createUsername').value;
            const password = document.getElementById('createPassword').value;
            const departmentId = document.getElementById('createDepartment').value;
            const roleId = document.getElementById('createRole').value;

            const url = `http://localhost/tms/api/register?username=${username}&password=${password}&department_id=${departmentId}&role_id=${roleId}`;

            console.log(url);

            fetch(url)
                .then(response => response.json())
                .then(result => {
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
                        closeCreateUserModal();
                        clearCreateInputs();
                        fetchUsers();
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
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });

    function clearCreateInputs() {
        document.getElementById('createUsername').value = "";
        document.getElementById('createPassword').value = "";
        document.getElementById('createDepartment').value = "";
        document.getElementById('createRole').value = "";
    }

    // Open the modal when the button is clicked
    document.getElementById('openCreateUserModal').addEventListener('click', openCreateUserModal);

    // Function to open the modal
    function openCreateUserModal() {
        document.getElementById('createUserModalOverlay').classList.remove('hidden');
    }

    // Function to close the modal
    function closeCreateUserModal() {
        document.getElementById('createUserModalOverlay').classList.add('hidden');
        clearCreateInputs();
    }
</script>