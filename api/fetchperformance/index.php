<?php

// Import the necessary controller classes
require_once '../../src/controllers/Users/index.php';
require_once '../../src/controllers/Tasks/index.php';



// Define the endpoint function
function fetchPerformance()
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
    $usersPerformance = Users::fetchPerformance();

    // Call the fetchPerformance function from the Tasks controller
    $tasksPerformance = Tasks::fetchPerformance();

    $tasksCount = Tasks::fetchAllTasks(getCurrentWeekDates()['monday'], getCurrentWeekDates()['sunday'], null, null);

    // Combine the results into a single response
    if (is_array(json_decode($tasksCount, true))) {
        $response = [
            'tasks_count' => count(json_decode($tasksCount, true)),
            'users_performance' => json_decode($usersPerformance, true),
            'tasks_performance' => json_decode($tasksPerformance, true)
        ];
    } else {
        // Handle the case where $tasksCount is not an array
        // For example, you can set 'tasks_count' to 0 or display an error message
        $response = [
            'tasks_count' => 0,
            'users_performance' => json_decode($usersPerformance, true),
            'tasks_performance' => json_decode($tasksPerformance, true)
            // You can add additional error handling here if needed
        ];
    }

    // Return the combined response
    return json_encode($response);
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Set the HTTP response header to indicate JSON content
    header('Content-Type: application/json');

    // Call the fetchPerformance function and echo the result
    echo fetchPerformance();
} else {
    // If the request method is not GET, return a 405 Method Not Allowed error
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
