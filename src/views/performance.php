<h1 class="text-2xl font-bold">Employees Performance</h1>
<div class="flex flex-row">
    <div id="completion-data" class="flex justify-center items-center">
        <div class="relative rotate-180">
            <div class="w-20 h-20 rounded-full flex justify-center items-center rotate-180">
                <span id="completion-rate" class="text-sm"></span>
            </div>
            <svg class="absolute top-0 left-0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <circle class="stroke-current text-gray-200" cx="10" cy="10" r="9" fill="transparent" stroke-width="1" />
            </svg>
            <svg id="completion-circle" class="absolute top-0 left-0 rotate-90" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <circle class="stroke-current text-blue-500" cx="10" cy="10" r="9" fill="transparent" stroke-width="1" />
            </svg>
        </div>
    </div>

    <div>Test</div>
</div>
<canvas id="tasksChart"></canvas>

<script src="/tms/src/assets/scripts/performance/index.js"></script>