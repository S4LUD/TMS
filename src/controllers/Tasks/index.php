<?php
require_once __DIR__ . '/../../config/db.php';

class Tasks
{
    public static function getPDO()
    {
        global $db;
        return $db;
    }

    public static function fetchAllTasks($startDate, $endDate)
    {
        global $db;

        try {
            $query = "SELECT id, title, createdAt, task_type, dueAt, user_id, startedAt, endedAt FROM tasks";

            // Check if date range is provided
            if (!empty($startDate) && !empty($endDate)) {
                $query .= " WHERE createdAt BETWEEN :startDate AND :endDate";
            }

            // Prepare the statement
            $stmt = $db->prepare($query);

            // Bind parameters if date range is provided
            if (!empty($startDate) && !empty($endDate)) {
                $startDate .= " 00:00:00";
                $endDate .= " 23:59:59";
                $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
                $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            }

            // Execute the statement
            $stmt->execute();

            // Fetch the results
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return JSON-encoded results
            return json_encode($results);
        } catch (PDOException $e) {
            // Handle database error
            http_response_code(500); // Internal Server Error
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public static function newFile($filename, $file_size, $destination,  $task_id)
    {
        global $db;

        try {
            $stmt = $db->prepare("INSERT INTO `files` (`filename`, `file_size`, `file_destination`, `task_id`) VALUES (:filename, :file_size, :destination, :task_id)");

            $stmt->bindParam(':filename', $filename);
            $stmt->bindParam(':file_size', $file_size);
            $stmt->bindParam(':destination', $destination);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function newTask($title, $details, $role, $createdBy)
    {
        global $db;

        try {
            // Fetch the role name based on role
            $stmtFetchRole = $db->prepare("SELECT * FROM role WHERE role = :role");
            $stmtFetchRole->bindParam(':role', $role);
            $stmtFetchRole->execute();
            $roleData = $stmtFetchRole->fetch(PDO::FETCH_ASSOC);
            $roleId = $roleData['id'];

            // Check if the fetched role is a public role
            $stmtCheckPublicRole = $db->prepare("SELECT COUNT(*) AS count FROM public_role WHERE role_id = :roleId");
            $stmtCheckPublicRole->bindParam(':roleId', $roleId);
            $stmtCheckPublicRole->execute();
            $publicRoleCount = $stmtCheckPublicRole->fetch(PDO::FETCH_ASSOC)['count'];

            // Determine the status ID based on whether the role is public or not
            $statusId = $publicRoleCount > 0 ? 6 : 4; // Assign status ID 6 for public roles, otherwise default to 4

            // Prepare the SQL statement for insertion
            $stmt = $db->prepare("INSERT INTO `tasks` (`title`, `detail`, `status_id`, `createdBy`) VALUES (:title, :details, :status_id, :createdBy)");
            // Bind parameters
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':details', $details);
            $stmt->bindParam(':status_id', $statusId);
            $stmt->bindParam(':createdBy', $createdBy);
            // Execute the statement
            $stmt->execute();
            // Get the ID of the inserted task
            $taskId = $db->lastInsertId();
            // Return the task ID as an object
            return (object)['id' => $taskId];
        } catch (PDOException $e) {
            // Handle database errors
            return null; // or throw an exception based on your error handling strategy
        }
    }

    public static function viewTask($task_id)
    {
        global $db;

        $stmt = $db->prepare(
            "SELECT 
                tasks.title, 
                tasks.detail, 
                CASE WHEN tasks.dueAt IS NULL THEN 'Not Set' ELSE tasks.dueAt END AS dueAt,
                COALESCE(users.username, 'N/A') AS assigned,
                tasks.createdAt, 
                tasks.updatedAt, 
                tasks.startedAt, 
                tasks.endedAt,
                files.id AS file_id,
                files.filename, 
                files.file_size, 
                files.file_destination,
                task_status.status
            FROM 
                tasks 
                LEFT JOIN files ON tasks.id = files.task_id 
                JOIN task_status ON tasks.status_id = task_status.id 
                LEFT JOIN users ON tasks.user_id = users.id
            WHERE tasks.id = :task_id"
        );
        $stmt->bindParam(':task_id', $task_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            return json_encode(['error' => 'Task not found']);
        }

        $formattedResult = [
            'title' => $results[0]['title'],
            'detail' => $results[0]['detail'],
            'assigned' => $results[0]['assigned'],
            'status' => $results[0]['status'],
            'dueAt' => $results[0]['dueAt'],
            'createdAt' => $results[0]['createdAt'],
            'updatedAt' => $results[0]['updatedAt'],
            'startedAt' => $results[0]['startedAt'],
            'endedAt' => $results[0]['endedAt'],
        ];

        if (!empty($results[0]['filename'])) {
            $formattedResult['files'] = array_map(function ($file) {
                return [
                    'file_id' => $file['file_id'],
                    'filename' => $file['filename'],
                    'file_size' => $file['file_size'],
                    'file_destination' => $file['file_destination'],
                ];
            }, $results);
        } else {
            $formattedResult['files'] = []; // No files associated with the task
        }

        return json_encode($formattedResult);
    }

    public static function updateTask($taskId, $title, $details)
    {
        global $db;

        $stmt = $db->prepare("UPDATE tasks SET title = :title, detail = :details WHERE id = :taskId");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function removeFile($file_id)
    {
        global $db;

        // Retrieve file information from the database
        $stmt = $db->prepare("SELECT * FROM `files` WHERE `id` = :file_id");
        $stmt->bindParam(':file_id', $file_id, PDO::PARAM_INT);
        $stmt->execute();

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($file) {
            // Decode the JSON-encoded string to an array
            if ($file['file_destination'] !== null) {
                $filePath = $file['file_destination'];
                // Use the decoded array to get the file destination
                unlink($filePath);

                // Delete the file record from the database
                $deleteStmt = $db->prepare("DELETE FROM `files` WHERE `id` = :file_id");
                $deleteStmt->bindParam(':file_id', $file_id, PDO::PARAM_INT);
                $deleteStmt->execute();

                // Check if any rows were affected
                $rowCount = $deleteStmt->rowCount();

                if ($rowCount > 0) {
                    return ['message' => "file removed successfully"];; // Returns true if the file was removed successfully
                }
            }
        }

        return ['error' => "File not found in the database"]; // File not found in the database
    }

    public static function deleteTask($task_id)
    {
        global $db;

        // Delete task from the database
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = :task_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        // Check if any rows were affected
        $rowCount = $stmt->rowCount();

        return $rowCount > 0; // Returns true if the task was deleted successfully
    }

    public static function fetchPerformance()
    {
        global $db;

        // Initialize status counts
        $statuses = [
            "DONE" => 0,
            "FAILED" => 0,
            "REJECTED" => 0,
            "PENDING" => 0,
            "LATE" => 0,
            "IN_REVIEW" => 0,
            "IN_PROGRESS" => 0
        ];

        try {
            $weekDates = getCurrentWeekDates();

            $stmt = $db->prepare("SELECT
            task_status.status,
            COUNT(*) AS status_count
        FROM
            tasks
        LEFT JOIN
            task_status ON tasks.status_id = task_status.id
        WHERE
            DATE(tasks.createdAt) BETWEEN :start_date AND :end_date
        GROUP BY
            task_status.status
        ORDER BY
            task_status.status");

            $stmt->bindParam(':start_date', $weekDates['monday']);
            $stmt->bindParam(':end_date', $weekDates['sunday']);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Update status counts
            foreach ($results as $row) {
                $status = strtoupper($row['status']);
                $statuses[$status] = $row['status_count'];
            }

            // Return the statuses
            return json_encode($statuses);
        } catch (PDOException $e) {
            // Handle database connection error
            http_response_code(500); // Internal Server Error
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public static function distributeTask($task_id, $task_type, $user_id, $dueAt)
    {
        global $db;
        try {
            $stmt = $db->prepare("UPDATE `tasks` SET `user_id`=:user_id, `task_type`=:task_type, `dueAt`=:dueAt WHERE `id`=:taskId");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':task_type', $task_type);
            $stmt->bindParam(':dueAt', $dueAt);
            $stmt->bindParam(':taskId', $task_id, PDO::PARAM_INT);

            $stmt->execute();

            // Check if any rows were affected by the update
            if ($stmt->rowCount() > 0) {
                return true; // Task successfully distributed
            } else {
                return false; // Task not found or no changes made
            }
        } catch (PDOException $e) {
            // Handle database errors
            error_log("Error distributing task: " . $e->getMessage());
            return false;
        }
    }
}
