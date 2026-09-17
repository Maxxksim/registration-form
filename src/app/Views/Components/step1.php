<?php

declare(strict_types=1);

/**
 * @var $countries
 * @var $steps
 */

?>


<div id="map" class="h-[450px] w-full"></div>
<div class="flex flex-col  m-3">
    <h1 class="text-lg">To participate in the conference, please fill out the form:</h1>
</div>
<div class="flex items-center justify-center border">
    <form action="/register/step1" method="post" class="flex flex-col gap-5 w-200 place-content-center p-20"
          id="step1-form">
        <label>First Name
            <input type="text" value="<?= htmlspecialchars($steps['data']['first_name'] ?? '') ?>"
                   required name="first_name" class="border rounded-md w-70">
            <p class="hidden text-red-700" id="first_name_error"></p>
        </label>
        <label>Last Name
            <input type="text" value="<?= htmlspecialchars($steps['data']['last_name'] ?? '') ?>"
                   name="last_name"
                   required maxlength="100" class="border rounded-md w-70">
            <p class="hidden text-red-700" id="last_name_error"></p>
        </label>
        <label>Birthdate
            <input type="date" value="<?= htmlspecialchars($steps['data']['birthdate'] ?? '') ?>"
                   name="birthdate"
                   required class="border rounded-md w-70">
            <p class="hidden text-red-700" id="birthdate_error"></p>
        </label>
        <label>Report subject
            <input type="text" value="<?= htmlspecialchars($steps['data']['report_subject'] ?? '') ?>"
                   name="report_subject" required maxlength="255" class="border rounded-md w-70">
            <p class="hidden text-red-700" id="report_subject_error"></p>
        </label>
        <label>Phone
            <input type="tel" value="<?= htmlspecialchars($steps['data']['phone'] ?? '') ?>" name="phone"
                   required
                   maxlength="17"
                   pattern="[1]{1}[0-9]{3}[0-9]{3}[0-9]{4}"
                   class="border rounded-md w-70">
            <p class="hidden text-red-700" id="phone_error"></p>
        </label>
        <label>Country
            <select name="country" class="border rounded-md w-70">
                <option value="<?= htmlspecialchars($steps['data']['country'] ?? '') ?>" selected>Choose
                    country
                </option>
                <?php foreach ($countries as $country)
                    echo "<option value='$country[name]'>$country[name]</option>"
                ?>
            </select>
            <p class="hidden text-red-700" id="country_error"></p>
        </label>
        <label>Email
            <input type="email" value="<?= htmlspecialchars($steps['data']['email'] ?? '') ?>" name="email"
                   required class="border rounded-md w-70">
            <p class="hidden text-red-700" id="email_error"></p>
        </label>
    </form>
    <button id="nextBtn" class="border rounded-md w-30 flex">Next</button>
</div>

