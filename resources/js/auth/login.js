import { setupPasswordToggle, displayResponse } from "../util/utils.js";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("login-form");
    const errorsDiv = document.getElementById("errors-div");

    // Setup password toggle if needed
    // setupPasswordToggle(document.getElementById("password"));

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

            // Success - display message and redirect
            window.location.href = data.redirect;
        } catch (error) {
            displayResponse(
                errorsDiv,
                error.errors || error.message || "Something went wrong",
                "error"
            );
        }
    });
});
