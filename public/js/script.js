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

document.getElementById('phone').addEventListener('input', function (event) {
    let value = event.target.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.slice(0, 11);
    }

    let formatted = '+1 (';
    if (value.length > 1) formatted += value.slice(1, 4);
    if (value.length > 4) formatted += ') ' + value.slice(4, 7);
    if (value.length > 7) formatted += '-' + value.slice(7, 11);

    event.target.value = formatted;
});

function getFormattedPhoneNumber(phoneNumber) {
    return `+${phoneNumber.slice(0, 1)} (${phoneNumber.slice(1, 4)}) ${phoneNumber.slice(4, 7)}-${phoneNumber.slice(7, 11)}`;
}

document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('phone');
    element.value = getFormattedPhoneNumber(element.value);
});


