const createUserForm = document.getElementById("createUserForm");

createUserForm.addEventListener("submit", async function (event) {
  event.preventDefault();

  const username = document.getElementById("createUsername").value;
  const password = document.getElementById("createPassword").value;
  const departmentId = document.getElementById("createDepartment").value;
  const roleId = document.getElementById("createRole").value;

  const url = `${apiLink}/register?username=${username}&password=${password}&department_id=${departmentId}&role_id=${roleId}`;

 await fetch(url)
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
    .catch((error) => {
      console.error("Error:", error);
    })
    .finally(async () => {
      users = await fetchUsers();
      userCount.innerText = Math.ceil(users.length / itemsPerPage);
      await updateTableForCurrentPage();
      closeCreateUserModal();
      clearCreateInputs();
      fetchUsers();
    });
});
