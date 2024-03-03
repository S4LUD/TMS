<?php
require_once __DIR__ . '/../../config/db.php';

class Performance
{
    public static function fetchAllUsersPerformance()
    {
        global $db;

        $stmt = $db->query("SELECT COALESCE(user_details.full_name, users.username)
                        AS user_name, COUNT(*) AS tasks_done_count
                        FROM assigned_task
                        JOIN users ON assigned_task.user_id = users.id
                        JOIN tasks ON assigned_task.task_id = tasks.id
                        JOIN task_status ON assigned_task.status_id = task_status.id
                        LEFT JOIN user_details ON user_details.user_id = users.id
                        WHERE task_status.status = 'DONE'
                        GROUP BY user_name;
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = (object)[
                'username' => $result['user_name'],
                'tasks_done_count' => $result['tasks_done_count']
            ];
        }

        return json_encode($users);
    }

    public static function tasksCount()
    {
        global $db;

        $stmt = $db->query("SELECT COUNT(*) AS total_tasks FROM tasks");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($result);
    }

    public static function fetchTasksCountByCategory()
    {
        global $db;

        $stmt = $db->query("
        SELECT 'Assigned' AS task_category, COUNT(*) AS task_count
        FROM assigned_task
        JOIN tasks ON assigned_task.task_id = tasks.id

        UNION

        SELECT 'Not Assigned' AS task_category, COUNT(*) AS task_count
        FROM tasks
        LEFT JOIN assigned_task ON tasks.id = assigned_task.task_id
        WHERE assigned_task.task_id IS NULL

        UNION

        SELECT task_status.status AS task_category, COUNT(*) AS task_count
        FROM assigned_task
        JOIN task_status ON assigned_task.status_id = task_status.id
        GROUP BY task_status.status");

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $taskCounts = [];
        foreach ($results as $result) {
            $taskCounts[] = (object)[
                'task_category' => $result['task_category'],
                'task_count' => $result['task_count'],
            ];
        }

        return json_encode($taskCounts);
    }
}
