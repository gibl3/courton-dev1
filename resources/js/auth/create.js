import {
    setupPasswordToggle,
    displayValidationErrors,
    checkPassRequirements,
    setFormState,
} from "../util/utils.js";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("signup-form");
    const successPopover = document.getElementById("success-popover");
    const popoverMessage = document.getElementById("popover-message");
    // const errorsDiv = document.getElementById("errors-div");

    const requirements = {
        length: document.getElementById("length-check"),
        uppercase: document.getElementById("uppercase-check"),
        lowercase: document.getElementById("lowercase-check"),
        number: document.getElementById("number-check"),
        special: document.getElementById("special-check"),
    };

    const passwordInput = document.querySelector("[name='password']");

    // Check requirements on initial load
    checkPassRequirements(passwordInput.value, requirements);

    // Check requirements whenever password changes
    passwordInput.addEventListener("input", (e) => {
        checkPassRequirements(e.target.value, requirements);
    });

    setupPasswordToggle(
        document.querySelector("#toggle-password-1"),
        document.querySelector("[name='password']")
    );
    setupPasswordToggle(
        document.querySelector("#toggle-password-2"),
        document.querySelector("[name='password_confirmation']")
    );

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            // Log the response data for debugging

            // Show success popover
            popoverMessage.textContent = data.message;
            successPopover.showPopover();
            setFormState(form, true);

            console.warn(data.redirect);

            setTimeout(() => {
                window.location.href = data.redirect;
            }, 3000);
        } catch (error) {
            displayValidationErrors(error.errors);
        }
    });
});
