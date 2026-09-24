<?php

declare(strict_types=1);

/**
 * @var $view
 * @var $countries
 * @var $steps
 * @var $countMembers
 * @var $shareData
 * @var $countriesCodes
 *
 */

$current = $steps['current'];
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/google-libphonenumber"></script>
    <script src="https://unpkg.com/imask"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@29.5.2/dist/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@29.5.2/dist/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>Registration Form</title>
</head>

<body class="bg-blue-100">
<div id="map" class="h-[450px] w-auto border rounded-md m-10"></div>
<div id="step1" class="<?= $current !== 'step1' ? 'hidden' : '' ?>">
    <?php $view->component('step1', ['countries' => $countries, 'steps' => $steps]); ?>
</div>
<div id="step2" class="<?= $current !== 'step2' ? 'hidden' : '' ?>">
    <?php $view->component('step2', ['steps' => $steps]); ?>
</div>
<div id="stepThanks" class="<?= $current === 'stepThanks' ? '' : 'hidden' ?>">
    <?php $view->component('stepThanks', ['countMembers' => $countMembers, 'shareData' => $shareData, 'countriesCodes' => $countriesCodes]); ?>
</div>

<script src="/js/script.js"></script>
</body>
</html>

