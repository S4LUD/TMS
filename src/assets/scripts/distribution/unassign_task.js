async function handleUnassignTask(task, username) {
  if (!!task?.dueAt) {
    if (task?.status_id === 4) {
      if (username) {
        // Task is assigned, show alert to confirm unassignment
        const confirmed = confirm(
          `The task is assigned to: ${username}. Are you sure you want to unassign the task?`
        );
        if (confirmed) {
          await fetch(`${apiLink}/unassigntask?task_id=${task?.id}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
            redirect: "follow",
          })
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
      } else {
        // Task is not assigned yet, no need for confirmation
        alert("The task is not assigned yet. It's available to unassign.");
      }
    }
  }
}
