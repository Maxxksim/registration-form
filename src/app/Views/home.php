<?php

declare(strict_types=1);

/**
 * @var $countries
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
    <title>Registration Form</title>
</head>
<body class="flex flex-col items-center p-5 m-5">

<div class="flex flex-col border m-3">
    <h3>To participate in the conference, please fill out the form:</h3>
</div>

<div class="flex items-center justify-center border">
    <form action="/register" method="post" class="flex flex-col gap-5 w-200 place-content-center">
        <label>First Name
            <input type="text" value="Maksym" required maxlength="100" class="border rounded-xl">
        </label>
        <label>Last Name
            <input type="text" value="Chahin" required maxlength="100" class="border rounded-xl">
        </label>
        <label>Birthdate
            <input type="date" value="2004-12-21" required class="border rounded-xl">
        </label>
        <label>Report subject
            <input type="text" value="Testing" required maxlength="255" class="border rounded-xl">
        </label>
        <label>Phone
            <input type="tel" value="15555555555" required maxlength="17" pattern="[1]{1}[0-9]{3}[0-9]{3}[0-9]{4}"
                   class="border rounded-xl">
        </label>
        <label>Country
            <select name="country" class="border rounded-xl">
                <?php foreach ($countries as $country)
                    echo "<option value='$country[name]'>$country[name]</option>"
                ?>
            </select>
        </label>
        <label>Email
            <input type="email" value="xackiiiii@gmail.com" required class="border rounded-xl w-30">
        </label>
        <div class="justify-end">
            <button type="submit" class="border rounded-md w-30 flex">Register</button>
        </div>

    </form>
</div>

</body>
</html>
