async function handleDeleteTask(taskId) {
  // Ask for confirmation using window.confirm
  const isConfirmed = window.confirm(
    `Are you sure you want to delete this task?`
  );

  if (isConfirmed) {
    await fetch(`http://localhost/tms/api/deletetask?task_id=${taskId}`, {
      method: "POST",
      redirect: "follow",
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.message === "Successfully deleted task") {
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
        } else {
          Toastify({
            text: result.message,
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
      });
  } else {
    // User canceled, do nothing
    console.log("Task Deletion Canceled");
  }
}
