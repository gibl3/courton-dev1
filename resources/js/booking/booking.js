document.addEventListener("DOMContentLoaded", function () {
    // Get the summary elements
    const courtSummary = document.getElementById("court-summary");
    const courtName = document.getElementById("court-name");
    const courtType = document.getElementById("court-type");
    const pricePerHour = document.getElementById("price-per-hour");
    const dateSummary = document.getElementById("date-summary");
    const timeSummary = document.getElementById("time-summary");
    const timeDetails = document.getElementById("time-details");
    const totalPrice = document.getElementById("total-price");

    // Set minimum date to today
    const datePicker = document.getElementById("date-picker");
    const today = new Date().toISOString().split("T")[0];
    datePicker.min = today;

    let selectedCourt = null;

    // Handle court selection
    document.querySelectorAll(".select-court-btn").forEach((button) => {
        button.addEventListener("click", function () {
            selectedCourt = JSON.parse(this.dataset.court);
            courtName.textContent = selectedCourt.name;
            courtType.textContent = `${selectedCourt.type} Court`;
            pricePerHour.textContent = `₱${parseFloat(
                selectedCourt.rate_per_hour
            ).toFixed(2)}`;
            updateTotalPrice();
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
            updateTotalPrice();
        } else {
            dateSummary.querySelector("p").textContent = "Not selected";
        }
    });

    // Handle time selection
    const startTimeSelect = document.getElementById("start-time");
    const endTimeSelect = document.getElementById("end-time");

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
        const diffMs = end - start;
        const hours = diffMs / (1000 * 60 * 60);
        return Math.max(1, Math.ceil(hours)); // Ensure minimum 1 hour
    }

    function updateTotalPrice() {
        if (!selectedCourt || !startTimeSelect.value || !endTimeSelect.value) {
            totalPrice.textContent = "₱0.00";
            return;
        }

        const startTime = new Date(`2000-01-01T${startTimeSelect.value}`);
        const endTime = new Date(`2000-01-01T${endTimeSelect.value}`);
        const duration = getDuration(startTime, endTime);

        const isWeekend = new Date(datePicker.value).getDay() % 6 === 0;
        const rate = isWeekend
            ? selectedCourt.weekend_rate_per_hour
            : selectedCourt.rate_per_hour;
        const total = rate * duration;

        totalPrice.textContent = `₱${total.toFixed(2)}`;
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
