function initializeDoctorRegistration() {
    const form = document.querySelector('[data-doctor-registration]');

    if (!form || form.dataset.stepperInitialized === 'true') {
        return;
    }

    const steps = [...form.querySelectorAll('[data-registration-step]')];
    const indicators = [...form.querySelectorAll('[data-step-indicator]')];

    if (steps.length !== 3 || indicators.length !== steps.length) {
        return;
    }

    form.dataset.stepperInitialized = 'true';

    let activeStep = 0;

    const showStep = (stepIndex) => {
        activeStep = stepIndex;

        steps.forEach((step, index) => {
            step.hidden = index !== activeStep;
        });

        indicators.forEach((indicator, index) => {
            if (index === activeStep) {
                indicator.setAttribute('aria-current', 'step');
            } else {
                indicator.removeAttribute('aria-current');
            }
        });

        steps[activeStep].querySelector('h2')?.focus();
    };

    const focusFirstInvalidField = () => {
        const invalidField = steps[activeStep].querySelector(':invalid');

        if (invalidField) {
            invalidField.reportValidity();

            return false;
        }

        return true;
    };

    form.querySelectorAll('[data-step-next]').forEach((button) => {
        button.addEventListener('click', () => {
            if (focusFirstInvalidField()) {
                showStep(Math.min(activeStep + 1, steps.length - 1));
            }
        });
    });

    form.querySelectorAll('[data-step-previous]').forEach((button) => {
        button.addEventListener('click', () => {
            showStep(Math.max(activeStep - 1, 0));
        });
    });

    const firstError = form.querySelector('[data-error-field]');

    if (firstError) {
        const fieldName = firstError.dataset.errorField;
        const input = [...form.elements].find((element) => element.name === fieldName);
        const errorStep = fieldName.startsWith('available_hours.')
            ? 1
            : steps.findIndex((step) => input && step.contains(input));

        showStep(errorStep >= 0 ? errorStep : 0);
    } else {
        showStep(0);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDoctorRegistration, { once: true });
} else {
    initializeDoctorRegistration();
}

document.addEventListener('livewire:navigated', initializeDoctorRegistration);
