<?php include($_SERVER['DOCUMENT_ROOT'] . '/tms/src\config\api.config.php'); ?>

<div class="grid grid-cols-4 gap-4 py-5">
    <div class="flex items-center justify-between py-2 px-6 bg-gray-600 rounded">
        <div class="flex items-center">
            <span class="text-3xl text-white mr-5">
                <i class="fas fa-users"></i>
            </span>
            <div>
                <p class="text-base font-semibold text-white">Total Users</p>
                <?php
                $apiEndpoint = $apiLink . '/userscount';
                $apiResponse = file_get_contents($apiEndpoint);
                $result = json_decode($apiResponse, true);
                if (isset($result['total_users'])) {
                    echo '<p class="text-xl text-white">' . $result['total_users'] . '</p>';
                } else {
                    echo '<p class="text-red-600">Error fetching data from API.</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-between py-2 px-6 bg-gray-600 rounded">
        <div class="flex items-center">
            <span class="text-3xl text-white mr-5">
                <i class="fas fa-tasks"></i>
            </span>
            <div>
                <p class="text-base font-semibold text-white">Total Tasks</p>
                <?php
                $apiEndpoint = $apiLink . '/taskscount';
                $apiResponse = file_get_contents($apiEndpoint);
                $result = json_decode($apiResponse, true);
                if (isset($result['total_tasks'])) {
                    echo '<p class="text-xl text-white">' . $result['total_tasks'] . '</p>';
                } else {
                    echo '<p class="text-red-600">Error fetching data from API.</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-span-2 py-2 px-6 bg-[#28A745] rounded">
        <h2 class="text-base font-semibold text-white">Top Performers of the Week</h2>
        <ul class="flex list-none text-white gap-2">
            <li class="line-clamp-1 text-base font-semibold"><i class="fas fa-trophy text-yellow-400 mr-1"></i><?php echo generateRandomName(); ?></li>
            <li class="line-clamp-1 text-base font-semibold"><i class="fas fa-trophy text-yellow-400 mr-1"></i><?php echo generateRandomName(); ?></li>
            <li class="line-clamp-1 text-base font-semibold"><i class="fas fa-trophy text-yellow-400 mr-1"></i><?php echo generateRandomName(); ?></li>
        </ul>
    </div>
    <?php
    function generateRandomName()
    {
        $firstNames = ["John", "Jane", "Lance", "Alice", "Bob"];
        $lastNames = ["Doe", "Smith", "Johnson", "Taylor", "Brown"];
        $fullName = $firstNames[array_rand($firstNames)] . " " . $lastNames[array_rand($lastNames)];
        return $fullName;
    }
    ?>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <h1 class="text-lg mb-1">Roles by Count</h1>
        <div class="bg-white p-2.5 rounded border">
            <?php
            // Fetch data from your API
            $apiEndpoint = $apiLink . '/countuserbyrole';
            $apiResponse = file_get_contents($apiEndpoint);
            $result = json_decode($apiResponse, true);

            if (isset($result['labels']) && isset($result['counts'])) {
            ?>
                <div class="overflow-x-auto max-h-64">
                    <table class="min-w-full border rounded">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border text-left font-semibold">Role</th>
                                <th class="py-2 px-4 border text-left font-semibold">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($result['labels']); $i++) { ?>
                                <tr class="<?php echo $i % 2 == 0 ? 'bg-[#F2F2F2]' : 'bg-white'; ?>">
                                    <td class="py-2 px-4 border"><?php echo $result['labels'][$i]; ?></td>
                                    <td class="py-2 px-4 border"><?php echo $result['counts'][$i]; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-red-600">Error fetching data from API.</p>
            <?php } ?>
        </div>
    </div>
    <div>
        <h1 class="text-lg mb-1">Department by Count</h1>
        <div class="bg-white p-2.5 rounded border">
            <?php
            // Fetch data from your API
            $apiEndpoint = $apiLink . '/countuserbydepartment';
            $apiResponse = file_get_contents($apiEndpoint);
            $result = json_decode($apiResponse, true);

            if (isset($result['labels']) && isset($result['counts'])) {
            ?>
                <div class="overflow-x-auto max-h-64">
                    <table class="min-w-full border rounded">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border text-left font-semibold">Department</th>
                                <th class="py-2 px-4 border text-left font-semibold">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($result['labels']); $i++) { ?>
                                <tr class="<?php echo $i % 2 == 0 ? 'bg-[#F2F2F2]' : 'bg-white'; ?>">
                                    <td class="py-2 px-4 border"><?php echo $result['labels'][$i]; ?></td>
                                    <td class="py-2 px-4 border"><?php echo $result['counts'][$i]; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-red-600">Error fetching data from API.</p>
            <?php } ?>
        </div>
    </div>
    <div>
        <h1 class="text-lg mb-1">Users Performance</h1>
        <div class="bg-white p-2.5 rounded border">
            <?php
            // Fetch data from your API
            $apiEndpoint = $apiLink . '/fetchallusersperformance';
            $apiResponse = file_get_contents($apiEndpoint);
            $result = json_decode($apiResponse, true);

            if ($result !== null && is_array($result)) {
            ?>
                <div class="overflow-x-auto max-h-64">
                    <table class="min-w-full border rounded">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border text-left font-semibold">Assigned User</th>
                                <th class="py-2 px-4 border text-left font-semibold">Tasks Done</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $key => $user) { ?>
                                <tr class="<?php echo $key % 2 == 0 ? 'bg-[#F2F2F2]' : 'bg-white'; ?>">
                                    <td class="py-2 px-4 border"><?php echo $user['username']; ?></td>
                                    <td class="py-2 px-4 border"><?php echo $user['tasks_done_count']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-red-600">Error fetching data from API.</p>
            <?php } ?>
        </div>
    </div>
    <div>
        <h1 class="text-lg mb-1">Tasks Overview</h1>
        <div class="bg-white p-2.5 rounded border">
            <?php
            // Fetch data from your API
            $apiEndpoint = $apiLink . '/fetchtaskcount';
            $apiResponse = file_get_contents($apiEndpoint);
            $result = json_decode($apiResponse, true);

            if ($result !== null && is_array($result)) {
            ?>
                <div class="overflow-x-auto max-h-64">
                    <table class="min-w-full border rounded">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border text-left font-semibold">Task Category</th>
                                <th class="py-2 px-4 border text-left font-semibold">Task Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result as $key => $task) { ?>
                                <tr class="<?php echo $key % 2 == 0 ? 'bg-[#F2F2F2]' : 'bg-white'; ?>">
                                    <td class="py-2 px-4 border"><?php echo $task['task_category']; ?></td>
                                    <td class="py-2 px-4 border"><?php echo $task['task_count']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-red-600">Error fetching data from API.</p>
            <?php } ?>
        </div>
    </div>
</div>