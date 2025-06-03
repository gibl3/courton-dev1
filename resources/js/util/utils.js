/**
 * Sets up a password toggle button for a password input.
 * @param {HTMLElement} toggleButton - The toggle button element.
 * @param {HTMLElement} passwordInput - The password input element.
 */
export function setupPasswordToggle(toggleButton, passwordInput) {
    if (
        !(toggleButton instanceof HTMLElement) ||
        !(passwordInput instanceof HTMLElement)
    ) {
        throw new Error(
            "setupPasswordToggle expects two HTMLElement arguments."
        );
    }

    toggleButton.addEventListener("click", (e) => {
        e.preventDefault();
        const icon = toggleButton.querySelector(".material-symbols-rounded");
        if (!icon) return;

        const type =
            passwordInput.getAttribute("type") === "password"
                ? "text"
                : "password";
        passwordInput.setAttribute("type", type);
        icon.textContent =
            type === "password" ? "visibility" : "visibility_off";
    });
}

export function displayResponse(element, message, type = "error") {
    // Define all possible response classes
    const responseClasses = {
        success: ["bg-green-50", "border-green-200", "text-green-600"],
        info: ["bg-blue-50", "border-blue-200", "text-blue-600"],
        error: ["bg-red-50", "border-red-200", "text-red-600"],
    };

    // Remove only the classes that are present
    Object.values(responseClasses)
        .flat()
        .forEach((className) => {
            if (element.classList.contains(className)) {
                element.classList.remove(className);
            }
        });

    element.classList.remove("hidden");
    element.hidden = false;
    element.innerHTML = "";

    // Add new classes based on type
    element.classList.add(...responseClasses[type]);

    // Handle both single messages and validation errors
    const messages =
        typeof message === "object"
            ? Object.values(message).map((m) => m[0])
            : [message];
    messages.forEach((text) => {
        const p = document.createElement("p");
        p.textContent = text;
        element.appendChild(p);
    });

    // Hide after 3 seconds
    setTimeout(() => {
        element.classList.add("hidden");
        element.hidden = true;
        element.innerHTML = "";
    }, 3000);
}

export function setFormState(form, disabled) {
    const formElements = form.querySelectorAll(
        "input, button, select, textarea, checkbox"
    );

    formElements.forEach((element) => {
        element.disabled = disabled;
        if (disabled) {
            element.classList.add("cursor-not-allowed");
            // element.disabled = false;
        } else {
            element.classList.remove("cursor-not-allowed");
            // element.disabled = true;
        }
    });
}

export function checkPassRequirements(password, requirements) {
    // Array of requirements: [key, test function]
    const checks = [
        ["length", (pw) => pw.length >= 8],
        ["uppercase", (pw) => /[A-Z]/.test(pw)],
        ["lowercase", (pw) => /[a-z]/.test(pw)],
        ["number", (pw) => /[0-9]/.test(pw)],
        ["special", (pw) => /[!@#$%^&*(),.?":{}|<>]/.test(pw)],
    ];

    checks.forEach(([key, test]) => {
        const el = requirements[key];
        if (!el) return;
        el.classList.remove("text-green-600", "text-red-500");
        el.classList.add(test(password) ? "text-green-600" : "text-red-500");
    });
}

export function displayValidationErrors(errors, decayTime = 5) {
    try {
        for (const [field, messages] of Object.entries(errors)) {
            const input = document.querySelector(`[id="${field}"]`);
            if (!input) continue;

            // Skip non-text input types and specific elements like the "terms" checkbox
            const skipTypes = [
                "checkbox",
                "radio",
                "file",
                "hidden",
                "submit",
                "button",
            ];
            if (skipTypes.includes(input.type) || field === "terms") continue;

            input.classList.add("ring", "ring-red-500", "rounded-lg");

            const error = document.createElement("p");
            error.className = "text-red-500 text-sm error-message";
            error.textContent = messages[0];
            input.insertAdjacentElement("afterend", error);

            setTimeout(() => {
                input.classList.remove("ring", "ring-red-500", "rounded-lg");
                error.remove();
            }, decayTime * 1000);
        }
    } catch (err) {
        alert(`An error occurred while registering the student. ${err}`);
    }
}

export function getAvatarUrl(avatar, name) {
    if (avatar) {
        return avatar;
    }
    // Use DiceBear's initials avatar as fallback
    const encodedName = encodeURIComponent(name || "User");
    return `https://api.dicebear.com/7.x/initials/svg?seed=${encodedName}&backgroundColor=rose-100&textColor=rose-600`;
}
