async function deleteAccount(userId) {
  const isConfirmed = confirm(`Are you sure you want to delete this account?`);
  if (isConfirmed) {
    await fetch(`${apiLink}/deleteuser?userId=${userId}`)
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
      .finally(() => {
        closeCreateUserModal();
        fetchUsers();
      });
  }
}
