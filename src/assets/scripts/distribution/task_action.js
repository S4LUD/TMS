async function handleTaskActionStatus(task, status_id) {
  if (status_id === 1) {
    if (!task?.assigned_users) {
      alert("You cannot done a task that haven't assigned yet");
      return;
    }
  }

  if (status_id === 2) {
    if (!task?.assigned_users && !task?.dueAt) {
      alert("You cannot fail a task that haven't started yet");
      return;
    }
  }

  await fetch(
    `${apiLink}/updatetaskstatus?taskId=${task?.id}&statusId=${status_id}`,
    {
      method: "GET",
      headers: { "Content-Type": "application/json" },
      redirect: "follow",
    }
  )
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
    .catch((error) => console.error(error))
    .finally(async () => {
      tasks = await fetchTasks();
      taskCount.innerText = Math.ceil(tasks.length / itemsPerPage);
      await updateTableForCurrentPage();
      fetchUsers();
    });
}

function showMenu() {
  document.getElementById("actionStatusMenu").classList.remove("hidden");
}

function hideMenu() {
  document.getElementById("actionStatusMenu").classList.add("hidden");
}
