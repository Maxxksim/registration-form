<?php

declare(strict_types=1);

/**
 * @var $countries
 * @var $steps
 */

?>

<div id="map" class="h-[450px] w-auto border rounded-md m-10"></div>
<div class="flex flex-col  m-3">
    <h1 class="text-lg">To participate in the conference, please fill out the form:</h1>
</div>

<form method="post"
      class="max-w-md mx-auto flex flex-col gap-4 p-4"
      id="step1-form">
    <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <div class="relative z-0 w-full group"><p class="">
            Fields marked with <span class="text-red-500">*</span> are required.
        </p></div>
    <div class="relative z-0 w-full group"><label class="after:ml-1 after:text-red-500 after:content-['*']">First
            Name</label>
        <input type="text" value="<?= htmlspecialchars($steps['data']['first_name'] ?? '') ?>"
               required name="first_name" class="border rounded-md w-full px-3 py-2">
        <p class="hidden text-red-700" id="first_name_error"></p>
    </div>
    <div class="relative z-0 w-full group"><label class="after:ml-1 after:text-red-500 after:content-['*']">Last
            Name</label>
        <input type="text" value="<?= htmlspecialchars($steps['data']['last_name'] ?? '') ?>"
               name="last_name"
               required maxlength="100" class="border rounded-md w-full px-3 py-2">
        <p class="hidden text-red-700" id="last_name_error"></p>
    </div>
    <div class="relative z-0 w-full group"><label
                class="after:ml-1 after:text-red-500 after:content-['*']">Birthdate</label>
        <input type="date" value="<?= htmlspecialchars($steps['data']['birthdate'] ?? '') ?>"
               name="birthdate"
               id="birthdate"
               required class="border rounded-md w-full px-3 py-2 ">
        <p class="hidden text-red-700 " id="birthdate_error"></p>
    </div>
    <div class="relative z-0 w-full group"><label class="after:ml-1 after:text-red-500 after:content-['*']">Report
            subject</label>
        <input type="text" value="<?= htmlspecialchars($steps['data']['report_subject'] ?? '') ?>"
               name="report_subject" required maxlength="255" class="border rounded-md w-full px-3 py-2">
        <p class="hidden text-red-700" id="report_subject_error"></p>
    </div>
    <div class="relative z-0 w-full group"><label
                class="after:ml-1 after:text-red-500 after:content-['*']">Phone</label>
        <input type="tel" value="<?= htmlspecialchars($steps['data']['phone'] ?? '') ?>" id="phone" name="phone"
               required

               class="border rounded-md w-full px-3 py-2">
        <p class="hidden text-red-700" id="phone_error"></p>
    </div>
    <div class="relative z-0 w-full group"><label
                class="after:ml-1 after:text-red-500 after:content-['*'] ">Country</label>
        <select name="country" class="border rounded-md w-full px-3 py-2">
            <option value="" <?= empty($steps['data']['country']) ? 'selected' : '' ?> disabled>Select country
            </option>
            <?php foreach ($countries as $country) { ?>
                <option value="<?= htmlspecialchars($country['name']) ?>"
                        <?= ($steps['data']['country'] ?? '') === $country['name'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($country['name']) ?>
                </option>
            <?php } ?>
        </select>
        <p class="hidden text-red-700" id="country_error"></p>
    </div>
    <div class="relative z-0 w-full group"><label
                class="after:ml-1 after:text-red-500 after:content-['*']">Email</label>
        <input type="email" value="<?= htmlspecialchars($steps['data']['email'] ?? '') ?>" name="email"
               required class="border rounded-md w-full px-3 py-2">
        <p class="hidden text-red-700" id="email_error"></p>
    </div>
    <button id="stepOneBtn" type="button" class="border rounded-md w-full px-3 py-2 hover:bg-gray-300">Next</button>
</form>



