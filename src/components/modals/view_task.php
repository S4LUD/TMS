<div id="viewTask" class="select-none fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeViewTaskModal()">
    <div class="bg-white p-4 rounded shadow-md w-96" onclick="event.stopPropagation();">
        <div class="mb-1 flex justify-between">
            <div class="w-80">
                <p id="taskTitle" class="text-2xl font-medium font-semibold"></p>
            </div>
            <i onclick="closeViewTaskModal()" class="fa-solid fa-xmark text-white bg-gray-300 hover:bg-gray-400 py-1 px-1.5 rounded-full cursor-pointer"></i>
        </div>
        <div>
            <div>
                Status
            </div>
            <div>
                Due Date
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <?php $tabs = array("Description", "Attachments"); ?>
            <div class="flex gap-3">
                <?php
                foreach ($tabs as $index => $tab) {
                    $tabClass = $index === 0 ? 'text-black relative font-semibold' : 'text-gray-500'; // Set the active tab's text style
                    $underlineClass = $index === 0 ? 'border-b-2 border-blue-500 absolute bottom-0 left-0 right-0' : 'border-b-2 border-blue-500 absolute bottom-0 left-0 right-0'; // Set the active tab's underline style
                    echo '<div class="tab cursor-pointer pb-0.5 ' . $tabClass . '" onclick="openTab(event, \'tab' . ($index + 1) . '\')">' . $tab . '<div class="' . $underlineClass . '"></div></div>';
                }
                ?>
            </div>
            <?php
            foreach ($tabs as $index => $tab) {
                $tabDisplay = $index === 0 ? 'block' : 'hidden'; // Set the active tab's display property
                $contentClass = $index === 0 ? 'text-gray-600 overflow-y-auto max-h-52' : 'flex flex-col gap-2 overflow-y-auto max-h-52'; // Set the content class based on the active tab
                echo '<div id="tab' . ($index + 1) . '" class="tab-content ' . $tabDisplay . '">';
                if ($index === 0) {
                    echo '<p id="taskDetailsContent" class="' . $contentClass . '"></p>';
                } else {
                    echo '<div id="viewFilePreview" class="' . $contentClass . '"></div>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>