<?php

declare(strict_types=1);
/**
 * @var $countMembers
 * @var $sharing
 */
?>

<h1>
    Thanks
</h1>
<div>
    <div>
        <a href="/members">All members (<?= $countMembers ?>)</a>
        <a href="/" id="startOver">Start over</a>
    </div>
    <ul>
        <?php foreach ($sharing as $name => $link): ?>
            <li class="flex items-center gap-2">
                <span><?= $name ?>:</span>
                <a href="<?= $link ?>" target="_blank" rel="noopener noreferrer"
                   class="">
                    <?= $link ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
