document.addEventListener("DOMContentLoaded", function () {
    // Get the summary elements
    const courtName = document.getElementById("court-name");
    const courtType = document.getElementById("court-type");
    const pricePerHour = document.getElementById("price-per-hour");
    const dateSummary = document.getElementById("date-summary");
    const timeSummary = document.getElementById("time-summary");
    const timeDetails = document.getElementById("time-details");
    const totalPrice = document.getElementById("total-price");

    // Handle time selection
    const startTimeSelect = document.getElementById("start-time");
    const endTimeSelect = document.getElementById("end-time");

    // Set minimum date to today
    const datePicker = document.getElementById("date-picker");
    const today = new Date().toISOString().split("T")[0];
    datePicker.min = today;

    let selectedCourt = null;
    let isWeekend = false;

    // Handle court selection
    document.querySelectorAll(".select-court-btn").forEach((button) => {
        button.addEventListener("click", function () {
            const courtData = JSON.parse(this.dataset.court);
            selectedCourt = courtData;
            courtName.textContent = selectedCourt.name;
            courtType.textContent = `${selectedCourt.type} Court`;
            pricePerHour.textContent = `₱${parseFloat(
                selectedCourt.rate_per_hour
            ).toFixed(2)}`;
            updateTotalPrice();

            // Update time slots based on court's operating hours and current date
            if (datePicker.value) {
                const selectedDate = new Date(datePicker.value);
                isWeekend =
                    selectedDate.getDay() === 0 || selectedDate.getDay() === 6;
            }
            updateTimeSlots(
                selectedCourt.opening_time,
                selectedCourt.closing_time
            );
        });
    });

    // Handle date selection
    datePicker.addEventListener("change", function () {
        if (this.value) {
            const selectedDate = new Date(this.value);
            const formattedDate = selectedDate.toLocaleDateString("en-US", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            });
            dateSummary.querySelector("p").textContent = formattedDate;

            // Check if it's a weekend
            isWeekend =
                selectedDate.getDay() === 0 || selectedDate.getDay() === 6;

            // Update weekend message visibility
            const weekendMessage = document.getElementById("weekend-message");
            const weekdayMessage = document.getElementById("weekday-message");
            weekendMessage.classList.toggle("hidden", !isWeekend);
            weekdayMessage.classList.toggle("hidden", isWeekend);

            if (isWeekend && selectedCourt) {
                // Use court's operating hours for weekend
                startTimeSelect.value = selectedCourt.opening_time;
                endTimeSelect.value = selectedCourt.closing_time;

                // Disable time selection for weekends
                startTimeSelect.disabled = true;
                endTimeSelect.disabled = true;

                // Update time summary
                timeSummary.querySelector("p").textContent = `${formatTime(
                    selectedCourt.opening_time
                )} - ${formatTime(selectedCourt.closing_time)}`;
                timeDetails.textContent = "Whole day booking";
                timeDetails.classList.remove("hidden");
            } else {
                // Enable time selection for weekdays
                startTimeSelect.disabled = false;
                endTimeSelect.disabled = false;

                // Reset time selection if it was previously a weekend
                if (startTimeSelect.value && endTimeSelect.value) {
                    startTimeSelect.value = "";
                    endTimeSelect.value = "";
                    timeSummary.querySelector("p").textContent = "Not selected";
                    timeDetails.classList.add("hidden");
                }

                // Update time slots if court is selected
                if (selectedCourt) {
                    updateTimeSlots(
                        selectedCourt.opening_time,
                        selectedCourt.closing_time
                    );
                }
            }

            updateTotalPrice();
        } else {
            dateSummary.querySelector("p").textContent = "Not selected";
            startTimeSelect.disabled = false;
            endTimeSelect.disabled = false;

            // Hide weekend message
            document.getElementById("weekend-message").classList.add("hidden");
            document
                .getElementById("weekday-message")
                .classList.remove("hidden");
        }
    });

    function updateTimeSlots(openingTime, closingTime) {
        console.log("Updating time slots with:", {
            openingTime,
            closingTime,
            isWeekend,
        });

        // Clear existing options
        startTimeSelect.innerHTML =
            '<option value="">Select start time</option>';
        endTimeSelect.innerHTML = '<option value="">Select end time</option>';

        try {
            if (isWeekend) {
                // For weekends, use the court's operating hours
                startTimeSelect.innerHTML = `<option value="${openingTime}">${formatTime(
                    openingTime
                )}</option>`;
                endTimeSelect.innerHTML = `<option value="${closingTime}">${formatTime(
                    closingTime
                )}</option>`;

                startTimeSelect.value = openingTime;
                endTimeSelect.value = closingTime;

                // Disable time selection for weekends
                startTimeSelect.disabled = true;
                endTimeSelect.disabled = true;

                // Update time summary
                timeSummary.querySelector("p").textContent = `${formatTime(
                    openingTime
                )} - ${formatTime(closingTime)}`;
                timeDetails.textContent = "Whole day booking";
                timeDetails.classList.remove("hidden");
            } else {
                // Parse opening and closing times for weekdays
                const [openingHour, openingMinute] = openingTime
                    .split(":")
                    .map(Number);
                const [closingHour, closingMinute] = closingTime
                    .split(":")
                    .map(Number);

                console.log("Parsed hours:", { openingHour, closingHour });

                // Create start time options
                let currentHour = openingHour;
                while (currentHour < closingHour) {
                    const timeString = `${String(currentHour).padStart(
                        2,
                        "0"
                    )}:00`;
                    const option = document.createElement("option");
                    option.value = timeString;
                    option.textContent = formatTime(timeString);
                    startTimeSelect.appendChild(option);
                    currentHour++;
                }

                // Create end time options
                currentHour = openingHour + 1;
                while (currentHour <= closingHour) {
                    const timeString = `${String(currentHour).padStart(
                        2,
                        "0"
                    )}:00`;
                    const option = document.createElement("option");
                    option.value = timeString;
                    option.textContent = formatTime(timeString);
                    endTimeSelect.appendChild(option);
                    currentHour++;
                }

                // Enable time selection for weekdays
                startTimeSelect.disabled = false;
                endTimeSelect.disabled = false;
            }
        } catch (error) {
            console.error("Error updating time slots:", error);
            // Disable the time selects if there's an error
            startTimeSelect.disabled = true;
            endTimeSelect.disabled = true;
        }

        updateTotalPrice();
    }

    // Helper function to format time
    function formatTime(timeString) {
        return new Date(`2000-01-01T${timeString}`).toLocaleTimeString(
            "en-US",
            {
                hour: "numeric",
                minute: "2-digit",
                hour12: true,
            }
        );
    }

    function updateTimeSummary() {
        if (startTimeSelect.value && endTimeSelect.value) {
            const startTime = new Date(`2000-01-01T${startTimeSelect.value}`);
            const endTime = new Date(`2000-01-01T${endTimeSelect.value}`);

            if (endTime <= startTime) {
                timeSummary.querySelector("p").textContent =
                    "Invalid time range";
                timeDetails.textContent = "";
                timeDetails.classList.add("hidden");
                totalPrice.textContent = "₱0.00";
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
            timeDetails.textContent = `${getDuration(
                startTime,
                endTime
            )} hours`;
            timeDetails.classList.remove("hidden");
        } else {
            timeSummary.querySelector("p").textContent = "Not selected";
            timeDetails.classList.add("hidden");
        }
    }

    function getDuration(start, end) {
        try {
            if (
                !(start instanceof Date) ||
                !(end instanceof Date) ||
                isNaN(start) ||
                isNaN(end)
            ) {
                throw new Error("Invalid date parameters");
            }
            const diffMs = end - start;
            if (isNaN(diffMs)) {
                throw new Error("Invalid time difference");
            }
            const hours = diffMs / (1000 * 60 * 60);
            return Math.max(1, Math.ceil(hours)); // Ensure minimum 1 hour
        } catch (error) {
            console.error("Error calculating duration:", error);
            return 1; // Return minimum duration in case of error
        }
    }

    function updateTotalPrice() {
        if (!selectedCourt || !startTimeSelect.value || !endTimeSelect.value) {
            totalPrice.textContent = "₱0.00";
            return;
        }

        const startTime = new Date(`2000-01-01T${startTimeSelect.value}`);
        const endTime = new Date(`2000-01-01T${endTimeSelect.value}`);
        const duration = getDuration(startTime, endTime);

        // Check if it's weekend (Saturday or Sunday)
        const selectedDate = new Date(datePicker.value);
        const isWeekend =
            selectedDate.getDay() === 0 || selectedDate.getDay() === 6;

        let total;
        if (isWeekend) {
            // For weekends, use the weekend rate for the whole day
            total = parseFloat(selectedCourt.weekend_rate_per_hour);
        } else {
            // For weekdays, calculate based on duration
            total = parseFloat(selectedCourt.rate_per_hour) * duration;
        }

        totalPrice.textContent = `₱${Number(total).toFixed(2)}`;
    }

    startTimeSelect.addEventListener("change", function () {
        updateTimeSummary();
        updateTotalPrice();
    });

    endTimeSelect.addEventListener("change", function () {
        updateTimeSummary();
        updateTotalPrice();
    });

    // Handle booking confirmation
    document
        .querySelector("#confirm-button")
        .addEventListener("click", function () {
            const startTime = new Date(`2000-01-01T${startTimeSelect.value}`);
            const endTime = new Date(`2000-01-01T${endTimeSelect.value}`);

            if (endTime <= startTime) {
                alert("End time must be later than start time");
                return;
            }

            if (!selectedCourt) {
                alert("Please select a court first");
                return;
            }

            if (!datePicker.value) {
                alert("Please select a date");
                return;
            }

            if (!startTimeSelect.value || !endTimeSelect.value) {
                alert("Please select both start and end times");
                return;
            }

            // Prepare booking data
            const bookingData = {
                court_id: selectedCourt.id,
                date: datePicker.value,
                start_time: startTimeSelect.value,
                end_time: endTimeSelect.value,
                is_weekend: new Date(datePicker.value).getDay() % 6 === 0,
                rate: selectedCourt.rate_per_hour,
                weekend_rate: selectedCourt.weekend_rate_per_hour,
            };

            // Send booking data to server
            fetch("/player/book/confirm", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(bookingData),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.message) {
                        alert(data.message);
                        if (data.booking) {
                            window.location.href = "/player/my-bookings";
                        }
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("An error occurred while creating your booking");
                });
        });
});
