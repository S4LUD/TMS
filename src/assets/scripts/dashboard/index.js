function openNotificationModal() {
  populateTables();
  document.getElementById("notification_modal").classList.remove("hidden");
}

function closeNotificationModal() {
  document.getElementById("notification_modal").classList.add("hidden");
}

document.addEventListener("DOMContentLoaded", function () {
  const userDetails = JSON.parse(localStorage.getItem("user"));
  const { role } = userDetails;

  if (role === "EMPLOYEE") {
    const modalOpened = localStorage.getItem("modalOpened");
    if (!modalOpened) {
      populateTables();
      openNotificationModal();
      localStorage.setItem("modalOpened", true);
    }
  }
});

async function populateTables() {
  const userDetails = JSON.parse(localStorage.getItem("user"));
  const { id } = userDetails;

  const response = await fetch(`${apiLink}/fetchnotifications?user_id=${id}`, {
    method: "GET",
    headers: { "Content-Type": "application/json" },
    redirect: "follow",
  });
  const notifications = await response.json();

  const currentDate = new Date();

  const currentTasks = [];
  const nearDeadlineTasks = [];
  const pastDueTasks = [];

  notifications.forEach((notification) => {
    const dueDate = new Date(notification.dueAt);
    const timeDiff = dueDate - currentDate;
    const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert milliseconds to days

    if (daysDiff <= 7 && daysDiff >= 0) {
      // Near deadline task
      nearDeadlineTasks.push(notification);
    } else if (daysDiff < 0) {
      // Past due task
      pastDueTasks.push(notification);
    }
  });

  // Push all tasks to the "currentTasks" section without filtering
  currentTasks.push(...notifications);

  populateTable("all_tasks_body", currentTasks);
  populateTable("ending_due_tasks_body", nearDeadlineTasks);
  populateTable("overdue_tasks_body", pastDueTasks);
}

function populateTable(tbodyId, tasks) {
  const tbody = document.getElementById(tbodyId);
  if (!tbody) {
    console.error("Tbody with ID " + tbodyId + " not found.");
    return;
  }

  let tableHTML = "";
  tasks.forEach((task) => {
    tableHTML += `
            <tr>
                <td class="px-3 py-3.5">${task.title}</td>
                <td class="px-3 py-3.5">${task.username}</td>
                <td class="px-3 py-3.5">${task.task_type}</td>
                <td class="px-3 py-3.5">${task.dueAt}</td>
            </tr>
        `;
  });
  tbody.innerHTML = tableHTML;
}
