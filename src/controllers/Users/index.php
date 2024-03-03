<?php
require_once __DIR__ . '/../../config/db.php';

class Users
{
    public $id;
    public $username;
    public $department;
    public $role;
    public $status;

    public function __construct($id, $username, $department, $role, $status)
    {
        $this->id = $id;
        $this->username = $username;
        $this->department = $department;
        $this->role = $role;
        $this->status = $status;
    }

    public static function fetchAllUsers($searchTerm  = null)
    {
        global $db;

        // Initial query without WHERE clause
        $query = "SELECT users.id, users.username, role.role, department.department, user_status.status
                    FROM users
                    INNER JOIN role ON users.role_id = role.id
                    INNER JOIN department ON users.department_id = department.id
                    LEFT JOIN user_status ON users.status = user_status.id
                    ";

        // Check if $username is provided
        if (!empty($searchTerm)) {
            // Append WHERE clause for specific username
            $query .= " WHERE BINARY users.username = :searchTerm OR users.id = :searchTerm";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        } else {
            // No specific username provided, fetch all users
            $stmt = $db->query($query);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = new Users(
                $result['id'],
                $result['username'],
                $result['department'],
                $result['role'],
                $result['status']
            );
        }

        return json_encode($users);
    }


    public static function countUsersByRole()
    {
        global $db;

        $stmt = $db->query("SELECT role.role, COUNT(users.id) AS user_count
                        FROM users
                        INNER JOIN role ON users.role_id = role.id
                        GROUP BY role.role");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usersByRole = [
            'labels' => [],
            'counts' => [],
        ];

        foreach ($results as $result) {
            $usersByRole['labels'][] = $result['role'];
            $usersByRole['counts'][] = (int)$result['user_count'];
        }

        return json_encode($usersByRole);
    }

    public static function countUsersByDepartment()
    {
        global $db;

        $stmt = $db->query("SELECT department.department, COUNT(users.id) AS user_count
                        FROM users
                        INNER JOIN department ON users.department_id = department.id
                        GROUP BY department.department");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usersByDepartment = [
            'labels' => [],
            'counts' => [],
        ];

        foreach ($results as $result) {
            $usersByDepartment['labels'][] = $result['department'];
            $usersByDepartment['counts'][] = (int)$result['user_count'];
        }

        return json_encode($usersByDepartment);
    }

    public static function countUsers()
    {
        global $db;

        $stmt = $db->query("SELECT COUNT(*) AS total_users FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($result);
    }

    public static function fetchAlltUserTasks($user_id)
    {
        global $db;

        $stmt = $db->query("SELECT tasks.title, tasks.detail, tasks.createdAt, tasks.updatedAt, users.username AS assigned_to, task_status.status
                    FROM assigned_task
                    JOIN users ON assigned_task.user_id = users.id
                    JOIN tasks ON assigned_task.task_id = tasks.id
                    JOIN task_status ON assigned_task.status_id = task_status.id
                    WHERE assigned_task.user_id =" . $user_id);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($results as $result) {
            $tasks[] = (object)[
                'title' => $result['title'],
                'detail' => $result['detail'],
                'createdAt' => $result['createdAt'],
                'updatedAt' => $result['updatedAt'],
                'assigned_to' => $result['assigned_to'],
                'status' => $result['status']
            ];
        }

        return json_encode($tasks);
    }

    public static function fetchAllRoles()
    {
        global $db;

        $stmt = $db->query("SELECT id, role FROM role");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($results);
    }

    public static function fetchAllDepartments()
    {
        global $db;

        $stmt = $db->query("SELECT id, department FROM department");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($results);
    }
}
