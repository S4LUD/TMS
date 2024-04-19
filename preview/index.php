<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/tms/src/config/config.php');
$userData = json_decode($_SESSION['user'], true);


// Declare a variable to store the result
$response = '';

// Check if required parameters are set
if (isset($_GET['startDate'], $_GET['endDate'])) {
    // Construct the API endpoint URL with required parameters
    $url = $apiLink . '/generatetaskreport?' . http_build_query([
        'startDate' => $_GET['startDate'],
        'endDate' => $_GET['endDate']
    ]);

    // Add optional parameters if provided
    if (isset($_GET['statusFilter'])) {
        $url .= '&statusFilter=' . $_GET['statusFilter'];
    }
    if (isset($_GET['usernames'])) {
        $url .= '&usernames=' . $_GET['usernames'];
    }

    // Create a stream context
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json'
        ]
    ]);

    // Make the GET request and fetch the response
    $response = file_get_contents($url, false, $context);

    // Check if response is received
    if ($response === false) {
        $response = "Error fetching data from the API.";
    }
} else {
    // Display an error message if required parameters are missing
    $response = "Error: Required parameters are missing.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Report</title>
    <script src="https://cdn.tailwindcss.com/3.4.1"></script>
    <link rel="icon" href="/tms/src/image/logo.png" type="image/png">
</head>

<body>
    <div id="capture" class="max-w-3xl mx-auto p-4 bg-white rounded-lg mt-8">
        <h2 class="text-2xl font-bold mb-4">Report</h2>
        <div class="mb-4">
            <p><strong>Created By:</strong> <?php echo $userData['username']; ?></p>
            <p><strong>Date Generated:</strong> <?php echo date("F j, Y"); ?></p>
            <p><strong>Date Range:</strong> <?php echo $_GET['startDate']; ?> to <?php echo $_GET['endDate']; ?></p>
            <p><strong>Status Filter:</strong> <?php echo $_GET['statusFilter'] ? $_GET['statusFilter'] : 'Not Set'; ?></p>
            <p><strong>User Filter:</strong> <?php echo $_GET['usernames'] ? $_GET['usernames'] : 'Not Set'; ?></p>
        </div>
        <div>
            <table class="min-w-full border">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium uppercase tracking-wider border border-solid">Title</th>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium uppercase tracking-wider border border-solid">Details</th>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium uppercase tracking-wider border border-solid">Status</th>
                        <th class="px-6 py-3 text-left text-xs leading-4 font-medium uppercase tracking-wider border border-solid">Assigned To</th>
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                <tbody class="bg-white border">
                    <?php
                    // Loop through each task in the response and create table rows
                    if (!empty($response)) {
                        $tasks = json_decode($response, true); // Decode the JSON response
                        // Check if tasks array is not empty
                        if (!empty($tasks)) {
                            foreach ($tasks as $task) {
                                // Output a table row for each task
                                echo '<tr>';
                                echo '<td class="px-6 py-4 whitespace-no-wrap border border-solid">' . $task['title'] . '</td>';
                                echo '<td class="px-6 py-4 whitespace-no-wrap border border-solid">' . $task['detail'] . '</td>';
                                echo '<td class="px-6 py-4 whitespace-no-wrap border border-solid">' . $task['status'] . '</td>';
                                echo '<td class="px-6 py-4 whitespace-no-wrap border border-solid">' . $task['username'] . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            // If tasks array is empty, display a message
                            echo '<tr><td colspan="4" class="px-6 py-4 whitespace-no-wrap border border-solid">No tasks found.</td></tr>';
                        }
                    } else {
                        // If response is empty, display an error message
                        echo '<tr><td colspan="4" class="px-6 py-4 whitespace-no-wrap border border-solid">Error: No response from the API.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex items-center justify-center gap-1">
        <button onclick="printReport()" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-center">
            Click Here
        </button>
        <button onclick="returnToTask()" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-center">
            Go Back
        </button>
    </div>

    <script>
        function printReport() {
            var printContents = document.getElementById('capture').innerHTML;
            var originalContents = document.body.innerHTML;

            // Temporarily replace the entire body with the content of the capture div
            document.body.innerHTML = printContents;

            // Trigger the print dialog
            window.print();

            // Restore the original body content
            document.body.innerHTML = originalContents;
        }

        function returnToTask() {
            window.location.href = "/tms/report";
        }
    </script>
</body>

</html>