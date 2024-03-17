<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /tms/login/');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script src="https://cdn.tailwindcss.com/3.4.1"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="icon" href="/tms/src/image/logo.png" type="image/png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap');

    * {
        font-family: "Karla", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
    }
</style>

<body>
    <div id="main-container" class="flex flex-col h-screen bg-gray-100">
        <div class="flex-grow flex">
            <?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/sidemenu.php'); ?>
            <div id="contentContainer" class="flex-1 flex flex-col overflow-hidden">
                <?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/header.php'); ?>
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F4F6F9]">
                    <div class="p-4 h-full">
                        <?php include($_SERVER['DOCUMENT_ROOT'] . $contentView); ?>
                    </div>
                </main>
                <?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src/components/footer.php'); ?>
            </div>
        </div>
    </div>
    <script src="/tms/src/assets/scripts/layout/index.js"></script>
</body>

</html>