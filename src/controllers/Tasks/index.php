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
            $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        } else {
            $stmt = $db->query($query);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($results);
    }

    public static function newFile($filename, $destination, $task_id)
    {
        global $db;

        try {
            $stmt = $db->prepare("INSERT INTO `files` (`filename`, `file_destination`, `task_id`) VALUES (:filename, :destination, :task_id)");

            $stmt->bindParam(':filename', $filename);
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

    public static function viewTask()
    {
        global $db;

        $stmt = $db->prepare("INSERT INTO `tasks` (`title`, `detail`) VALUES (:title, :details)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':details', $details);
        $stmt->execute();
        $taskId = $db->lastInsertId();
        return (object)['id' => $taskId];
    }
}
