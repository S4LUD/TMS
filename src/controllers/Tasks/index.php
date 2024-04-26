<?php
require_once __DIR__ . '/../../config/db.php';

class Tasks
{
    public static function getPDO()
    {
        global $db;
        return $db;
    }

    public static function fetchAllTasks($startDate = "", $endDate = "", $role = null, $userId = null)
    {
        global $db;

        try {
            $query = "SELECT tasks.*, department.department, department.abbreviation, GROUP_CONCAT(users.username SEPARATOR ', ') AS assigned_users FROM tasks ";
            $query .= "LEFT JOIN distributed_tasks ON tasks.id = distributed_tasks.task_id ";
            $query .= "LEFT JOIN department ON tasks.department_id = department.id ";
            $query .= "LEFT JOIN users ON distributed_tasks.user_id = users.id ";

            // Check if date range is provided
            if (!empty($startDate) && !empty($endDate)) {
                $query .= "WHERE tasks.createdAt BETWEEN :startDate AND :endDate ";
            }

            // If role is provided, fetch visibility and adjust query accordingly
            if ($role !== null) {
                // Fetch the visibility based on role
                $stmtFetchRole = $db->prepare("SELECT visibility FROM role WHERE role = :role");
                $stmtFetchRole->bindParam(':role', $role);
                $stmtFetchRole->execute();
                $roleData = $stmtFetchRole->fetch(PDO::FETCH_ASSOC);

                if (!$roleData) {
                    throw new Exception("Role not found");
                }

                $visibility = $roleData['visibility'];

                // If the role is public, add condition to fetch tasks based on user_id or createdBy
                if ($visibility === "PUBLIC") {
                    if (strpos($query, "WHERE") === false) {
                        $query .= " WHERE";
                    } else {
                        $query .= " AND";
                    }
                    $query .= " (distributed_tasks.user_id = :userId OR tasks.createdBy = :userId)";
                }
            }

            // If userId is provided, fetch department ID and adjust query accordingly
            if ($userId !== null) {
                // Fetch the department ID based on userId
                $stmtFetchDepartment = $db->prepare("SELECT department_id FROM users WHERE id = :userId");
                $stmtFetchDepartment->bindParam(':userId', $userId);
                $stmtFetchDepartment->execute();
                $departmentData = $stmtFetchDepartment->fetch(PDO::FETCH_ASSOC);

                if (!$departmentData) {
                    throw new Exception("User not found");
                }

                $departmentId = $departmentData['department_id'];

                // Add condition to filter tasks by department ID
                if ($departmentId !== 26) {
                    if (strpos($query, "WHERE") === false) {
                        $query .= " WHERE";
                    } else {
                        $query .= " AND";
                    }
                    $query .= " tasks.department_id = :departmentId";
                }
            }

            // Group by task ID to avoid duplicate results
            $query .= " GROUP BY tasks.id";

            // Prepare the statement
            $stmt = $db->prepare($query);

            // Bind parameters if date range is provided
            if (!empty($startDate) && !empty($endDate)) {
                $startDate .= " 00:00:00";
                $endDate .= " 23:59:59";
                $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
                $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            }

            // Bind user ID if needed
            if ($userId !== null && $departmentId !== 26) {
                $stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
            }

            // Bind user ID if the role is public and user ID is provided
            if ($role !== null && $userId !== null && $visibility === "PUBLIC") {
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
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
        } catch (Exception $ex) {
            // Handle other exceptions
            http_response_code(500); // Internal Server Error
            return json_encode(['error' => $ex->getMessage()]);
        }
    }

    public static function GenerateTaskReport($startDate, $endDate, $statusFilter, $usernames)
    {
        global $db;

        try {
            // Construct the SQL query to fetch tasks
            $query = "SELECT `tasks`.`title`, `tasks`.`detail`, `task_status`.`status`, `users`.`username` FROM `tasks`
            LEFT JOIN `task_status` ON `tasks`.`status_id` = `task_status`.`id`
            LEFT JOIN `distributed_tasks` ON `tasks`.`id` = `distributed_tasks`.`task_id`
            LEFT JOIN `users` ON `distributed_tasks`.`user_id` = `users`.`id` ";

            // Add condition for date range if both start date and end date are provided
            if (!empty($startDate) && !empty($endDate)) {
                $query .= "WHERE DATE(`tasks`.`createdAt`) BETWEEN :startDate AND :endDate ";
            }

            // Add condition for status filter if provided
            if (!empty($statusFilter)) {
                $query .= "AND `task_status`.`status` = :statusFilter ";
            }

            // Add condition for usernames filter if provided
            if (!empty($usernames)) {
                // Split usernames into an array and surround each username with single quotes
                $usernamesArray = explode(',', $usernames);
                $usernamesString = "'" . implode("','", $usernamesArray) . "'";
                $query .= "AND `users`.`username` IN ($usernamesString) ";
            }

            // Prepare the statement
            $stmt = $db->prepare($query);

            // Bind parameters if date range is provided
            if (!empty($startDate) && !empty($endDate)) {
                $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
                $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            }

            // Bind status filter parameter if provided
            if (!empty($statusFilter)) {
                $stmt->bindParam(':statusFilter', $statusFilter, PDO::PARAM_STR);
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
        } catch (Exception $ex) {
            // Handle other exceptions
            http_response_code(500); // Internal Server Error
            return json_encode(['error' => $ex->getMessage()]);
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

    public static function newTask($title, $details, $department_id = "", $role, $createdBy)
    {
        global $db;

        try {
            // Fetch the visibility based on role
            $stmtFetchRole = $db->prepare("SELECT visibility FROM role WHERE role = :role");
            $stmtFetchRole->bindParam(':role', $role);
            $stmtFetchRole->execute();
            $roleData = $stmtFetchRole->fetch(PDO::FETCH_ASSOC);

            if (!$roleData) {
                throw new Exception("Role not found");
            }

            $visibility = $roleData['visibility'];

            // Determine the department ID
            $departmentId = !empty($department_id) ? $department_id : self::getDepartmentId($createdBy);

            // Determine the status ID based on visibility
            $statusId = ($visibility === "PUBLIC") ? 6 : 4;

            // Prepare the SQL statement for insertion
            $stmt = $db->prepare("INSERT INTO `tasks` (`title`, `detail`, `status_id`, `department_id`, `createdBy`) VALUES (:title, :details, :status_id, :department_id, :createdBy)");

            // Bind parameters
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':details', $details);
            $stmt->bindParam(':status_id', $statusId);
            $stmt->bindParam(':department_id', $departmentId);
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
        } catch (Exception $ex) {
            // Handle other exceptions
            return null; // or throw an exception based on your error handling strategy
        }
    }

    private static function getDepartmentId($userId)
    {
        global $db;

        $stmtFetchDepartment = $db->prepare("SELECT department_id FROM users WHERE id = :userId");
        $stmtFetchDepartment->bindParam(':userId', $userId);
        $stmtFetchDepartment->execute();
        $departmentData = $stmtFetchDepartment->fetch(PDO::FETCH_ASSOC);

        if (!$departmentData) {
            throw new Exception("User not found");
        }

        return $departmentData['department_id'];
    }

    public static function viewTask($task_id)
    {
        global $db;

        $stmt = $db->prepare(
            "SELECT 
            tasks.title, 
            tasks.detail, 
            CASE WHEN tasks.dueAt IS NULL THEN 'Not Set' ELSE tasks.dueAt END AS dueAt,
            GROUP_CONCAT(users.username SEPARATOR ', ') AS assigned,
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
            LEFT JOIN distributed_tasks ON tasks.id = distributed_tasks.task_id 
            LEFT JOIN users ON distributed_tasks.user_id = users.id
        WHERE tasks.id = :task_id
        GROUP BY tasks.id, tasks.title, tasks.detail, tasks.dueAt, 
                 tasks.createdAt, tasks.updatedAt, tasks.startedAt, tasks.endedAt,
                 files.id, files.filename, files.file_size, files.file_destination,
                 task_status.status;
        "
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

        try {
            // Delete task from the database
            $stmt = $db->prepare("DELETE FROM tasks WHERE id = :task_id");
            $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $stmt->execute();

            // Delete task distribution records
            $deleteStmt = $db->prepare("DELETE FROM `distributed_tasks` WHERE `task_id` = :task_id");
            $deleteStmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Check if any rows were affected by the update and insertion
            if ($stmt->rowCount() > 0 || $deleteStmt->rowCount() > 0) {
                return true; // Task successfully distributed
            } else {
                return false; // Task not found or no changes made
            }
        } catch (PDOException $e) {
            // Handle database errors
            error_log("Error deleting task: " . $e->getMessage());
            return false;
        }
    }

    public static function unassignTask($task_id)
    {
        global $db;

        try {
            // Update task in the database
            $stmt = $db->prepare("UPDATE tasks SET task_type = NULL, dueAt = NULL WHERE id = :task_id");
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            // Delete task distribution records
            $deleteStmt = $db->prepare("DELETE FROM `distributed_tasks` WHERE `task_id` = :task_id");
            $deleteStmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            return $rowCount > 0; // Returns true if the task was updated successfully
        } catch (PDOException $e) {
            // Handle database errors
            error_log("Error unassigning task: " . $e->getMessage());
            return false;
        }
    }

    public static function fetchPerformance($user_id = null, $departmentId = null)
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

            $query = "SELECT
                        task_status.status,
                        COUNT(*) AS status_count
                    FROM
                        tasks
                    LEFT JOIN
                        task_status ON tasks.status_id = task_status.id
                    LEFT JOIN
                        distributed_tasks ON tasks.id = distributed_tasks.task_id
                    LEFT JOIN
                        users ON distributed_tasks.user_id = users.id
                    WHERE
                        DATE(tasks.createdAt) BETWEEN :start_date AND :end_date";

            if ($user_id !== null) {
                $query .= " AND distributed_tasks.user_id = :user_id";
            }

            if ($departmentId !== null) {
                $query .=  " AND users.department_id = :departmentId";
            }

            $query .= " GROUP BY
                            task_status.status
                        ORDER BY
                            task_status.status";

            $stmt = $db->prepare($query);

            $stmt->bindParam(':start_date', $weekDates['monday']);
            $stmt->bindParam(':end_date', $weekDates['sunday']);

            if ($user_id !== null) {
                $stmt->bindParam(':user_id', $user_id);
            }

            if ($departmentId !== null) {
                $stmt->bindParam(':departmentId', $departmentId);
            }

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

    public static function distributeTask($task_id, $role, $task_type, $user_id, $dueAt, $visibility)
    {
        global $db;
        try {
            // Determine the status ID based on the role
            $status_id = ($role === "SUPER ADMIN" || $visibility === "PRIVATE") ? 4 : 6;

            // Update task details
            $updateStmt = $db->prepare("UPDATE `tasks` SET `task_type` = :task_type, `status_id` = :status_id, `dueAt` = :dueAt WHERE `id` = :task_id");
            $updateStmt->bindParam(':task_type', $task_type);
            $updateStmt->bindParam(':status_id', $status_id);
            $updateStmt->bindParam(':dueAt', $dueAt);
            $updateStmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $updateStmt->execute();

            // Insert task distribution record
            $insertStmt = $db->prepare("INSERT INTO `distributed_tasks` (`task_id`, `user_id`) VALUES (:task_id, :user_id)");
            $insertStmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $insertStmt->execute();

            // Check if any rows were affected by the update and insertion
            if ($updateStmt->rowCount() > 0 && $insertStmt->rowCount() > 0) {
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

    public static function fetchPublicUsers($role)
    {
        global $db;

        // Check if the fetched role is a public role
        $stmt = $db->prepare("SELECT visibility FROM `role` WHERE `role`.`role` = :role");
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        $visibility = $stmt->fetchColumn(); // Fetch the count value

        return $visibility; // Return the count value, which could be 0 if the role is not found
    }

    public static function updateTaskStatus($taskId, $statusId)
    {
        global $db;

        $beginstatus = [7];
        $endstatus = [1, 2, 3, 5];

        // Check if statusId is in beginstatus or endstatus
        if (in_array($statusId, $beginstatus)) {
            // Update startedAt and status_id
            $stmt = $db->prepare("UPDATE tasks SET startedAt = :startedAt, status_id = :statusId WHERE id = :taskId");
            $stmt->bindParam(':startedAt', date('Y-m-d H:i:s')); // Current date and time
        } elseif (in_array($statusId, $endstatus)) {
            // Update endedAt and status_id
            $stmt = $db->prepare("UPDATE tasks SET endedAt = :endedAt, status_id = :statusId WHERE id = :taskId");
            $stmt->bindParam(':endedAt', date('Y-m-d H:i:s')); // Current date and time
        } else {
            // Only update status_id
            $stmt = $db->prepare("UPDATE tasks SET status_id = :statusId WHERE id = :taskId");
        }

        // Bind parameters
        $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
        $stmt->bindParam(':statusId', $statusId);

        // Execute the query
        return $stmt->execute();
    }

    public static function fetchNotifications($user_id)
    {
        global $db;

        $stmt = $db->prepare("SELECT `title`, `users`.`username`, `task_type`, `dueAt`, `task_status`.`status` 
                            FROM `tasks`
                            LEFT JOIN `task_status` ON `tasks`.`status_id` = `task_status`.`id`
                            LEFT JOIN `distributed_tasks` ON `tasks`.`id` = `distributed_tasks`.`task_id`
                            LEFT JOIN `users` ON `distributed_tasks`.`user_id` = `users`.`id`
                            WHERE `distributed_tasks`.`user_id` = :user_id
                            AND `task_status`.`status` NOT IN ('FAILED', 'DONE', 'REJECTED')        
                        ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($results);
    }
}
