import {
    displayResponse,
    checkPassRequirements,
    setupPasswordToggle,
    setFormState,
} from "../util/utils.js";

document.addEventListener("DOMContentLoaded", function () {
    // Profile Form
    const profileForm = document.querySelector("#profile-form");
    const profileResponse = document.querySelector("#profile-response");

    // Profile form submission
    profileForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(profileForm);

        try {
            const response = await fetch(profileForm.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(Object.fromEntries(formData)),
            });

            if (response.status === 304) {
                displayResponse(
                    profileResponse,
                    "No changes were made",
                    "info"
                );
                return;
            }

            const data = await response.json();

            if (!response.ok) {
                throw data.errors || "Failed to update profile";
            }

            setFormState(profileForm, true);

            displayResponse(profileResponse, data.message, "success");

            setTimeout(() => {
                setFormState(profileForm, false);
            }, 2000);
        } catch (error) {
            displayResponse(profileResponse, error, "error");
        }
    });

    // Password form elements
    const passwordForm = document.querySelector("#password-form");
    const passwordResponse = document.querySelector("#password-response");
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordChecks = {
        length: document.querySelector("#length-check"),
        uppercase: document.querySelector("#uppercase-check"),
        lowercase: document.querySelector("#lowercase-check"),
        number: document.querySelector("#number-check"),
        special: document.querySelector("#special-check"),
    };

    passwordInput.addEventListener("input", (e) => {
        checkPassRequirements(e.target.value, passwordChecks);
    });

    setupPasswordToggle(
        document.querySelector("#toggle-password-1"),
        document.querySelector("[name='password']")
    );
    setupPasswordToggle(
        document.querySelector("#toggle-password-2"),
        document.querySelector("[name='password_confirmation']")
    );

    // Password form submission
    passwordForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(passwordForm);

        try {
            const response = await fetch(passwordForm.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(Object.fromEntries(formData)),
            });

            if (response.status === 304) {
                displayResponse(
                    passwordResponse,
                    "No changes were made",
                    "info"
                );
                return;
            }

            const data = await response.json();

            if (!response.ok) {
                throw data.errors || "Failed to update password";
            }

            setFormState(passwordForm, true);
            displayResponse(passwordResponse, data.message, "success");

            setTimeout(() => {
                setFormState(passwordForm, false);
            }, 2000);
        } catch (error) {
            displayResponse(passwordResponse, error, "error");
        }
    });

    setupPasswordToggle(
        document.querySelector("#toggle-password-3"),
        document.querySelector("#confirm-delete-password")
    );

    const deleteForm = document.querySelector("#delete-form");
    const deleteResponse = document.querySelector("#delete-response");

    deleteForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        try {
            const formData = new FormData(deleteForm);
            const response = await fetch(deleteForm.action, {
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
                throw data.message || "Failed to delete account";
            }

            displayResponse(deleteResponse, data.message, "success");
            window.location.href = data.redirect;
        } catch (error) {
            displayResponse(deleteResponse, error, "error");
        }
    });
});
