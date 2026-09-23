let inputsForInit = null;
const phone = document.getElementById('phone');
let iti = null;
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
        return;
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
    const countMembers = document.getElementById('countMembers');
    if (result) {
        countMembers.textContent = `All members (${result.countMembers})`;
        switchSteps(result.nextStep);
    }
});
document.getElementById('stepOneBtn').addEventListener('click', async function () {
    clearErrors();
    const formData = getForm('step1-form');

    if (iti) {
        await iti.promise
        const number = iti.getNumber()
        console.log(number);
        formData.set('phone', number);
    }

    const result = await request('/register/steps/one', formData);

    if (result) {
        switchSteps(result.nextStep);
    }

});

document.getElementById('backStepBtn').addEventListener('click', async function () {
    clearErrors();
    const response = await fetch('/register/steps/back', {method: 'GET'});
    const result = await response.json();
    switchSteps(result.backStep);

});

function clearErrors() {
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
    clearErrors();
});

document.getElementById('photo').addEventListener('change', function () {
    const photoElement = document.getElementById('photo');
    if (photoElement.value !== '' && photoElement.files.length !== 0) {
        document.getElementById('cancel').classList.remove('hidden');
    }
});

function initInputs() {
    if (!inputsForInit) {
        const birthdateMax = new Date().toISOString().split('T')[0];
        flatPicker = flatpickr("#birthdate", {
            dateFormat: "Y-m-d",
            maxDate: birthdateMax,
        });

        const initialCountryLookup = async () => {

            const cachedUserCountry = sessionStorage.getItem('userCountry');
            if (cachedUserCountry) {
                return cachedUserCountry;
            }

            const res = await fetch("https://ipapi.co/json");
            const data = await res.json();
            sessionStorage.setItem('userCountry', data.country_code)
            return data.country_code;
        }

        iti = window.intlTelInput(phone, {
            initialCountryLookup,
            classNames: {
                input: "border rounded-md w-full px-3 py-2",
                container: "w-full block",
            },
            formatAsYouType: true,
            customPlaceholder: () => 'Enter your number',
            loadUtils: () =>
                import('https://cdn.jsdelivr.net/npm/intl-tel-input@29.5.2/dist/js/utils.js'),
        });

        if (phone.value) {
            iti.setNumber(phone.value);
        }
    }
}


document.addEventListener('DOMContentLoaded', initInputs);






