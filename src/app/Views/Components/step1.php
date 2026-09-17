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
    <div class="relative z-0 w-full mb-6 group"><label class="text-sm font-medium">First Name
            <input type="text" value="<?= htmlspecialchars($steps['data']['first_name'] ?? '') ?>"
                   required name="first_name" class="border rounded-md w-full px-3 py-2">
            <p class="hidden text-red-700" id="first_name_error"></p>
        </label></div>
    <div class="relative z-0 w-full mb-6 group"><label class="text-sm font-medium">Last Name
            <input type="text" value="<?= htmlspecialchars($steps['data']['last_name'] ?? '') ?>"
                   name="last_name"
                   required maxlength="100" class="border rounded-md w-full px-3 py-2">
            <p class="hidden text-red-700" id="last_name_error"></p>
        </label></div>
    <div class="relative z-0 w-full mb-6 group"><label class="text-sm font-medium">Birthdate
            <input type="date" value="<?= htmlspecialchars($steps['data']['birthdate'] ?? '') ?>"
                   name="birthdate"
                   required class="border rounded-md w-full px-3 py-2">
            <p class="hidden text-red-700" id="birthdate_error"></p>
        </label></div>
    <div class="relative z-0 w-full mb-6 group"><label class="text-sm font-medium">Report subject
            <input type="text" value="<?= htmlspecialchars($steps['data']['report_subject'] ?? '') ?>"
                   name="report_subject" required maxlength="255" class="border rounded-md w-full px-3 py-2">
            <p class="hidden text-red-700" id="report_subject_error"></p>
        </label></div>
    <div class="relative z-0 w-full mb-6 group"><label class="text-sm font-medium">Phone
            <input type="tel" value="<?= htmlspecialchars($steps['data']['phone'] ?? '') ?>" name="phone"
                   required
                   maxlength="17"
                   pattern="[1]{1}[0-9]{3}[0-9]{3}[0-9]{4}"
                   class="border rounded-md w-full px-3 py-2">
            <p class="hidden text-red-700" id="phone_error"></p>
        </label></div>
    <div class="relative z-0 w-full mb-6 group"><label class="text-sm font-medium">Country
            <select name="country" class="border rounded-md w-full px-3 py-2">
                <option value="<?= htmlspecialchars($steps['data']['country'] ?? '') ?>" selected>Choose
                    country
                </option>
                <?php foreach ($countries as $country)
                    echo "<option value='$country[name]'>$country[name]</option>"
                ?>
            </select>
            <p class="hidden text-red-700" id="country_error"></p>
        </label></div>
    <div class="relative z-0 w-full mb-6 group"><label class="text-sm font-medium">Email
            <input type="email" value="<?= htmlspecialchars($steps['data']['email'] ?? '') ?>" name="email"
                   required class="border rounded-md w-full px-3 py-2">
            <p class="hidden text-red-700" id="email_error"></p>
        </label></div>
    <button id="nextBtn" type="button" class="border rounded-md w-full px-3 py-2 hover:bg-gray-300">Next</button>
</form>




