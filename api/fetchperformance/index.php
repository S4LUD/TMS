<?php

// Import the necessary controller classes
require_once '../../src/controllers/Users/index.php';
require_once '../../src/controllers/Tasks/index.php';



// Define the endpoint function
function fetchPerformance($user_id = null, $departmentId = null)
{
    function getCurrentWeekDates()
    {
        // Set the timezone to your desired timezone
        date_default_timezone_set('Asia/Manila');

        // Get the current date
        $currentDate = new DateTime();

        // Set the current date to the start of the week (Monday)
        $currentDate->modify('this week');

        // Get the Monday of the current week
        $mondayDate = $currentDate->format('Y-m-d');

        // Set the current date to the end of the week (Sunday)
        $currentDate->modify('this week +6 days');

        // Get the Sunday of the current week
        $sundayDate = $currentDate->format('Y-m-d');

        return array('monday' => $mondayDate, 'sunday' => $sundayDate);
    }

    // Call the fetchPerformance function from the Users controller
    $usersPerformance = Users::fetchPerformance($user_id, $departmentId);

    // Call the fetchPerformance function from the Tasks controller
    $tasksPerformance = Tasks::fetchPerformance($user_id, $departmentId);

    // $tasksCount = Tasks::fetchAllTasks(getCurrentWeekDates()['monday'], getCurrentWeekDates()['sunday'], null, $user_id);

    $statusCounts = json_decode($tasksPerformance, true);

    // Array of statuses to include in the total count
    $statusesToCount = ["DONE", "PENDING", "FAILED", "REJECTED", "LATE", "IN_REVIEW", "IN_PROGRESS"];

    // Initialize total count
    $totalCount = 0;

    // Loop through each status and add its count to the total
    foreach ($statusesToCount as $status) {
        if (isset($statusCounts[$status])) {
            $totalCount += $statusCounts[$status];
        }
    }

    $response = [
        'tasks_count' => $totalCount,
        'users_performance' => json_decode($usersPerformance, true),
        'tasks_performance' => json_decode($tasksPerformance, true)
    ];


    // Return the combined response
    return json_encode($response);
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Set the HTTP response header to indicate JSON content
    header('Content-Type: application/json');

    // Check if user_id is provided in the query parameters
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        // Call the fetchPerformance function with the provided user_id and echo the result
        echo fetchPerformance($user_id, null);
    } elseif (isset($_GET['departmentId'])) {
        $departmentId = $_GET['departmentId'];
        // Call the fetchPerformance function with the provided user_id and echo the result
        echo fetchPerformance(null, $departmentId);
    } else {
        // Call the fetchPerformance function without user_id and echo the result
        echo fetchPerformance(null, null);
    }
} else {
    // If the request method is not GET, return a 405 Method Not Allowed error
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
