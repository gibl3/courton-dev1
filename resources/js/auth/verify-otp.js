document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("verify-otp-form");
    const errorsDiv = document.getElementById("errors-div");
    const resendButton = document.getElementById("resend-code");
    const resendTimer = document.getElementById("resend-timer");
    const inputs = form.querySelectorAll('input[type="text"]');
    let countdown = 60;
    let timerInterval;

    // Handle input focus and auto-advance
    inputs.forEach((input, index) => {
        input.addEventListener("input", function () {
            if (this.value.length === 1) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
        });

        input.addEventListener("keydown", function (e) {
            if (e.key === "Backspace" && !this.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // Handle form submission
    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        try {
            // Combine OTP inputs
            const otp = Array.from(inputs)
                .map((input) => input.value)
                .join("");

            const response = await fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ otp }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            // Success - redirect
            window.location.href = data.redirect;
        } catch (error) {
            displayResponse(
                errorsDiv,
                error.errors || error.message || "Something went wrong",
                "error"
            );
            // Clear inputs on error
            inputs.forEach((input) => (input.value = ""));
            inputs[0].focus();
        }
    });

    // Handle resend OTP
    // resendButton.addEventListener("click", async function () {
    //     try {
    //         const response = await fetch(route("auth.resendOTP"), {
    //             method: "POST",
    //             headers: {
    //                 "X-CSRF-TOKEN": document.querySelector(
    //                     'meta[name="csrf-token"]'
    //                 ).content,
    //                 Accept: "application/json",
    //             },
    //         });

    //         const data = await response.json();

    //         if (!response.ok) {
    //             throw data;
    //         }

    //         // Start countdown
    //         startCountdown();
    //         displayResponse(errorsDiv, "OTP resent successfully", "success");
    //     } catch (error) {
    //         displayResponse(
    //             errorsDiv,
    //             error.errors || error.message || "Failed to resend OTP",
    //             "error"
    //         );
    //     }
    // });

    // Countdown timer function
    function startCountdown() {
        resendButton.disabled = true;
        countdown = 60;

        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (countdown > 0) {
                resendTimer.textContent = `Resend available in ${countdown} seconds`;
                countdown--;
            } else {
                clearInterval(timerInterval);
                resendButton.disabled = false;
                resendTimer.textContent = "";
            }
        }, 1000);
    }

    // Start initial countdown
    startCountdown();
});
