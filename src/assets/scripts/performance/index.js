// Function to fetch data from the API
function fetchDataFromAPI(url, callback) {
  fetch(url)
    .then((response) => response.json())
    .then((data) => callback(data))
    .catch((error) => console.error("Error fetching data:", error));
}

// API URL
var apiUrl = "http://localhost/tms/api/fetchperformance/";

function calculateCompletionRate(apiData) {
  // Get the total number of tasks
  const totalTasks = apiData.tasks_count;

  // Get the total number of completed tasks
  const totalCompletedTasks =
    apiData.tasks_performance.DONE + apiData.tasks_performance.LATE;

  const totlaNotCompletedTask =
    apiData.tasks_performance.PENDING +
    apiData.tasks_performance.FAILED +
    apiData.tasks_performance.REJECTED +
    apiData.tasks_performance.IN_REVIEW +
    apiData.tasks_performance.IN_PROGRESS;

  // Calculate the total completed tasks by subtracting failed tasks
  const totalCompleted = totalTasks - totlaNotCompletedTask;

  // Calculate the completion rate percentage
  let completionRate = 0;
  if (totalTasks > 0) {
    completionRate = (totalCompleted / totalTasks) * 100;
  }

  return {
    totalTasks,
    totalCompleted,
    completionRate: completionRate,
  };
}

function renderCompletionData(completionData) {
  const completionDiv = document.getElementById("completion-data");
  completionDiv.innerHTML = `
        <p>Total Tasks: ${completionData.totalTasks}</p>
        <p>Total Completed: ${completionData.totalCompleted}</p>
        <p>Completion Rate: ${completionData.completionRate}</p>
    `;
}

function renderCompletionRate(completionRate) {
  const completionRateElement = document.getElementById("completion-rate");
  completionRateElement.textContent = completionRate.toFixed(2) + "%";

  const completionCircle = document.getElementById("completion-circle");
  const radius = 9; // Updated radius to match the circle radius
  const circumference = 2 * Math.PI * radius;
  const progress = completionRate / 100;
  const offset = circumference * (1 - progress); // Adjusted offset calculation

  completionCircle.style.strokeDasharray = `${circumference}`;
  completionCircle.style.strokeDashoffset = offset;
}

const statusColors = {
  DONE: ["rgba(75, 192, 192, 0.5)", "rgba(75, 192, 192, 1)"], // Green
  FAILED: ["rgba(255, 99, 132, 0.5)", "rgba(255, 99, 132, 1)"], // Red
  REJECTED: ["rgba(255, 99, 132, 0.5)", "rgba(255, 99, 132, 1)"], // Red
  PENDING: ["rgba(255, 206, 86, 0.5)", "rgba(255, 206, 86, 1)"], // Yellow
  LATE: ["rgba(153, 102, 255, 0.5)", "rgba(153, 102, 255, 1)"], // Purple
  "IN REVIEW": ["rgba(54, 162, 235, 0.5)", "rgba(54, 162, 235, 1)"], // Blue
  "IN PROGRESS": ["rgba(54, 162, 235, 0.5)", "rgba(54, 162, 235, 1)"], // Blue
};

// Fetch data from the API
fetchDataFromAPI(apiUrl, function (apiData) {
  // Extract tasks performance data

  const completionData = calculateCompletionRate(apiData);
  renderCompletionRate(completionData.completionRate);

  var tasksPerformance = apiData.tasks_performance || {};

  // Prepare data for Chart.js
  var taskLabels = Object.keys(tasksPerformance);
  var taskValues = Object.values(tasksPerformance);

  // Get reference to canvas element
  var ctx = document.getElementById("tasksChart").getContext("2d");

  // Create Chart.js chart
  var tasksChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: taskLabels,
      datasets: [
        {
          label: "Tasks Performance",
          data: taskValues,
          backgroundColor: [
            statusColors["DONE"][0],
            statusColors["FAILED"][0],
            statusColors["REJECTED"][0],
            statusColors["PENDING"][0],
            statusColors["LATE"][0],
            statusColors["IN REVIEW"][0],
            statusColors["IN PROGRESS"][0],
          ],
          borderColor: [
            "rgba(75, 192, 192, 1)",
            "rgba(255, 99, 132, 1)",
            "rgba(255, 206, 86, 1)",
            "rgba(54, 162, 235, 1)",
            "rgba(153, 102, 255, 1)",
            "rgba(255, 159, 64, 1)",
            "rgba(231,233,237, 1)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          ticks: {
            stepSize: 1,
          },
        },
      },
    },
  });
});
