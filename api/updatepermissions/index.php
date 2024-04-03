<?php
require_once '../../src/controllers/Users/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Start a database transaction
    $pdo = Users::getPDO(); // Replace with your actual method for getting a PDO instance
    $pdo->beginTransaction();

    try {
        if (isset($_GET['userId']) && isset($_GET['permissions'])) {
            $userId = $_GET['userId'];
            $permissions = $_GET['permissions'];
            $result = Users::updateUserPermissions($userId, $permissions);

            if ($result) {
                $pdo->commit();
                echo json_encode(['message' => 'Successfully updated permissions']);
            } else {
                echo json_encode([
                    'message' => 'Updating permissions failed',
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'userId and permissions parameters are required',
            ]);
        }
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $pdo->rollBack();

        // Respond with error
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Respond with method not allowed
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}
