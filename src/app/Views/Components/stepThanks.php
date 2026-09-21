<?php

declare(strict_types=1);
/**
 * @var $countMembers
 * @var $shareData
 */
?>

<div class="mx-auto w-full m-30 p-30 text-center">
    <h1 class="text-2xl font-bold mb-6 ">
        Thanks
    </h1>

    <div class="mx-auto grid w-full max-w-xs grid-cols-2 gap-2 text-sm">
        <a href="/members" class="text-blue-700 hover:underline" id="countMembers">All
            members
            (<?= $countMembers ?>)</a>
        <a href="/" id="startOver" class="text-gray-700 hover:underline">Start
            over</a>


        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareData['url']) ?>" type="button" class="border rounded-md p-2" target="_blank">Share
            to
            Facebook</a>


        <a href="https://x.com/intent/tweet?text=<?= urlencode($shareData['text']) ?>&url=<?= urlencode($shareData['url']) ?>"
           target="_blank" type="button" class="border rounded-md p-2">Share
            to Twitter</a>
    </div>
</div>