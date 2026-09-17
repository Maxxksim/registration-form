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
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-center">All members</h1>

    <table class="w-full border-collapse bg-white shadow-sm rounded-md overflow-hidden">
        <thead>
        <tr class="text-left text-sm text-gray-600">
            <th class="px-3 py-3 text-center">Photo</th>
            <th class="px-3 py-3 text-center">Full name</th>
            <th class="px-3 py-3 text-center">Report subject</th>
            <th class="px-3 py-3 text-center">Email</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($members as $member): ?>
            <tr class="border-t text-center">
                <td class="px-10 py-1">
                    <img src="<?= !empty($member['path_to_photo'])
                            ? '/photos/' . htmlspecialchars($member['path_to_photo'])
                            : '/photos/default.jpg' ?>"
                         alt="Photo"
                         class="w-14 h-14 rounded-md">
                </td>
                <td class="px-10 py-10 font-medium text-center">
                    <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                </td>
                <td class="px-10 py-10 text-gray-700 text-center">
                    <?= htmlspecialchars($member['report_subject']) ?>
                </td>
                <td class="px-10 py-10 text-center">
                    <a href="mailto:<?= htmlspecialchars($member['email']) ?>"
                       class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($member['email']) ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


</body>
</html>
