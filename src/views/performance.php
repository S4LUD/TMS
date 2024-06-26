<?php
function getCurrentWeekDates()
{
    // Set the timezone to your desired timezone
    date_default_timezone_set('Asia/Manila');

    // Get the current date
    $currentDate = new DateTime();

    // Get the current month
    $currentMonth = $currentDate->format('F');

    // Set the current date to the start of the week (Monday)
    $currentDate->modify('this week');

    // Get the Monday of the current week
    $mondayDate = $currentDate->format('d');

    // Set the current date to the end of the week (Sunday)
    $currentDate->modify('this week +6 days');

    // Get the Sunday of the current week
    $sundayDate = $currentDate->format('d');

    return array('monday' => $mondayDate, 'sunday' => $sundayDate, 'month' => $currentMonth);
}

$userData = json_decode($_SESSION['user'], true);
?>

<div class="h-fit bg-white border rounded">
    <div class="flex flex-col">
        <div class="text-xl font-semibold border-b p-8">
            Weekly Performance for
            <?= getCurrentWeekDates()['monday'] ?>
            -
            <?= getCurrentWeekDates()['sunday'] ?>
            <?= getCurrentWeekDates()['month'] ?>
        </div>
        <div class="flex flex-col lg:flex-row">
            <div class="flex-1 border-b lg:border-b-0 lg:border-r p-8">
                <?php if ($userData['visibility'] === "PRIVATE") { ?>
                    <div class="flex flex-row gap-4">
                        <div class="relative rotate-180">
                            <div class="w-20 h-20 rounded-full flex justify-center items-center rotate-180">
                                <span id="completion-rate" class="text-base font-medium"></span>
                            </div>
                            <svg class="absolute top-0 left-0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <circle class="stroke-current text-gray-200" cx="10" cy="10" r="9" fill="transparent" stroke-width="1" />
                            </svg>
                            <svg id="completion-circle" class="absolute top-0 left-0 rotate-90" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <circle class="stroke-current text-blue-500" cx="10" cy="10" r="9" fill="transparent" stroke-width="1" />
                            </svg>
                        </div>
                        <div id="completion-data" class="flex items-center h-20">
                        </div>
                    </div>
                <?php } ?>
                <div class="w-full h-full mt-3">
                    <canvas id="tasksChart"></canvas>
                </div>
            </div>
            <div class="flex-1 p-8">
                <?php if ($userData['visibility'] === "PRIVATE") { ?>
                    <div id="users_list"></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script src="/tms/src/assets/scripts/performance/index.js"></script>