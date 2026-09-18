<?php

declare(strict_types=1);
/**
 * @var $countMembers
 * @var $sharing
 */
?>


<div class="max-w-md mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6 text-center">
        Thanks
    </h1>

    <div class="flex justify-center gap-6 mb-6 text-sm">
        <a href="/members" class="text-blue-700 hover:underline" id="countMembers">All members (<?= $countMembers ?>)</a>
        <a href="/" id="startOver" class="text-gray-700 hover:underline">Start over</a>
    </div>

    <ul class="space-y-3">
        <?php foreach ($sharing as $name => $link): ?>
            <li class="flex flex-col gap-1 border rounded-md p-3">
                <span class="font-medium text-sm text-black-700"><?= htmlspecialchars($name) ?></span>
                <a href="<?= htmlspecialchars($link) ?>" target="_blank"
                   class="text-blue-600 hover:underline break-all text-sm">
                    <?= htmlspecialchars($link) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>