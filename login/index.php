<?php
session_start();
require_once '../src/controllers/Auth/index.php';

if (isset($_SESSION['user'])) {
    header('Location: /tms/dashboard/');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginResult = Auth::login($_POST['username'], $_POST['password']);
    if ($loginResult !== null) {
        if (json_decode($loginResult, true)['status'] === 2) {
            echo '<script>alert("Your account is currently blocked. Please contact your higher-ups to unblock it.");</script>';
        } else {
            $_SESSION['user'] = $loginResult;
            echo '<script>localStorage.setItem("user",' . json_encode($_SESSION['user']) . ');</script>';
            echo '<script>localStorage.setItem("permissions",' . json_encode(json_decode($_SESSION['user'], true)['permissions'], true) . ');</script>';
            header("refresh:0;url=/tms/dashboard/");
            exit();
        }
    } else {
        $maxAttempts = 3; // Maximum login attempts allowed
        $loginAttempts = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] : 0;
        $remainingAttempts = $maxAttempts - $loginAttempts - 1; // Decrement remaining attempts by 1
        $_SESSION['login_attempts'] = $loginAttempts + 1;
        if ($remainingAttempts > 0) {
            echo '<script>alert("Invalid credentials. You have ' . $remainingAttempts . '/' . $maxAttempts . ' attempts left before your account is blocked.");</script>';
        } else {
            $blockedResult = Auth::blockUser($_POST['username']);
            if ($blockedResult) {
                echo '<script>alert("Your account has been blocked. Please contact your higher-ups to unblock it.");</script>';
            }
            // Reset login attempts after blocking the user
            unset($_SESSION['login_attempts']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com/3.4.1"></script>
    <script src="https://kit.fontawesome.com/ece8d271f7.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="icon" href="/tms/src/image/logo.png" type="image/png">
</head>

<body>
    <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
        <div class="flex-grow flex flex-col items-center justify-center">
            <h1 class="text-blue-500 font-bold text-3xl mb-4">Task Monitoring System</h1>
            <div class="bg-white w-96 px-8 py-6 rounded-lg shadow-xl">
                <div class="flex items-center justify-center mb-4">
                    <span class="text-black-500 font-semibold text-lg">Sign in to Task Monitoring System</span>
                </div>
                <form method="post" action="">
                    <div class="relative mb-4">
                        <input type="text" name="username" placeholder="Username" class="w-full border border-gray-400 rounded-md py-3 px-4 focus:outline-none focus:border-blue-500 hover:border-blue-700 transition duration-300 text-lg <?php echo $usernameClass; ?>" required>
                    </div>
                    <div class="relative mb-4">
                        <input type="password" name="password" placeholder="Password" class="w-full border border-gray-400 rounded-md py-3 px-4 focus:outline-none focus:border-blue-500 hover:border-blue-700 transition duration-300 text-lg <?php echo $passwordClass; ?>" required>
                    </div>
                    <button type="submit" class="text-lg font-semibold bg-blue-500 text-white py-3 rounded-md w-full hover:bg-blue-600 hover:text-gray-100 transition duration-300">Submit</button>
                </form>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/footer.php'); ?>
    </div>
</body>

</html>