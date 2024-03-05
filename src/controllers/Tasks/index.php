<?php
require_once __DIR__ . '/../../config/db.php';

class Tasks
{
    public static function getPDO()
    {
        global $db;
        return $db;
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
}
