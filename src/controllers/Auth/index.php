<?php
require_once __DIR__ . '/../../config/db.php';

class Auth
{
    public static function login($username, $password)
    {
        global $db;

        $stmt = $db->prepare("SELECT users.id, users.username, users.password, users.status, role.role, users.auth, department.department, department.abbreviation, permissions.permissions FROM users
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
                'status' => $user['status'],
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
            $defaultPermissions = '{"account_management":{"enabled":false,"source":{"create_user":false,"roles":false,"departments":false,"delete":false,"view":false,"edit":false,"permissions":false}},"tasks":{"enabled":false,"source":{"create_task":false,"delete":false,"view":false,"edit":false}},"distribute":{"enabled":false,"source":{"assign":false}}, "report":false}'; // Define default permissions
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

    public static function changePassword($userId, $currentPassword, $newPassword)
    {
        global $db;

        try {
            // Fetch user's current password from the database
            $stmt = $db->prepare("SELECT id, password FROM users WHERE BINARY id = ?");
            $stmt->execute([$userId]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$userData) {
                return json_encode(['error' => 'User not found']);
            }

            // Verify if the provided current password matches the stored password
            if (!password_verify($currentPassword, $userData['password'])) {
                return json_encode(['error' => 'Incorrect current password']);
            }

            // Hash the new password before storing it
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedPassword, $userId]);

            // Password updated successfully
            return json_encode(['message' => 'Password changed successfully']);
        } catch (PDOException $e) {
            // Handle database errors
            return json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public static function blockUser($username)
    {
        global $db;

        try {
            // Prepare the SQL statement for update
            $stmt = $db->prepare("UPDATE users SET status = 2 WHERE username = :username");

            // Bind parameters and execute the statement
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            return $rowCount > 0;
        } catch (PDOException $e) {
            // Handle database errors
            return "Error updating department: " . $e->getMessage();
        }
    }
}
