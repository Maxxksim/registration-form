<?php

declare(strict_types=1);

/**
 * @var $view
 * @var $countries
 * @var $steps
 *
 */

$current = $steps['current'] ?? 'step1';
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Registration Form Step 1</title>
</head>

<body class="flex flex-col items-center p-5 m-5">

<div id="step1" class="<?= $current !== 'step1' ? 'hidden' : '' ?>">
    <?php $view->component('step1', ['countries' => $countries, 'steps' => $steps]); ?>
</div>
<div id="step2" class="<?= $current !== 'step2' ? 'hidden' : '' ?>">
    <?php $view->component('step2'); ?>
</div>

</body>
</html>

<script>

    async function request(url, formId) {
        const form = document.getElementById(formId);
        const formData = new FormData(form);

        const response = await fetch(url, {
            method: 'POST',
            body: formData,
        })

        const result = await response.json();
        if (!response.ok) {
            showErrors(result.errors);
            return;
        }

        return result;
    }

    function switchSteps(step) {
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.add('hidden');
        document.getElementById(step).classList.remove('hidden');
    }

    document.getElementById('nextBtn').addEventListener('click', async function () {
        const result = await request('/register/next', 'step1-form');
        if (result) {
            switchSteps(result.nextStep);
        }
    });

    document.getElementById('backBtn').addEventListener('click', async function () {
        const response = await fetch('/register/back', {method: 'GET'});
        const result = await response.json();
        switchSteps(result.backStep);

    });

    function showErrors(errors) {

        document.querySelectorAll('[id$="_error"]').forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });

        for (const [field, error] of Object.entries(errors)) {
            let element = document.getElementById(`${field}_error`);
            element.textContent = error;
            element.classList.remove('hidden');
        }
    }

</script>