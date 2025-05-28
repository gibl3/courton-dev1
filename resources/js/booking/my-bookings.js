document.addEventListener("DOMContentLoaded", function () {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const bookingRows = document.querySelectorAll(".booking-row");
    const today = new Date().toISOString().split("T")[0];

    // Handle filter button clicks
    filterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            // Update button styles
            filterButtons.forEach((btn) => {
                btn.classList.remove("btn-filled");
                btn.classList.add("btn-filled-tonal");
            });
            this.classList.remove("btn-filled-tonal");
            this.classList.add("btn-filled");

            // Get the filter value
            const filter = this.dataset.filter;

            // Filter the bookings
            bookingRows.forEach((row) => {
                const status = row.dataset.status;
                const date = row.dataset.date;

                let show = false;
                switch (filter) {
                    case "all":
                        show = true;
                        break;
                    case "upcoming":
                        show = date >= today && status === "confirmed";
                        break;
                    case "pending":
                        show = status === "pending";
                        break;
                    case "past":
                        show = date < today || status === "completed";
                        break;
                    case "cancelled":
                        show = status === "cancelled";
                        break;
                }

                row.style.display = show ? "" : "none";
            });
        });
    });

    // Handle view booking button clicks
    document.querySelectorAll(".view-booking").forEach((button) => {
        button.addEventListener("click", function () {
            const bookingId = this.dataset.bookingId;
            // TODO: Implement view booking functionality
            console.log("View booking:", bookingId);
        });
    });

    // Handle cancel booking button clicks
    document.querySelectorAll(".cancel-booking").forEach((button) => {
        button.addEventListener("click", function () {
            const bookingId = this.dataset.bookingId;
            if (confirm("Are you sure you want to cancel this booking?")) {
                fetch(`/player/bookings/${bookingId}/cancel`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || "Failed to cancel booking");
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("An error occurred while cancelling the booking");
                    });
            }
        });
    });
});
