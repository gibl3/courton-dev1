// Debounce utility
function debounce(fn, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), delay);
    };
}

// Show error message in search results
function showSearchError(message) {
    const userSearchResults = document.getElementById("user-search-results");
    userSearchResults.innerHTML = `<div class="p-2 text-sm text-red-500">${message}</div>`;
    userSearchResults.classList.remove("hidden");
}

// Show loading state in search results
function showSearchLoading() {
    const userSearchResults = document.getElementById("user-search-results");
    userSearchResults.innerHTML =
        '<div class="p-2 text-sm text-neutral-500">Searching...</div>';
    userSearchResults.classList.remove("hidden");
}

// Show no results message
function showNoResults() {
    const userSearchResults = document.getElementById("user-search-results");
    userSearchResults.innerHTML =
        '<div class="p-2 text-sm text-neutral-500">No players found</div>';
    userSearchResults.classList.remove("hidden");
}

// Clear search results
function clearSearchResults() {
    const userSearchResults = document.getElementById("user-search-results");
    userSearchResults.innerHTML = "";
    userSearchResults.classList.add("hidden");
}

// Handle user selection
function handleUserSelection(userId, userName, userEmail) {
    const userIdInput = document.getElementById("user-id");
    const userSearchInput = document.getElementById("user-search");
    const playerName = document.getElementById("player-name");
    const playerEmail = document.getElementById("player-email");

    userIdInput.value = userId;
    userSearchInput.value = userName;
    playerName.textContent = userName;
    playerEmail.textContent = userEmail;
    clearSearchResults();
}

// Update time slots based on selected date
function updateTimeSlots(selectedDate) {
    const startTimeSelect = document.getElementById("start-time");
    const endTimeSelect = document.getElementById("end-time");
    const timeSlotInfo = document.getElementById("time-slot-info");

    // Clear existing options
    startTimeSelect.innerHTML = '<option value="">Select start time</option>';
    endTimeSelect.innerHTML = '<option value="">Select end time</option>';

    const date = new Date(selectedDate);
    const isWeekend = date.getDay() === 0 || date.getDay() === 6;

    if (isWeekend) {
        // Weekend booking - whole day only
        startTimeSelect.innerHTML += '<option value="06:00">6:00 AM</option>';
        endTimeSelect.innerHTML += '<option value="22:00">10:00 PM</option>';
        timeSlotInfo.textContent = "Weekend bookings are for the whole day";

        // Auto-select the only available option
        startTimeSelect.value = "06:00";
        endTimeSelect.value = "22:00";
    } else {
        // Weekday booking - hourly slots
        const start = new Date(selectedDate + "T08:00:00");
        const end = new Date(selectedDate + "T21:00:00");
        const current = new Date(start);

        while (current < end) {
            const timeValue = current.toTimeString().slice(0, 5);
            const timeDisplay = current.toLocaleTimeString("en-US", {
                hour: "numeric",
                minute: "2-digit",
                hour12: true,
            });

            startTimeSelect.innerHTML += `<option value="${timeValue}">${timeDisplay}</option>`;
            current.setHours(current.getHours() + 1);
        }

        // Reset end time options
        current.setHours(start.getHours() + 1);
        while (current <= end) {
            const timeValue = current.toTimeString().slice(0, 5);
            const timeDisplay = current.toLocaleTimeString("en-US", {
                hour: "numeric",
                minute: "2-digit",
                hour12: true,
            });

            endTimeSelect.innerHTML += `<option value="${timeValue}">${timeDisplay}</option>`;
            current.setHours(current.getHours() + 1);
        }

        timeSlotInfo.textContent = "Select your preferred start and end times";
    }
}

// Update booking summary
function updateBookingSummary() {
    const courtSelect = document.getElementById("court-id");
    const selectedOption = courtSelect.options[courtSelect.selectedIndex];
    const courtData = selectedOption.dataset.court
        ? JSON.parse(selectedOption.dataset.court)
        : null;
    const dateInput = document.getElementById("booking-date");
    const startTimeSelect = document.getElementById("start-time");
    const endTimeSelect = document.getElementById("end-time");

    // Update court summary
    const courtName = document.getElementById("court-name");
    const courtType = document.getElementById("court-type");
    const pricePerHour = document.getElementById("price-per-hour");

    if (courtData) {
        courtName.textContent = courtData.name;
        courtType.textContent = `${courtData.type} Court`;
        pricePerHour.textContent = `₱${parseFloat(
            courtData.rate_per_hour
        ).toFixed(2)}`;
    } else {
        courtName.textContent = "No court selected";
        courtType.textContent = "Select a court to continue";
        pricePerHour.textContent = "₱0.00";
    }

    // Update date summary
    const dateSummary = document.getElementById("date-summary");
    if (dateInput.value) {
        const selectedDate = new Date(dateInput.value);
        const formattedDate = selectedDate.toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        });
        dateSummary.querySelector("p").textContent = formattedDate;
    } else {
        dateSummary.querySelector("p").textContent = "Not selected";
    }

    // Update time summary
    const timeSummary = document.getElementById("time-summary");
    const timeDetails = document.getElementById("time-details");

    if (startTimeSelect.value && endTimeSelect.value) {
        const startTime = new Date(`2000-01-01T${startTimeSelect.value}`);
        const endTime = new Date(`2000-01-01T${endTimeSelect.value}`);

        if (endTime <= startTime) {
            timeSummary.querySelector("p").textContent = "Invalid time range";
            timeDetails.textContent = "";
            timeDetails.classList.add("hidden");
            return;
        }

        const formattedStartTime = startTime.toLocaleTimeString("en-US", {
            hour: "numeric",
            minute: "2-digit",
            hour12: true,
        });

        const formattedEndTime = endTime.toLocaleTimeString("en-US", {
            hour: "numeric",
            minute: "2-digit",
            hour12: true,
        });

        timeSummary.querySelector(
            "p"
        ).textContent = `${formattedStartTime} - ${formattedEndTime}`;
        timeDetails.textContent = `${getDuration(startTime, endTime)} hours`;
        timeDetails.classList.remove("hidden");
    } else {
        timeSummary.querySelector("p").textContent = "Not selected";
        timeDetails.classList.add("hidden");
    }

    // Update total price
    updateTotalPrice();
}

function getDuration(start, end) {
    const diffMs = end - start;
    const hours = diffMs / (1000 * 60 * 60);
    return Math.max(1, Math.ceil(hours)); // Ensure minimum 1 hour
}

function updateTotalPrice() {
    const courtSelect = document.getElementById("court-id");
    const selectedOption = courtSelect.options[courtSelect.selectedIndex];
    const courtData = selectedOption.dataset.court
        ? JSON.parse(selectedOption.dataset.court)
        : null;
    const startTimeSelect = document.getElementById("start-time");
    const endTimeSelect = document.getElementById("end-time");
    const dateInput = document.getElementById("booking-date");
    const totalPrice = document.getElementById("total-price");

    if (
        !courtData ||
        !startTimeSelect.value ||
        !endTimeSelect.value ||
        !dateInput.value
    ) {
        totalPrice.textContent = "₱0.00";
        return;
    }

    const startTime = new Date(`2000-01-01T${startTimeSelect.value}`);
    const endTime = new Date(`2000-01-01T${endTimeSelect.value}`);
    const duration = getDuration(startTime, endTime);

    // Check if it's weekend
    const selectedDate = new Date(dateInput.value);
    const isWeekend =
        selectedDate.getDay() === 0 || selectedDate.getDay() === 6;

    let total;
    if (isWeekend) {
        // For weekends, use the weekend rate for the whole day
        total = parseFloat(courtData.weekend_rate_per_hour);
    } else {
        // For weekdays, calculate based on duration
        total = parseFloat(courtData.rate_per_hour) * duration;
    }

    totalPrice.textContent = `₱${Number(total).toFixed(2)}`;
}

// Validate booking form
function validateBookingForm() {
    const userId = document.getElementById("user-id").value;
    const courtId = document.getElementById("court-id").value;
    const bookingDate = document.getElementById("booking-date").value;
    const startTime = document.getElementById("start-time").value;
    const endTime = document.getElementById("end-time").value;

    if (!userId) {
        alert("Please select a player");
        return false;
    }

    if (!courtId) {
        alert("Please select a court");
        return false;
    }

    if (!bookingDate) {
        alert("Please select a date");
        return false;
    }

    if (!startTime || !endTime) {
        alert("Please select both start and end times");
        return false;
    }

    if (startTime >= endTime) {
        alert("End time must be after start time");
        return false;
    }

    return true;
}

document.addEventListener("DOMContentLoaded", function () {
    // Player search
    const userSearchInput = document.getElementById("user-search");
    const userSearchResults = document.getElementById("user-search-results");
    const userIdInput = document.getElementById("user-id");
    const template = document.getElementById("user-result-template");
    const bookingDate = document.getElementById("booking-date");
    const courtSelect = document.getElementById("court-id");

    if (!userSearchInput || !userSearchResults || !userIdInput || !template) {
        console.error("Required elements not found");
        return;
    }

    // Initialize time slots based on today's date
    if (bookingDate.value) {
        updateTimeSlots(bookingDate.value);
    }

    // Update time slots when date changes
    bookingDate.addEventListener("change", function () {
        if (this.value) {
            updateTimeSlots(this.value);
            updateBookingSummary();
        }
    });

    // Update summary when court changes
    courtSelect.addEventListener("change", updateBookingSummary);

    userSearchInput.addEventListener(
        "input",
        debounce(function () {
            const query = this.value.trim();

            if (query.length < 2) {
                clearSearchResults();
                return;
            }

            showSearchLoading();

            const searchUrl = `/admin/players/search?q=${encodeURIComponent(
                query
            )}`;

            fetch(searchUrl)
                .then((res) => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then((data) => {
                    if (!Array.isArray(data)) {
                        throw new Error("Invalid response format");
                    }

                    if (data.length === 0) {
                        showNoResults();
                        return;
                    }

                    userSearchResults.innerHTML = "";
                    data.forEach((user) => {
                        const clone = template.content.cloneNode(true);
                        const resultDiv = clone.querySelector("div");
                        resultDiv.dataset.id = user.id;

                        const nameDiv = clone.querySelector(".user-name");
                        const emailDiv = clone.querySelector(".user-email");

                        nameDiv.textContent = `${user.first_name} ${user.last_name}`;
                        emailDiv.textContent = user.email;

                        userSearchResults.appendChild(clone);
                    });
                    userSearchResults.classList.remove("hidden");
                })
                .catch((error) => {
                    console.error("Search error:", error);
                    showSearchError(
                        "Error searching players. Please try again."
                    );
                });
        }, 300)
    );

    userSearchResults.addEventListener("click", function (e) {
        const target = e.target.closest("[data-id]");
        if (target) {
            const userId = target.dataset.id;
            const userName = target.querySelector(".user-name").textContent;
            const userEmail = target.querySelector(".user-email").textContent;
            handleUserSelection(userId, userName, userEmail);
        }
    });

    // Close search results when clicking outside
    document.addEventListener("click", function (e) {
        if (
            !userSearchInput.contains(e.target) &&
            !userSearchResults.contains(e.target)
        ) {
            clearSearchResults();
        }
    });

    // Handle keyboard navigation
    userSearchInput.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            clearSearchResults();
        }
    });

    // Start/End time validation
    const startTimeSelect = document.getElementById("start-time");
    const endTimeSelect = document.getElementById("end-time");

    if (!startTimeSelect || !endTimeSelect) {
        console.error("Time input elements not found");
        return;
    }

    function validateTimeInputs() {
        if (startTimeSelect.value && endTimeSelect.value) {
            if (startTimeSelect.value >= endTimeSelect.value) {
                endTimeSelect.setCustomValidity(
                    "End time must be after start time"
                );
                startTimeSelect.setCustomValidity(
                    "Start time must be before end time"
                );
            } else {
                endTimeSelect.setCustomValidity("");
                startTimeSelect.setCustomValidity("");
            }
        } else {
            endTimeSelect.setCustomValidity("");
            startTimeSelect.setCustomValidity("");
        }
        updateBookingSummary();
    }

    startTimeSelect.addEventListener("change", validateTimeInputs);
    endTimeSelect.addEventListener("change", validateTimeInputs);

    // Handle form submission
    const bookingForm = document.getElementById("booking-form");
    const submitButton = document.getElementById("submit-booking");

    if (bookingForm && submitButton) {
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            if (!validateBookingForm()) {
                return;
            }

            // Disable submit button to prevent double submission
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="material-symbols-rounded animate-spin">sync</span>
                Creating Booking...
            `;

            // Submit the form
            bookingForm.submit();
        });
    }
});
