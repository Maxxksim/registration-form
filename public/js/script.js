const birthDate = document.getElementById('birthdate');

birthDate.max = new Date().toISOString().split('T')[0];

const map = L.map('map').setView([34.10114, -118.34376], 80);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var marker = L.marker([34.10114, -118.34376]).addTo(map);
marker.bindTooltip('7060 Hollywood Blvd, Los Angeles, CA', {
    permanent: true,
    direction: 'top'
}).openTooltip();


function getForm(formId) {
    const form = document.getElementById(formId);
    return new FormData(form);
}

async function request(url, formData) {
    const csrfToken = document.getElementById('csrf_token').value;
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-Token': csrfToken,
        },
        body: formData,
    })

    const result = await response.json();
    if (response.status === 413) {
        showErrors(result.errors);
    }
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

document.getElementById('stepTwoBtn').addEventListener('click', async function () {
    const result = await request('/register/steps/two', getForm('step2-form'));
    document.getElementById('countMembers').textContent = `All members (${result.countMembers})`;
    switchSteps(result.nextStep);
});
document.getElementById('stepOneBtn').addEventListener('click', async function () {
    clearError();
    formData = getForm('step1-form');
    const phone = formData.get('phone')
    if (phone) {
        formData.set('phone', phone.replace(/\D/g, ''));
    }
    const result = await request('/register/steps/one', formData);
    if (result) {
        switchSteps(result.nextStep);
    }

});

document.getElementById('backStepBtn').addEventListener('click', async function () {
    clearError();
    const response = await fetch('/register/steps/back', {method: 'GET'});
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

document.querySelectorAll('input,textarea,select').forEach(element => {
    element.addEventListener('change', function (el) {
        const field = el.target.name;
        const errorElement = document.getElementById(`${field}_error`);
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.classList.add('hidden');
        }
    });
});
document.getElementById('cancel').addEventListener('click', function () {
    document.getElementById('photo').value = '';
    document.getElementById('cancel').classList.add('hidden');
});

document.getElementById('photo').addEventListener('change', function () {
    const photoElement = document.getElementById('photo');
    if (photoElement.value !== '' && photoElement.files.length !== 0) {
        document.getElementById('cancel').classList.remove('hidden');
    }
});

const phone = document.getElementById('phone');
const country = document.getElementById('country');
const mask = IMask(phone, {mask: '+000000000000000'});

const phoneNumberUtil = libphonenumber.PhoneNumberUtil.getInstance();
const phoneNumberFormat = libphonenumber.PhoneNumberFormat;

country.addEventListener('change', function (event) {
    if (event.target.value !== '') {
        phone.disabled = false;
        phone.value = '';
        document.getElementById('phone_hint').hidden = true;

        const selectedCountry = country.selectedOptions[0];
        const codeSelectedCountry = selectedCountry.dataset.alpha2;

        const example = phoneNumberUtil.getExampleNumber(codeSelectedCountry);

        const formatted = phoneNumberUtil.format(example, phoneNumberFormat.INTERNATIONAL);
        mask.updateOptions({mask: formatted.replace(/\d/g, '0')});
    }
});





