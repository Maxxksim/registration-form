<form method="post" enctype="multipart/form-data" id="step2-form"
      class="max-w-md mx-auto flex flex-col gap-4 p-4">

    <div class="flex flex-col gap-1">
        <label for="company" class="text-sm font-medium">Company</label>
        <input type="text" id="company" name="company"
               value="<?= htmlspecialchars($steps['data']['company'] ?? '') ?>"
               class="border rounded-md w-full px-3 py-2">
        <p class="hidden text-red-700 text-sm" id="company_error"></p>
    </div>

    <div class="flex flex-col gap-1">
        <label for="position" class="text-sm font-medium">Position</label>
        <input type="text" id="position" name="position"
               value="<?= htmlspecialchars($steps['data']['position'] ?? '') ?>"
               class="border rounded-md w-full px-3 py-2">
        <p class="hidden text-red-700 text-sm" id="position_error"></p>
    </div>

    <div class="flex flex-col gap-1">
        <label for="about_me" class="text-sm font-medium">About me</label>
        <textarea name="about_me" id="about_me" rows="5"
                  class="border rounded-md w-full px-3 py-2"><?= htmlspecialchars($steps['data']['about_me'] ?? '') ?></textarea>
        <p class="hidden text-red-700 text-sm" id="about_me_error"></p>
    </div>

    <div class="flex flex-col gap-1">
        <label for="photo" class="text-sm font-medium">Photo</label>
        <input type="file" id="photo" name="photo" accept="image/png, image/jpeg, image/webp"
               class="border rounded-md w-full px-3 py-2 hover:file:bg-gray-300">
        <p class="hidden text-red-700 text-sm" id="photo_error"></p>
    </div>

    <div class="flex gap-3">
        <button type="button" id="backBtn"
                class="border rounded-md w-full px-3 py-2 hover:bg-gray-300">Back</button>
        <button type="button" id="finishBtn"
                class="border rounded-md w-full px-3 py-2 hover:bg-gray-300">Finish</button>
    </div>

</form>