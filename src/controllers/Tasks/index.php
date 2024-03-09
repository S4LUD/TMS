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

        // Initial query without WHERE clause
        $query = "SELECT id, title, createdAt FROM tasks";

        if (!empty($startDate) && !empty($endDate)) {
            // Append WHERE clause for specific date range
            $query .= " WHERE createdAt BETWEEN :startDate AND :endDate";
            $stmt = $db->prepare($query);
            $startDate .= " 00:00:00";
            $endDate .= " 23:59:59";
            $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        } else {
            $stmt = $db->query($query);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($results);
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

    public static function newTask($title, $details)
    {
        global $db;

        $stmt = $db->prepare("INSERT INTO `tasks` (`title`, `detail`) VALUES (:title, :details)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':details', $details);
        $stmt->execute();
        $taskId = $db->lastInsertId();
        return (object)['id' => $taskId];
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
                    'filename' => $file['filename'],
                    'file_size' => $file['file_size'],
                    'file_destination' => $file['file_destination'],
                ];
            }, $results);
        } else {
            $formattedResult['files'] = []; // No files associated with the task
        }

        return json_encode([$formattedResult]);
    }

    public static function updateTask($taskId, $title, $details)
    {
        global $db;

        $stmt = $db->prepare("UPDATE `tasks` SET `title` = :title, `detail` = :details WHERE `id` = :taskId");
        $stmt->bindParam(':taskId', $taskId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':details', $details);
        $stmt->execute();

        // Check if any rows were affected (to ensure the task with the given ID exists)
        if ($stmt->rowCount() > 0) {
            return true; // Update successful
        } else {
            return false; // Task with the given ID not found
        }
    }
}
