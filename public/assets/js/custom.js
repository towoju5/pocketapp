// window.onload = function () {
//     // Connect to the trade.created channel
//     var tradeChannel = Echo.channel("trade.created");
    // var tradeUpdateChannel = Echo.channel("trade.updated");

    // if (tradeChannel) {
    //     toastr.success("Trade update connected");
    //     console.log("Echo connected successfully");
    // }

//     // Listen for the 'trade.created' event
//     tradeChannel.listen(".trade.created", function (data) {
//         if (data && data.id) {
//             console.log("Trade Created:", data);
//             toastr.success(`Trade event received: ID: ${data.id}`);
//         } else {
//             console.error("Invalid trade.created event data:", data);
//         }
//     });

//     // Listen for the 'trade-updated' event
    // tradeUpdateChannel.listen(".trade-updated", function (data) {
    //     if (data && data.id) {
    //         console.log("Trade Updated:", data);
    //         toastr.success(`Update on trade ${data.id} received`);
    //     } else {
    //         console.error("Invalid trade-updated event data:", data);
    //     }
    // });
// };

function initCountdowns() {
    const countdownElements = document.querySelectorAll('.signal-time');
    
    countdownElements.forEach(element => {
        let timeString = element.textContent.trim();
        let timerParts = timeString.split(':').map(Number);
        let totalTime = timerParts[0] * 3600 + timerParts[1] * 60 + timerParts[2];

        const updateCountdown = () => {
            totalTime--;
            
            if (totalTime < 0) {
                clearInterval(timer);
                element.textContent = '00:00:00';
                return;
            }

            const newHours = Math.floor(totalTime / 3600);
            const newMinutes = Math.floor((totalTime % 3600) / 60);
            const newSeconds = totalTime % 60;

            element.textContent = [
                String(newHours).padStart(2, '0'),
                String(newMinutes).padStart(2, '0'),
                String(newSeconds).padStart(2, '0')
            ].join(':');
        };

        const timer = setInterval(updateCountdown, 1000);
    });
}


$(document).ready(function () {
    function activateTab(activeTab, inactiveTab, activeContent, inactiveContent) {
        // Show the active content, hide the inactive one
        $(activeContent).removeClass("hidden");
        $(inactiveContent).addClass("hidden");

        // Update tab styles
        $(activeTab).addClass("active-tab text-gray-200 bg-[#1e2131]")
                    .removeClass("text-gray-500 bg-[#272b3c]");
        $(inactiveTab).removeClass("active-tab text-gray-200 bg-[#1e2131]")
                      .addClass("text-gray-500 bg-[#272b3c]");

        // Ensure blue bottom border is only on the active tab
        $(activeTab).find(".tab-indicator").removeClass("hidden");
        $(inactiveTab).find(".tab-indicator").addClass("hidden");
    }

    // Click event for Opened tab
    $("#openTab").click(function () {
        activateTab("#openTab", "#closedTab", "#openTrades", "#closedTrades");
    });

    // Click event for Closed tab
    $("#closedTab").click(function () {
        activateTab("#closedTab", "#openTab", "#closedTrades", "#openTrades");
    });

    // Set the default active tab on page load
    activateTab("#openTab", "#closedTab", "#openTrades", "#closedTrades");
});



// handle trade form submission.
document.addEventListener("DOMContentLoaded", function () {
    const tradeForm = document.getElementById("tradeForm");
    const tradeButtons = document.querySelectorAll(".cta-button");

    // Handle button clicks to set trade direction and submit form
    tradeButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const tradeDirection = this.getAttribute("data-value"); // 'up' or 'down'

            if (!tradeDirection) {
                toastr.error("Trade direction is missing.");
                return;
            }

            // Append trade direction to the form
            const formData = new FormData(tradeForm);
            formData.append("direction", tradeDirection);

            // Disable buttons to prevent multiple submissions
            tradeButtons.forEach((btn) => (btn.disabled = true));

            fetch(tradeForm.getAttribute("action"), {
                method: "POST",
                body: new URLSearchParams([...formData]), // Convert FormData to URL-encoded format
            })
                .then((response) => response.json()) // Convert response to JSON
                .then((response) => {
                    if (response.status) {
                        toastr.success(response.message);

                        // Display trade data
                        const trade = response.trade;
                        const tradeHtml = response.html;
                        $("#openTradeList").append(tradeHtml);
                        document
                            .getElementById("tradesList")
                            .insertAdjacentHTML("afterbegin", tradeHtml);

                        // Start countdown
                        let timeLeft = trade.trade_close_time;
                        const countdownInterval = setInterval(() => {
                            if (timeLeft <= 0) {
                                clearInterval(countdownInterval);
                                document
                                    .querySelectorAll(`.countdown-${trade.id}`)
                                    .forEach(
                                        (el) => (el.textContent = "Completed")
                                    );
                                return;
                            }

                            document
                                .querySelectorAll(`.countdown-${trade.id}`)
                                .forEach(
                                    (el) =>
                                        (el.textContent = `${timeLeft} seconds`)
                                );
                            timeLeft--;
                        }, 1000);

                        // Reset form after submission
                        tradeForm.reset();
                    } else {
                        toastr.error(response.message);
                    }
                })
                .catch((error) => {
                    // toastr.error("An error occurred while placing the trade");
                    console.error(error);
                })
                .finally(() => {
                    // Re-enable buttons
                    tradeButtons.forEach((btn) => (btn.disabled = false));
                });
        });
    });
});

// stock dropdown
document.addEventListener("DOMContentLoaded", function () {
    const assetBtn = document.getElementById("assetBtn");
    const assetDropDown = document.getElementById("assetDropDown");

    if (assetBtn && assetDropDown) {
        assetBtn.addEventListener("click", function () {
            assetDropDown.classList.toggle("hidden");
        });
    } else {
        // console.error("Element(s) not found: assetBtn or assetDropDown.");
    }
});
