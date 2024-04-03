<?php
require_once '../../src/controllers/Users/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Start a database transaction
    $pdo = Users::getPDO(); // Replace with your actual method for getting a PDO instance
    $pdo->beginTransaction();

    try {
        if (isset($_GET['userId']) && isset($_GET['fullname']) && isset($_GET['address']) && isset($_GET['age']) && isset($_GET['contact']) && isset($_GET['gender'])) {
            $userId = $_GET['userId'];
            $fullname = $_GET['fullname'];
            $address = $_GET['address'];
            $age = $_GET['age'];
            $contact = $_GET['contact'];
            $gender = $_GET['gender'];

            $result = Users::insertupdateUserDetails($userId, $fullname, $address, $age, $contact, $gender);

            if ($result) {
                $pdo->commit();
                echo json_encode(['message' => 'Successfully updated user details']);
            } else {
                echo json_encode([
                    'error' => 'Updating permissions failed',
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
