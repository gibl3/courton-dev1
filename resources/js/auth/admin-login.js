import { setupPasswordToggle, displayResponse } from "../util/utils.js";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("admin-login-form");
    const errorsDiv = document.getElementById("errors-div");

    // Setup password toggle
    setupPasswordToggle(
        document.getElementById("toggle-password"),
        document.getElementById("password")
    );

    // Handle reCAPTCHA callback
    window.onSubmit = function (token) {
        // Add the token to the form
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "g-recaptcha-response";
        input.value = token;
        form.appendChild(input);

        // Submit the form
        submitForm();
    };

    // Handle form submission
    form.addEventListener("submit", async function (e) {
        e.preventDefault();
        // Let reCAPTCHA handle the submission
        grecaptcha.execute();
    });

    // Function to submit the form
    async function submitForm() {
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

            // Success - display message and redirect
            window.location.href = data.redirect;
        } catch (error) {
            displayResponse(
                errorsDiv,
                error.errors || error.message || "Something went wrong",
                "error"
            );
            // Reset reCAPTCHA
            grecaptcha.reset();
        }
    }
});
