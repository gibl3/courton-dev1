import { setupPasswordToggle, displayResponse } from "../util/utils.js";

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("signup-form");
    const errorsDiv = document.getElementById("errors-div");

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

            // Success - display message and redirect after 3 seconds
            displayResponse(errorsDiv, data.message, "success");
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 3000);
        } catch (error) {
            displayResponse(
                errorsDiv,
                error.errors || error.message || "Something went wrong",
                "error"
            );
        }
    });
});
