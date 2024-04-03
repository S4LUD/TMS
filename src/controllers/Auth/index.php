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

        $stmt = $db->prepare("SELECT users.id, users.username, users.password, role.role, users.auth, department.department, department.abbreviation, permissions.permissions FROM users
                        JOIN role on users.role_id = role.id
                        JOIN department on users.department_id = department.id
                        JOIN permissions on users.id = permissions.user_id
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
                'permissions' => $user['permissions'],
                'auth' => $user['auth'],
            ]);
        } else {
            return null;
        }
    }

    public static function userDetails($searchTerm)
    {
        global $db;

        // Prepare the SQL query using placeholders to prevent SQL injection
        $stmt = $db->prepare("SELECT permissions.permissions 
                        FROM users
                        JOIN permissions ON users.id = permissions.user_id
                        WHERE BINARY users.username = :searchTerm OR users.id = :searchTerm");

        // Execute the prepared statement with parameters
        $stmt->execute([':searchTerm' => $searchTerm]);

        // Fetch the user details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // User not found, return null or handle accordingly
            return null;
        }

        // Prepare the user details as an associative array
        $userData = [
            'permissions' => json_decode($user['permissions'], true) // Decode permissions JSON string to an array
        ];

        // Encode the user details as JSON and return
        return json_encode($userData);
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

        // Insert user data into the database
        $insertStmt = $db->prepare("INSERT INTO `users`(`username`, `password`, `department_id`, `role_id`) VALUES (?, ?, ?, ?)");
        $insertSuccess = $insertStmt->execute([$username, $hashedPassword, $departmentId, $roleId]);

        if ($insertSuccess) {
            // Successful registration
            $userId = $db->lastInsertId(); // Get the ID of the newly inserted user

            // Insert default permissions for the user
            $defaultPermissions = '{"dashboard":true,"account_management":{"enabled":true,"source":{"create_user":true,"roles":true,"departments":true,"delete":true,"view":true,"edit":true,"permissions":true}},"tasks":{"enabled":true,"source":{"create_task":true,"delete":true,"view":true,"edit":true}},"distribute":{"enabled":true,"source":{"assign":true}},"performance":true, "report":false}'; // Define default permissions
            $insertPermissionStmt = $db->prepare("INSERT INTO `permissions`(`user_id`, `permissions`) VALUES (?, ?)");
            $insertPermissionSuccess = $insertPermissionStmt->execute([$userId, $defaultPermissions]);

            if ($insertPermissionSuccess) {
                return json_encode(['message' => 'Registration successful']);
            } else {
                // If inserting default permissions fails, remove the user record to maintain consistency
                $db->prepare("DELETE FROM `users` WHERE `id` = ?")->execute([$userId]);
                return json_encode(['error' => 'Failed to set permissions']);
            }
        } else {
            // Registration failed
            return json_encode(['error' => 'Registration failed']);
        }
    }
}
