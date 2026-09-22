<?php

declare(strict_types=1);

/**
 * @var $members
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>All members</title>
</head>
<body class="bg-blue-200">
<div class="m-5"><h1 class="text-2xl font-bold mb-4 text-center">All members</h1></div>
<div class="mx-5 md:mx-10 text-center rounded-md">
    <div class="hidden md:grid grid-cols-4 rounded-md font-semibold mb-5">
        <div class="border m-2 rounded-md bg-white"><h2>Photo</h2></div>
        <div class="border m-2 rounded-md bg-white"><h2>Full name</h2></div>
        <div class="border m-2 rounded-md bg-white"><h2>Report subject</h2></div>
        <div class="border m-2 rounded-md bg-white"><h2>Email</h2></div>
    </div>
    <div class="flex flex-col gap-4 md:gap-0">
        <?php foreach ($members as $member): ?>
            <div class="border rounded m-1">
                <div class="grid grid-cols-1 rounded-md gap-3 bg-white rounded-md md:rounded-none md:grid-cols-4 md:items-center  md:border-t-0">
                    <div class="flex items-center gap-3 p-5 md:justify-center">
                <span class="font-semibold md:hidden">
                Photo:
            </span>
                        <img src="<?= !empty($member['path_to_photo'])

                                ? '/photos/' . htmlspecialchars($member['path_to_photo'])
                                : '/photos/default.jpg' ?>"
                             alt="Photo"
                             class="w-20 h-20 rounded-md">
                    </div>

                    <div class="p-5">
                <span class="font-semibold md:hidden">
                Full name:
            </span>
                        <span>
                <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
            </span>
                    </div>

                    <div>
            <span class="font-semibold md:hidden">
                Report subject:
            </span>
                        <span>
                <?= htmlspecialchars($member['report_subject']) ?>
            </span>
                    </div>

                    <div>
            <span class="font-semibold md:hidden">
                Email:
            </span>
                        <span>
                <a href="mailto:<?= htmlspecialchars($member['email']) ?>"
                   class="text-blue-500 hover:underline"><?= htmlspecialchars($member['email']) ?></a>
                </span>
                    </div>


                </div>
            </div>
        <?php endforeach; ?>
    </div>


</body>
</html>
