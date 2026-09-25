let inputsForInit = null;
const phone = document.getElementById('phone');
const stepOneBtn = document.getElementById('stepOneBtn');
const stepTwoBtn = document.getElementById('stepTwoBtn');
const countMembers = document.getElementById('countMembers');
const backStepBtn = document.getElementById('backStepBtn');
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

    let result;
    try {
        result = await response.json();
    } catch (parseError) {
        showErrors({'server': 'Something went wrong. Please try again later.'})
        return;
    }

    errorHandler(response, result);

    return result;
}

function errorHandler(response, result) {
    if (response.status === 500) {
        showErrors({'server': 'Something went wrong. Please try again later.'})
        return;
    }

    if (!response.ok) {
        showErrors(result.errors);
        return;
    }
}

function switchSteps(step) {
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById(step).classList.remove('hidden');
}

stepTwoBtn.addEventListener('click', async function () {
    clearErrors();
    stepTwoBtn.disabled = true;
    try {
        const result = await request('/register/steps/two', getForm('step2-form'));
        if (result) {
            countMembers.textContent = `All members (${result.countMembers})`;
            switchSteps(result.nextStep);
        }
    } finally {
        stepTwoBtn.disabled = false;
    }
});

stepOneBtn.addEventListener('click', async function () {
    clearErrors();
    const formData = getForm('step1-form');

    if (iti) {
        await iti.promise
        const number = iti.getNumber()

        formData.set('phone', number);
    }

    stepOneBtn.disabled = true;
    try {
        const result = await request('/register/steps/one', formData);

        if (result) {
            switchSteps(result.nextStep);
        }
    } finally {
        stepOneBtn.disabled = false;
    }

});

backStepBtn.addEventListener('click', async function () {
    clearErrors();
    backStepBtn.disabled = true;
    let result;
    const response = await fetch('/register/steps/back', {method: 'GET'});
    try {
        result = await response.json();
    } catch (ParseError) {
        showErrors({'server': 'Something went wrong. Please try again later.'})
        return;
    } finally {
        backStepBtn.disabled = false;
    }

    errorHandler(response, result);
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
        element.classList.remove('animate-pulse');
        setTimeout(() => {
            element.classList.add('animate-pulse');
            setTimeout(() => element.classList.remove('animate-pulse'), 10000);
        }, 10);
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
        const maxDate = new Date(birthdateMax)
        flatPicker = flatpickr("#birthdate", {
            dateFormat: "Y-m-d",
            maxDate: birthdateMax,
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates[0] > birthdateMax) {
                    showErrors({
                        birthdate: "Birthdate cannot be in the future"
                    });
                } else {
                    clearErrors();
                }
            },
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






