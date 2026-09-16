<div class="flex flex-col border m-3">
    <h3>To participate in the conference, please fill out the form:</h3>
</div>

<div class="flex items-center justify-center border">
    <form action="/register/step2" method="post" enctype="multipart/form-data" id="step2-form"
          class="flex flex-col gap-5 w-200 place-content-center">
        <label>Company
            <input type="text" value=""
                   name="company" class="border rounded-md w-70">
            <p class="hidden" id="company_error"></p>
        </label>
        <label>Position
            <input type="date" value=""
                   name="birthdate"
                   class="border rounded-md w-70">
            <p class="hidden" id="position_error"></p>
        </label>
        <label for="About me">About me
            <textarea name="about_me" id="about_me_error" class="border-md" cols="30"
                      rows="10">
            </textarea>
        </label>
        <label>Photo
            <input type="file" name="photo" accept="image/png, image/jpeg, image/webp">
            <p class="hidden" id="photo_error"></p>
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


