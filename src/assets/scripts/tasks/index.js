const taskTable = document.getElementById("taskTable");
const filterButton = document.getElementById("filterButton");

filterButton.addEventListener("click", function () {
  const startDate = document.getElementById("startDate").value;
  const endDate = document.getElementById("endDate").value;
  fetch(
    `http://localhost/tms/api/fetchalltasks?startDate=${startDate}&endDate=${endDate}`
  )
    .then((response) => response.json())
    .then((users) => updateTable(users));
});

function fetchTasks(params) {
  fetch(`http://localhost/tms/api/fetchalltasks`)
    .then((response) => response.json())
    .then((tasks) => updateTable(tasks));
}

fetchTasks();

function updateTable(tasks) {
  // Clear existing table content
  taskTable.innerHTML = "";

  if (tasks.length === 0) {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td class="py-3.5 pl-6 pr-3 text-left font-semibold">NO TASK FOUND</td>
        `;
    taskTable.appendChild(row);
  } else {
    for (const task of tasks) {
      const dateTime = new Date(task.createdAt);
      const options = {
        weekday: "long",
        day: "numeric",
        month: "long",
        hour: "numeric",
        minute: "numeric",
        hour12: true,
      };
      const formattedDate = new Intl.DateTimeFormat("en-PH", options).format(
        dateTime
      );
      const row = document.createElement("tr");
      row.id = `userRow_${task.id}`;
      row.innerHTML = `
                <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6">
                    ${task.title}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-gray-500">${formattedDate}</td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
                    <span class="bg-blue-500 hover:bg-blue-600 text-white hover:text-gray-100 px-2 py-1 rounded view-btn" style="cursor: pointer">VIEW</span>
                    <span class="bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-100 px-2 py-1 rounded edit-btn" style="cursor: pointer">EDIT</span>
                </td>
            `;
      taskTable.appendChild(row);

      // Add event listeners to VIEW and EDIT buttons
      row
        .querySelector(".view-btn")
        .addEventListener("click", () => handleViewTask(task.id));
      row
        .querySelector(".edit-btn")
        .addEventListener("click", () => handleEditTask(task.id));
    }
  }
}
