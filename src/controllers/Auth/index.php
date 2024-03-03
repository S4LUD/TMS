<?php
require_once __DIR__ . '/../../config/db.php';

class Auth
{
    public static function fetchAll()
    {
        global $db;

        $stmt = $db->query("SELECT users.id, users.username, users.createdAt, users.updatedAt, role.role FROM users INNER JOIN role ON users.role_id = role.id");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($results as $result) {
            $users[] = new Users(
                $result['id'],
                $result['username'],
                $result['createdAt'],
                $result['updatedAt'],
                $result['role']
            );
        }

        return json_encode($users);
    }

    public static function login($username, $password)
    {
        global $db;

        $stmt = $db->prepare("SELECT users.id, users.username, users.password, role.role, department.department, department.abbreviation FROM users
                        JOIN role on users.role_id = role.id
                        JOIN department on users.department_id = department.id
                        WHERE BINARY users.username = ?");

        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return json_encode([
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'department' => $user['department'],
                'abbreviation' => $user['abbreviation'],
            ]);
        } else {
            return null;
        }
    }

    public static function createUser($username, $password, $departmentId, $roleId)
    {
        global $db;

        // Check if the username already exists
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM `users` WHERE BINARY `username` = ?");
        $checkStmt->execute([$username]);
        $userCount = $checkStmt->fetchColumn();

        if ($userCount > 0) {
            // Username already exists, return an error
            return json_encode(['error' => 'Username already exists']);
        }

        if ($departmentId === "") {
            // Department is empty, return an error
            return json_encode(['error' => 'You must choose a Department']);
        }

        if ($roleId === "") {
            // Role is empty, return an error
            return json_encode(['error' => 'You must choose a Role']);
        }

        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = $db->prepare("INSERT INTO `users`(`username`, `password`, `department_id`, `role_id`) VALUES (?, ?, ?, ?)");

        if ($insertStmt->execute([$username, $hashedPassword, $departmentId, $roleId])) {
            // Successful registration
            return json_encode(['message' => 'Registration successful']);
        } else {
            // Registration failed
            return json_encode(['error' => 'Registration failed']);
        }
    }
}
