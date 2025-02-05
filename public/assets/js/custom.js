// window.onload = function () {
//     // Connect to the trade.created channel
//     var tradeChannel = Echo.channel("trade.created");
//     var tradeUpdateChannel = Echo.channel("trade.updated");

//     if (tradeChannel) {
//         toastr.success("Trade update connected");
//         console.log("Echo connected successfully");
//     }

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
//     tradeUpdateChannel.listen(".trade-updated", function (data) {
//         if (data && data.id) {
//             console.log("Trade Updated:", data);
//             toastr.success(`Update on trade ${data.id} received`);
//         } else {
//             console.error("Invalid trade-updated event data:", data);
//         }
//     });
// };

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
                    toastr.error("An error occurred while placing the trade");
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
document.getElementById("assetBtn").addEventListener("click", function () {
    document.getElementById("assetDropDown").classList.toggle("hidden");
});

// Stock Data
const stocks = [
    { name: "Apple OTC", payout: "+92%", category: "tech" },
    { name: "American Express OTC", payout: "+92%", category: "finance" },
    { name: "Boeing Company OTC", payout: "+92%", category: "tech" },
    { name: "Facebook INC OTC", payout: "+92%", category: "tech" },
    { name: "Intel OTC", payout: "+92%", category: "tech" },
    { name: "Microsoft OTC", payout: "+92%", category: "tech" },
    { name: "Tesla OTC", payout: "+92%", category: "tech" },
    { name: "Amazon OTC", payout: "+92%", category: "retail" },
    { name: "Netflix OTC", payout: "+88%", category: "retail" },
    { name: "Twitter OTC", payout: "+85%", category: "tech" },
    { name: "Johnson & Johnson OTC", payout: "+83%", category: "finance" },
];

// Load stocks
function loadStocks(filter = "all") {
    const stockList = document.getElementById("stockList");
    stockList.innerHTML = "";
    stocks.forEach((stock) => {
        if (filter === "all" || stock.category === filter) {
            const li = document.createElement("li");
            li.className =
                "p-2 flex justify-between hover:bg-gray-700 cursor-pointer";
            li.innerHTML = `<span>${stock.name}</span> <span class="text-green-400">${stock.payout}</span>`;
            li.onclick = function () {
                document.getElementById("selectedAsset").innerText = stock.name;
                document
                    .getElementById("assetDropDown")
                    .classList.add("hidden");
            };
            stockList.appendChild(li);
        }
    });
}

loadStocks();

// Filter Stocks
function filterStocks(category) {
    loadStocks(category);
}

// Search Functionality
document.getElementById("searchBar").addEventListener("keyup", function () {
    const searchValue = this.value.toLowerCase();
    const stockItems = document.querySelectorAll("#stockList li");
    stockItems.forEach((item) => {
        item.style.display = item.textContent
            .toLowerCase()
            .includes(searchValue)
            ? "flex"
            : "none";
    });
});

// Close Dropdown on Outside Click
document.addEventListener("click", function (event) {
    if (
        !document.getElementById("assetBtn").contains(event.target) &&
        !document.getElementById("assetDropDown").contains(event.target)
    ) {
        document.getElementById("assetDropDown").classList.add("hidden");
    }
});

// stock dropdown
document.getElementById("chartTpyeBtn").addEventListener("click", function () {
    document.getElementById("chartTpyeDropDown").classList.toggle("hidden");
});
document.addEventListener("click", function (event) {
    if (
        !document.getElementById("chartTpyeBtn").contains(event.target) &&
        !document.getElementById("chartTpyeDropDown").contains(event.target)
    ) {
        document.getElementById("chartTpyeDropDown").classList.add("hidden");
    }
});
