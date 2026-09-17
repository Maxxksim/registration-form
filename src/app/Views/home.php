<?php

declare(strict_types=1);

/**
 * @var $view
 * @var $countries
 * @var $steps
 * @var $countMembers
 * @var $sharing
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

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Registration Form</title>
</head>

<body class="flex flex-col items-center p-5 m-5">

<div id="step1" class="<?= $current !== 'step1' ? 'hidden' : '' ?>">
    <?php $view->component('step1', ['countries' => $countries, 'steps' => $steps]); ?>
</div>
<div id="step2" class="<?= $current !== 'step2' ? 'hidden' : '' ?>">
    <?php $view->component('step2', ['steps' => $steps]); ?>
</div>
<div id="stepThanks" class="<?= $current === 'stepThanks' ? '' : 'hidden' ?>">
    <?php $view->component('stepThanks', ['countMembers' => $countMembers, 'sharing' => $sharing]); ?>
</div>


</body>
</html>

<script>

    const map = L.map('map').setView([34.10114, -118.34376], 80);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([34.10114, -118.34376]).addTo(map);
    marker.bindTooltip('7060 Hollywood Blvd, Los Angeles, CA', {
        permanent: true,
        direction: 'top'
    }).openTooltip();

    function addEventListener(id, event, callback) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener(event, callback);
        }
    }

    function getForm(formId) {
        const form = document.getElementById(formId);
        return new FormData(form);
    }

    async function request(url, formData) {

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

    addEventListener('finishBtn', 'click', async function () {
        const result = await request('/member/update', getForm('step2-form'));
        switchSteps(result.nextStep);
    });

    addEventListener('nextBtn', 'click', async function () {
        clearError();

        const result = await request('/register/next', getForm('step1-form'));
        if (result) {
            switchSteps(result.nextStep);
        }
    });

    addEventListener('backBtn', 'click', async function () {
        clearError();
        const response = await fetch('/register/back', {method: 'GET'});
        const result = await response.json();
        switchSteps(result.backStep);

    });

    function clearError() {
        document.querySelectorAll('[id$="_error"]').forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });
    }

    function showErrors(errors) {
        for (const [field, error] of Object.entries(errors)) {
            let element = document.getElementById(`${field}_error`);
            element.textContent = error;
            element.classList.remove('hidden');
        }
    }

</script>