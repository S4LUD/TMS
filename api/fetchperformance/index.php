<?php

// Assume you have fetched the weekly report and user performance data
$weeklyReportData = getWeeklyReport(); // Replace with the actual function or method to fetch weekly report data
$usersPerformanceData = getUsersPerformance(); // Replace with the actual function or method to fetch user performance data

$response = [
    "weekly_report" => formatWeeklyReport($weeklyReportData),
    "users_performance" => formatUsersPerformance($usersPerformanceData),
];

echo json_encode($response);

function formatWeeklyReport($weeklyReportData)
{
    $formattedWeeklyReport = [];

    foreach ($weeklyReportData as $week) {
        $formattedWeeklyReport[] = [
            "from_date" => $week["from_date"],
            "to_date" => $week["to_date"],
            "status_score" => [
                "done" => $week["status"] === "DONE" ? $week["status_count"] : 0,
                "failed" => $week["status"] === "FAILED" ? $week["status_count"] : 0,
                "rejected" => $week["status"] === "REJECTED" ? $week["status_count"] : 0,
                "pending" => $week["status"] === "PENDING" ? $week["status_count"] : 0,
                "late" => $week["status"] === "LATE" ? $week["status_count"] : 0,
                "in_review" => $week["status"] === "IN REVIEW" ? $week["status_count"] : 0,
                "in_progress" => $week["status"] === "IN PROGRESS" ? $week["status_count"] : 0,
            ],
        ];
    }

    return $formattedWeeklyReport;
}

function formatUsersPerformance($usersPerformanceData)
{
    $formattedUsersPerformance = [];

    foreach ($usersPerformanceData as $user) {
        $formattedUsersPerformance[] = [
            "username" => $user["username"],
            "done" => $user["done"],
            "failed" => $user["failed"],
            "in_progress" => $user["in_progress"],
        ];
    }

    return $formattedUsersPerformance;
}

// Replace these functions with your actual database queries or API calls
function getWeeklyReport()
{
    // Your logic to fetch weekly report data from the database or API
    // Example data for illustration purposes
    return [
        ["from_date" => "2024-03-03 00:00:00", "to_date" => "2024-03-09 23:59:59", "status" => "DONE", "status_count" => 0],
        ["from_date" => "2024-03-10 00:00:00", "to_date" => "2024-03-16 23:59:59", "status" => "IN PROGRESS", "status_count" => 0],
    ];
}

function getUsersPerformance()
{
    // Your logic to fetch user performance data from the database or API
    // Example data for illustration purposes
    return [
        ["username" => "User1", "done" => 0, "failed" => 0, "in_progress" => 0],
        ["username" => "User2", "done" => 0, "failed" => 0, "in_progress" => 0],
    ];
}
