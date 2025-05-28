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
