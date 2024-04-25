async function handleUnblock(userId) {
  try {
    // Ask for confirmation
    const confirmed = confirm("Are you sure you want to unblock this user?");
    if (!confirmed) {
      console.log("Unblock operation cancelled.");
      return;
    }

    const url = `${apiLink}/unblockuser?userId=${userId}`;
    const myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    const requestOptions = {
      method: "GET",
      headers: myHeaders,
      redirect: "follow",
    };

    const response = await fetch(url, requestOptions);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

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
      users = await fetchUsers();
      userCount.innerText = Math.ceil(users.length / itemsPerPage);
      await updateTableForCurrentPage();
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
  } catch (error) {
    console.error(error);
  }
}
