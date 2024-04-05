<?php
require_once __DIR__ . '/../../config/db.php';

class Users
{
    public $id;
    public $username;
    public $auth;
    public $department;
    public $role;
    public $status;
    public $full_name;
    public $address;
    public $age;
    public $contact;
    public $gender;

    public static function getPDO()
    {
        global $db;
        return $db;
    }

    public function __construct($id, $username, $auth, $department, $role, $status, $full_name, $address, $age, $contact, $gender)
    {
        $this->id = $id;
        $this->username = $username;
        $this->auth = $auth;
        $this->department = $department;
        $this->role = $role;
        $this->status = $status;
        $this->full_name = $full_name;
        $this->address = $address;
        $this->age = $age;
        $this->contact = $contact;
        $this->gender = $gender;
    }

    public static function fetchAllUsers($searchTerm  = null)
    {
        global $db;

        // Initial query without WHERE clause
        $query = "SELECT users.id, users.username, users.auth, role.role, department.department, user_status.status, user_details.full_name, user_details.address, user_details.age, user_details.contact, user_details.gender
                FROM users
                INNER JOIN role ON users.role_id = role.id
                INNER JOIN department ON users.department_id = department.id
                LEFT JOIN user_status ON users.status = user_status.id
                LEFT JOIN user_details ON users.id = user_details.user_id";

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
                $result['auth'],
                $result['department'],
                $result['role'],
                $result['status'],
                $result['full_name'],
                $result['address'],
                $result['age'],
                $result['contact'],
                $result['gender']
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

    public static function fetchDepartments()
    {
        global $db;

        $stmt = $db->query("SELECT id, abbreviation, department, super FROM department");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($results);
    }

    public static function  fetchRoles()
    {
        global $db;

        $stmt = $db->query("SELECT id, role, super FROM role");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($results);
    }

    public static function insertDepartment($abbreviation, $department)
    {
        global $db;
        try {
            // Prepare the SQL statement for insertion
            $stmt = $db->prepare("INSERT INTO department (abbreviation, department) VALUES (:abbreviation, :department)");

            // Bind parameters and execute the statement
            $stmt->bindParam(':abbreviation', $abbreviation);
            $stmt->bindParam(':department', $department);
            $stmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            // Return a success message or the number of affected rows
            if ($rowCount > 0) {
                return "Successfully added: $abbreviation";
            } else {
                return "No rows affected.";
            }
        } catch (PDOException $e) {
            // Handle database errors
            return "Error inserting department: " . $e->getMessage();
        }
    }

    public static function insertRole($role)
    {
        global $db;
        try {
            // Prepare the SQL statement for insertion
            $stmt = $db->prepare("INSERT INTO role (role) VALUES (:role)");

            // Bind parameters and execute the statement
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            // Return a success message or the number of affected rows
            if ($rowCount > 0) {
                return "Successfully added: $role";
            } else {
                return "No rows affected.";
            }
        } catch (PDOException $e) {
            // Handle integrity constraint violation error
            if ($e->getCode() === '23000') {
                // Handle duplicate entry error
                return "Role '$role' already exists.";
            } else {
                // Handle other database errors
                return "Error inserting role: " . $e->getMessage();
            }
        }
    }

    public static function updateDepartment($departmentId, $abbreviation, $department)
    {
        global $db;
        try {
            // Prepare the SQL statement for update
            $stmt = $db->prepare("UPDATE department SET abbreviation = :abbreviation, department = :department WHERE id = :departmentId");

            // Bind parameters and execute the statement
            $stmt->bindParam(':departmentId', $departmentId);
            $stmt->bindParam(':abbreviation', $abbreviation);
            $stmt->bindParam(':department', $department);
            $stmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            // Return a success message or the number of affected rows
            if ($rowCount > 0) {
                return "Successfully updated department";
            } else {
                return "No rows affected. Department with ID $departmentId not found.";
            }
        } catch (PDOException $e) {
            // Handle database errors
            return "Error updating department: " . $e->getMessage();
        }
    }

    public static function updateRole($roleId, $role)
    {
        global $db;
        try {
            // Prepare the SQL statement for update
            $stmt = $db->prepare("UPDATE role SET role = :role WHERE id = :roleId");

            // Bind parameters and execute the statement
            $stmt->bindParam(':roleId', $roleId);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            // Return a success message or the number of affected rows
            if ($rowCount > 0) {
                return "Successfully updated role";
            } else {
                return "No rows affected. Role with ID $roleId not found.";
            }
        } catch (PDOException $e) {
            // Handle database errors
            return "Error updating role: " . $e->getMessage();
        }
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

    public static function fetchPerformance()
    {
        global $db;

        try {
            // Get the current week's start and end dates
            $weekDates = getCurrentWeekDates();

            // Prepare the SQL query with placeholders for start_date and end_date
            $sql = "SELECT
                users.username,
                SUM(CASE WHEN task_status.status = 'DONE' THEN 1 ELSE 0 END) AS done,
                SUM(CASE WHEN task_status.status = 'LATE' THEN 1 ELSE 0 END) AS late,
                SUM(CASE WHEN task_status.status = 'FAILED' THEN 1 ELSE 0 END) AS failed
            FROM
                users
            LEFT JOIN
                tasks ON users.id = tasks.user_id
            LEFT JOIN task_status ON tasks.status_id = task_status.id
            WHERE DATE(tasks.createdAt) BETWEEN :start_date AND :end_date
            GROUP BY
                users.username
            ORDER BY done DESC
            LIMIT 5"; // Add LIMIT and OFFSET clauses

            // Prepare the statement
            $stmt = $db->prepare($sql);

            // Bind the parameters
            $stmt->bindParam(':start_date', $weekDates['monday']);
            $stmt->bindParam(':end_date', $weekDates['sunday']);

            // Execute the statement
            $stmt->execute();

            // Fetch the results
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the results as JSON
            return json_encode($results);
        } catch (PDOException $e) {
            // Handle database connection error
            http_response_code(500); // Internal Server Error
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public static function updateUserPermissions($userId, $permissions)
    {
        global $db;

        try {
            // Prepare and execute the SQL query to update permissions
            $stmt = $db->prepare("UPDATE permissions SET permissions = :permissions WHERE user_id = :userId");
            $stmt->bindParam(':permissions', $permissions);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            // Return a success message or the number of affected rows
            if ($rowCount > 0) {
                return "Permissions updated successfully for user with ID: $userId";
            } else {
                return "No permissions updated for user with ID: $userId";
            }
        } catch (PDOException $e) {
            // Handle database errors
            return "Error updating permissions for user with ID: $userId - " . $e->getMessage();
        }
    }

    public static function deleteUser($userId)
    {
        global $db;

        // Start a transaction
        $db->beginTransaction();

        try {
            // Delete user from the database
            $stmt1 = $db->prepare("DELETE FROM users WHERE id = :userId");
            $stmt1->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt1->execute();

            // Update other tasks
            $stmt2 = $db->prepare("UPDATE tasks SET task_type = NULL, dueAt = NULL, user_id = NULL WHERE user_id = :userId");
            $stmt2->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt2->execute();

            // Commit the transaction
            $db->commit();

            // Check if any rows were affected
            $rowCount1 = $stmt1->rowCount();
            $rowCount2 = $stmt2->rowCount();

            // Return true if any rows were affected by any of the SQL statements
            return ($rowCount1 > 0 || $rowCount2 > 0);
        } catch (PDOException $e) {
            // An error occurred, rollback the transaction
            $db->rollBack();

            // Handle the error
            // For example, you can throw an exception or return false
            return false;
        }
    }

    public static function deleteDepartment($departmentId)
    {
        global $db;

        // Start a transaction
        $db->beginTransaction();

        try {
            // Check if users are using the department
            $stmtCountUsers = $db->prepare("SELECT COUNT(*) AS user_count FROM users WHERE department_id = :departmentId");
            $stmtCountUsers->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
            $stmtCountUsers->execute();
            $userCountResult = $stmtCountUsers->fetch(PDO::FETCH_ASSOC);
            $userCount = (int) $userCountResult['user_count'];

            if ($userCount > 0) {
                // If users are using the department, return a message indicating it's being used
                return "The department is being used by $userCount user(s) and cannot be deleted.";
            }

            // Delete the department from the database
            $stmtDeleteDepartment = $db->prepare("DELETE FROM department WHERE id = :departmentId");
            $stmtDeleteDepartment->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
            $stmtDeleteDepartment->execute();

            // Commit the transaction
            $db->commit();

            // Check if any rows were affected
            $rowCount = $stmtDeleteDepartment->rowCount();

            // Return true if any rows were affected by the SQL statement
            return ($rowCount > 0);
        } catch (PDOException $e) {
            // An error occurred, rollback the transaction
            $db->rollBack();

            // Handle the error
            // For example, you can throw an exception or return false
            return false;
        }
    }

    public static function deleteRole($roleId)
    {
        global $db;

        // Start a transaction
        $db->beginTransaction();

        try {
            // Check if the role is being used by any users
            $stmtCountUsers = $db->prepare("SELECT COUNT(*) AS user_count FROM users WHERE role_id = :roleId");
            $stmtCountUsers->bindParam(':roleId', $roleId, PDO::PARAM_INT);
            $stmtCountUsers->execute();
            $userCountResult = $stmtCountUsers->fetch(PDO::FETCH_ASSOC);
            $userCount = $userCountResult['user_count'];

            if ($userCount > 0) {
                // If users are using the role, return a message indicating it's being used
                return "The role is being used by $userCount user(s) and cannot be deleted.";
            }

            // Delete the role from the database
            $stmtDeleteRole = $db->prepare("DELETE FROM role WHERE id = :roleId");
            $stmtDeleteRole->bindParam(':roleId', $roleId, PDO::PARAM_INT);
            $stmtDeleteRole->execute();

            // Commit the transaction
            $db->commit();

            // Check if any rows were affected
            $rowCount = $stmtDeleteRole->rowCount();

            // Return true if any rows were affected by the deletion
            return ($rowCount > 0);
        } catch (PDOException $e) {
            // An error occurred, rollback the transaction
            $db->rollBack();

            // Handle the error
            // For example, you can throw an exception or return false
            return false;
        }
    }

    public static function insertupdateUserDetails($userId, $fullname, $address, $age, $contact, $gender)
    {
        global $db;

        try {
            // Prepare and execute the SQL query to insert or update user details
            $stmt = $db->prepare("
            INSERT INTO user_details (user_id, full_name, address, age, contact, gender)
            VALUES (:userId, :full_name, :address, :age, :contact, :gender)
            ON DUPLICATE KEY UPDATE
            full_name = VALUES(full_name),
            address = VALUES(address),
            age = VALUES(age),
            contact = VALUES(contact),
            gender = VALUES(gender)
        ");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':full_name', $fullname);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':gender', $gender);
            $stmt->execute();

            // Check if any rows were affected
            $rowCount = $stmt->rowCount();

            // Return a success message or the number of affected rows
            if ($rowCount > 0) {
                return "User details updated successfully for user with ID: $userId";
            } else {
                return "No user details updated for user with ID: $userId";
            }
        } catch (PDOException $e) {
            // Handle database errors
            return "Error updating user details for user with ID: $userId - " . $e->getMessage();
        }
    }
}
