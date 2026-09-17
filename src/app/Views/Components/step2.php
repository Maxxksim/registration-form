<div class="flex items-center justify-center border">
    <form method="post" enctype="multipart/form-data" id="step2-form"
          class="flex flex-col gap-5 w-200 place-content-center">
        <label>Company
            <input type="text" value="<?= htmlspecialchars($steps['data']['company'] ?? '') ?>"
                   name="company" class="border rounded-md w-70">
            <p class="hidden text-red-700" id="company_error"></p>
        </label>
        <label>Position
            <input type="text" value="<?= htmlspecialchars($steps['data']['position'] ?? '') ?>"
                   name="position"
                   class="border rounded-md w-70">
            <p class="hidden text-red-700" id="position_error"></p>
        </label>

        <label for="About me">About me
            <textarea name="about_me" id="about_me" class="border" cols="40"
                      rows="5">
                <?= htmlspecialchars($steps['data']['about_me'] ?? '') ?>
            </textarea>
        </label>
        <p class="hidden text-red-700" id="about_me_error"></p>
        <label>Photo
            <input type="file" name="photo" accept="image/png, image/jpeg, image/webp">
            <p class="hidden text-red-700" id="photo_error"></p>
        </label>
        <div class="justify-end">
            <button type="button" formaction="/update" formmethod="post" class="border rounded-md w-30 flex">
                Update
            </button>
        </div>
    </form>
    <button class="border" id="backBtn">Back</button>
    <button class="border" id="finishBtn">Finish</button>
</div>


