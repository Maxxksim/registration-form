const map = L.map('map').setView([34.10114, -118.34376], 80);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var marker = L.marker([34.10114, -118.34376]).addTo(map);
marker.bindTooltip('7060 Hollywood Blvd, Los Angeles, CA', {
    permanent: true,
    direction: 'top'
}).openTooltip();

function addEventListener(id, event, callback) {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener(event, callback);
    }
}

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

addEventListener('finishBtn', 'click', async function () {
    const result = await request('/register/next', getForm('step2-form'));
    document.getElementById('countMembers').textContent = `All members (${result.countMembers})`;
    switchSteps(result.nextStep);
});

addEventListener('nextBtn', 'click', async function () {
    clearError();

    const result = await request('/register/next', getForm('step1-form'));
    if (result) {
        switchSteps(result.nextStep);
    }
});

addEventListener('backBtn', 'click', async function () {
    clearError();
    const response = await fetch('/register/back', {method: 'GET'});
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
