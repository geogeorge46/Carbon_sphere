document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('register-form');
    if (!registerForm) return;

    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const email = document.getElementById('email');
    const phoneNumber = document.getElementById('phone_number');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const registerButton = document.getElementById('register-button');

    // Keys must match the input element IDs in the registration form
    const validationRules = {
        first_name: {
            validate: (value) => /^[A-Za-z]{2,}$/.test(value),
            message: 'First name must be at least 2 letters and contain only letters.'
        },
        last_name: {
            validate: (value) => /^[A-Za-z]{2,}$/.test(value),
            message: 'Last name must be at least 2 letters and contain only letters.'
        },
        email: {
            validate: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            message: 'Please enter a valid email address.'
        },
        phone_number: {
            validate: (value) => /^[6-9]\d{9}$/.test(value),
            message: 'Phone number must be 10 digits and start with 6-9.'
        },
        password: {
            validate: (value) => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(value),
            message: 'Password must be 8+ characters, with uppercase, lowercase, number, and special character.'
        },
        confirm_password: {
            validate: (value) => value === password.value && value.length > 0,
            message: 'Passwords do not match.'
        }
    };

    const inputs = {
        firstName,
        lastName,
        email,
        phoneNumber,
        password,
        confirmPassword
    };

    const validateField = (input) => {
        if (!input) return false;
        const rule = validationRules[input.id];
        const feedbackElement = input.nextElementSibling.nextElementSibling;
        const iconElement = input.nextElementSibling;
        const isValid = rule.validate(input.value);

        if (isValid) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
            if (feedbackElement) feedbackElement.textContent = '';
            if (iconElement) iconElement.classList.add('is-valid');
            if (iconElement) iconElement.classList.remove('is-invalid');
        } else {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            if (feedbackElement) feedbackElement.textContent = rule.message;
            if (iconElement) iconElement.classList.add('is-invalid');
            if (iconElement) iconElement.classList.remove('is-valid');
        }
        return isValid;
    };

    const checkFormValidity = () => {
        let isFormValid = true;
        for (const key in inputs) {
            if (!inputs[key].classList.contains('is-valid')) {
                isFormValid = false;
                break;
            }
        }
        registerButton.disabled = !isFormValid;
    };

    const initializeForm = () => {
        for (const key in inputs) {
            if (inputs[key].value) {
                validateField(inputs[key]);
            }
        }
        checkFormValidity();
    };

    for (const key in inputs) {
        inputs[key].addEventListener('input', () => {
            validateField(inputs[key]);
            if (key === 'password') {
                validateField(confirmPassword);
            }
            checkFormValidity();
        });
    }

    initializeForm();
});
