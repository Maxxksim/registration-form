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
    <title>All members</title>
</head>
<body>
<?php
foreach ($members as $member) {
    echo 'full name: ' . htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) . "</br>";
    echo 'photo: ' . ($member['path_to_photo'] ? htmlspecialchars($member['path_to_photo']) : 'DEFAULT PHOTO') . "</br>";
    echo 'report_subject: ' . htmlspecialchars($member['report_subject']) . "</br>";
    echo "<a href='mailto:" . htmlspecialchars($member['email']) . "'>" . htmlspecialchars($member['email']) . "</a>";
}
?>
</body>
</html>
