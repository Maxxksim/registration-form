<?php

declare(strict_types=1);
/**
 * @var $countMembers
 * @var $shareData
 */
?>


<div class="w-full mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6 text-center">
        Thanks
    </h1>

    <div class="flex justify-center gap-6 mb-6 text-sm">
        <a href="/members" class="text-blue-700 hover:underline" id="countMembers">All members (<?= $countMembers ?>
            )</a>
        <a href="/" id="startOver" class="text-gray-700 hover:underline">Start over</a>
    </div>
    <div class="flex justify-center flex-col gap-6 mb-6 text-sm w-full">
            <ul class="space-y-5 space-y-5 flex flex-col items-center">
                <?php foreach ($shareData as $name => $url): ?>
                    <button class="hover:bg-gray-300" id="<?= $name ?>Btn">
                        <li class="flex flex-col gap-1 border rounded-md p-3">
                            <span><strong><?= htmlspecialchars($name) ?></strong> - <?= htmlspecialchars($url) ?></span>
                        </li>
                    </button>
                <?php endforeach; ?>
            </ul>
    </div>
</div>