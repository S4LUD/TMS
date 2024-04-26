// Function to fetch data from the API
function fetchDataFromAPI(url, callback) {
  fetch(url)
    .then((response) => response.json())
    .then((data) => callback(data))
    .catch((error) => console.error("Error fetching data:", error));
}

const userDetails = JSON.parse(localStorage.getItem("user"));
const { visibility, id, role, department_id } = userDetails;

// API URL
let apiUrl;
console.log(userDetails);
if (role === "SUPER ADMIN") {
  apiUrl = `${apiLink}/fetchperformance`;
} else if (visibility === "PUBLIC") {
  apiUrl = `${apiLink}/fetchperformance?user_id=${id}`;
} else if (visibility === "PRIVATE") {
  apiUrl = `${apiLink}/fetchperformance?departmentId=${department_id}`;
}

function calculateCompletionRate(apiData) {
  // Get the total number of tasks
  const totalTasks = apiData.tasks_count;

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
        <div class="flex flex-col">
        <p class="font-medium text-lg">Completion Rate</p>
        <p class="font-medium text-sm text-gray-500">${completionData.totalCompleted} / ${completionData.totalTasks} tasks</p>
        </div>
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

function calculateStars(userPerformance, totalTasks) {
  const stars = [];
  userPerformance.forEach((user) => {
    const totalCompletedTasks = parseInt(user.done);
    const completedTasksScore = totalCompletedTasks * 2;
    // Calculate performance rating based on total score and total tasks
    const performanceRating = calculatePerformanceRating(
      completedTasksScore,
      parseInt(totalTasks)
    );

    stars.push({
      username: user.username,
      stars: performanceRating,
      done: user.done,
      late: user.late,
      failed: user.failed,
    });
  });
  return stars;
}

function calculatePerformanceRating(totalScore, totalTasks) {
  // Calculate percentage based on total score and total tasks
  const percentage = (totalScore / (totalTasks * 2)) * 100; // Assuming each task has a weight of 2
  // Assuming percentage is out of 100, mapping it to 1 to 5 stars
  const rating = Math.ceil(percentage / 20);
  return rating;
}

// Function to display stars based on the rating
function displayStars(rating) {
  let stars = "";
  for (let i = 0; i < 5; i++) {
    if (i < rating) {
      stars += '<i class="fas fa-star text-yellow-500"></i>'; // Filled star
    } else {
      stars += '<i class="far fa-star text-gray-300"></i>'; // Empty star
    }
  }
  return stars;
}

// Fetch data from the API
fetchDataFromAPI(apiUrl, function (apiData) {
  console.log(apiData);
  const completionData = calculateCompletionRate(apiData);
  renderCompletionRate(completionData.completionRate);
  renderCompletionData(completionData);

  const usersStars = calculateStars(
    apiData.users_performance,
    apiData.tasks_count
  );

  const userListDiv = document.getElementById("users_list");

  // Function to generate HTML for user stars
  function generateUserStarsHTML(usersStars) {
    let html =
      '<ul class="list-none flex flex-col gap-2 divide-y divide-gray-200">';

    usersStars.forEach((user) => {
      // Define the legend and its corresponding color based on star count
      let legend = "";
      let legendColor = "";
      if (user.stars <= 1) {
        legend = "Poor";
        legendColor = "text-red-500"; // Red for Poor
      } else if (user.stars <= 2) {
        legend = "Fair";
        legendColor = "text-yellow-500"; // Yellow for Fair
      } else if (user.stars <= 3) {
        legend = "Good";
        legendColor = "text-yellow-300"; // Light Yellow for Good
      } else if (user.stars <= 4) {
        legend = "Very Good";
        legendColor = "text-green-500"; // Green for Very Good
      } else {
        legend = "Excellent";
        legendColor = "text-green-800"; // Dark Green for Excellent
      }

      html += `<li class="flex flex-row justify-between lg:w-full min-w-72 py-2">
      <div class="flex flex-col">
        <span>${user.username}</span>
        <div class="flex items-center justify-center gap-2">
          <span>${displayStars(user.stars)}</span>
          <span class="text-sm ${legendColor}">${legend}</span> <!-- Display legend with color -->
        </div>
      </div>
      <div class="flex gap-1">
        <div class="flex justify-center items-center bg-green-500 h-fit w-6 h-5 text-sm text-white rounded">
        ${user.done}
        </div>
        <div class="flex justify-center items-center bg-yellow-500 h-fit w-6 h-5 text-sm text-white rounded">
        ${user.late}
        </div>
        <div class="flex justify-center items-center bg-red-500 h-fit w-6 h-5 text-sm text-white rounded">
        ${user.failed}
        </div>
      </div>
    </li>`;
    });

    html += "</ul>";
    return html;
  }

  // Update the users_list div with the generated HTML
  if (userListDiv) {
    userListDiv.innerHTML = generateUserStarsHTML(usersStars);
  }

  var tasksPerformance = apiData.tasks_performance || {};

  let filteredStatus = {};

  if (visibility === "PUBLIC") {
    // If visibility is PUBLIC, filter to display only DONE, PENDING, and FAILED
    filteredStatus = {
      DONE: tasksPerformance.DONE || 0,
      PENDING: tasksPerformance.PENDING || 0,
      FAILED: tasksPerformance.FAILED || 0,
    };
  } else {
    // If visibility is not PUBLIC, keep the original tasksPerformance object
    filteredStatus = tasksPerformance;
  }

  // Prepare data for Chart.js
  var taskLabels = Object.keys(filteredStatus);
  var taskValues = Object.values(filteredStatus);
  // Get reference to canvas element
  var ctx = document.getElementById("tasksChart").getContext("2d");

  // Create Chart.js chart

  if (visibility === "PUBLIC") {
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
              statusColors["PENDING"][0],
              statusColors["FAILED"][0],
            ],
            borderColor: [
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            ticks: { stepSize: 5 },
          },
        },
      },
    });
  } else {
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
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
              "rgba(0, 0, 0, 0)",
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            ticks: { stepSize: 5 },
          },
        },
      },
    });
  }
});
