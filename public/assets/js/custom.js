document.addEventListener('DOMContentLoaded', function () {
    // Echo listeners
    try {
        const tradeChannel = Echo.channel("trade.created");
        const tradeUpdateChannel = Echo.channel("trade.updated");

        // toastr.success("Trade update connected");
        console.log("Echo connected successfully");

        tradeChannel.listen(".trade.created", (data) => {
            if (data?.id) {
                console.log("Trade Created:", data);
                toastr.success(`Trade event received: ID: ${data.id}`);
            } else {
                console.error("Invalid trade.created event data:", data);
            }
        });

        tradeUpdateChannel.listen(".trade-updated", (data) => {
            if (data?.id) {
                console.log("Trade Updated:", data);
                toastr.success(`Update on trade ${data.id} received`);
            } else {
                console.error("Invalid trade-updated event data:", data);
            }
        });
    } catch (error) {
        console.error("Error initializing Echo channels:", error);
    }

    // Countdown initialization
    function formatTime(seconds) {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        const pad = n => n.toString().padStart(2, '0');
        return hrs > 0 ? `${pad(hrs)}:${pad(mins)}:${pad(secs)}` : `${pad(mins)}:${pad(secs)}`;
    }

    // function updateCountdowns() {
    //     document.querySelectorAll('.countdown-timer').forEach(timer => {
    //         let seconds = parseInt(timer.getAttribute('data-timestamp'), 10);
    //         if (seconds > 0) {
    //             seconds--;
    //             timer.setAttribute('data-timestamp', seconds);
    //             timer.textContent = formatTime(seconds);
    //         } else {
    //             timer.textContent = '00:00';
    //         }
    //     });
    // }
    function updateCountdowns() {
        const $noTradeTag = document.getElementById('regular-trade-no-trade-text-default');
        document.querySelectorAll('.countdown-timer').forEach(timer => {
            let seconds = parseInt(timer.getAttribute('data-timestamp'), 10);
            if (seconds > 0) {
                seconds--;
                timer.setAttribute('data-timestamp', seconds);
                timer.textContent = formatTime(seconds);
            } else {
                timer.textContent = '00:00';

                const signalCard = timer.closest('.signal-card');
                const expressCard = timer.closest('.express-trading-card');
                const closedContainer = document.querySelector('.trade-closed-content.trade_list-page');
                const expressClosedContainer = document.querySelector('.express-trade-closed-content.express-trade_list-page');


                if (expressCard && closedContainer && !expressCard.classList.contains('moved')) {
                    // closedContainer.appendChild(signalCard);
                    expressClosedContainer.prepend(expressCard);
                    if ($noTradeTag) {
                        $noTradeTag.style.display = 'none';
                    }
                    signalCard.classList.add('moved'); // Prevent moving it multiple times
                } else if (signalCard && closedContainer && !signalCard.classList.contains('moved')) {
                    // closedContainer.appendChild(signalCard);
                    closedContainer.prepend(signalCard);
                    if ($noTradeTag) {
                        $noTradeTag.style.display = 'none';
                    }
                    signalCard.classList.add('moved'); // Prevent moving it multiple times
                }
            }
        });
    }


    function initCountdowns() {
        document.querySelectorAll('.countdown-timer:not([data-initialized])').forEach(timer => {
            const seconds = parseInt(timer.getAttribute('data-timestamp'), 10);
            timer.textContent = formatTime(seconds);
            timer.dataset.initialized = 'true';
        });
    }

    function observeDOMChanges() {
        const observer = new MutationObserver(initCountdowns);
        observer.observe(document.body, { childList: true, subtree: true });
    }

    initCountdowns();
    setInterval(updateCountdowns, 1000);
    observeDOMChanges();

    // Tab switching
    function activateTab(activeTab, inactiveTab, activeContent, inactiveContent) {
        $(activeContent).removeClass("hidden");
        $(inactiveContent).addClass("hidden");

        $(activeTab).addClass("active-tab text-gray-200 bg-[#1e2131]")
            .removeClass("text-gray-500 bg-[#272b3c]");
        $(inactiveTab).removeClass("active-tab text-gray-200 bg-[#1e2131]")
            .addClass("text-gray-500 bg-[#272b3c]");

        $(activeTab).find(".tab-indicator").removeClass("hidden");
        $(inactiveTab).find(".tab-indicator").addClass("hidden");
    }

    $("#openTab").on("click", () => activateTab("#openTab", "#closedTab", "#openTrades", "#closedTrades"));
    $("#closedTab").on("click", () => activateTab("#closedTab", "#openTab", "#closedTrades", "#openTrades"));
    activateTab("#openTab", "#closedTab", "#openTrades", "#closedTrades");

    // Trade form submission
    const tradeForm = document.getElementById("tradeForm");
    const tradeButtons = document.querySelectorAll(".cta-button");

    tradeButtons.forEach(button => {
        button.addEventListener("click", () => {
            const direction = button.getAttribute("data-value");
            if (!direction) return toastr.error("Trade direction is missing.");

            const formData = new FormData(tradeForm);
            formData.append("direction", direction);
            tradeButtons.forEach(btn => btn.disabled = true);

            fetch(tradeForm.getAttribute("action"), {
                method: "POST",
                body: new URLSearchParams([...formData])
            })
                .then(res => res.json())
                .then(response => {
                    if (response.status) {
                        toastr.success(response.message);
                        const trade = response.trade;
                        const tradeHtml = response.html;

                        $("#openTradeList").prepend(tradeHtml);
                        document.getElementById("tradesList").insertAdjacentHTML("afterbegin", tradeHtml);

                        let timeLeft = trade.trade_close_time;
                        const interval = setInterval(() => {
                            if (timeLeft <= 0) {
                                clearInterval(interval);
                                document.querySelectorAll(`.countdown-${trade.id}`).forEach(el => el.textContent = "Completed");
                            } else {
                                document.querySelectorAll(`.countdown-${trade.id}`).forEach(el => el.textContent = `${timeLeft} seconds`);
                                timeLeft--;
                            }
                        }, 1000);

                        tradeForm.reset();
                    } else {
                        toastr.error(response.message);
                    }
                })
                .catch(console.error)
                .finally(() => tradeButtons.forEach(btn => btn.disabled = false));
        });
    });

    // Asset dropdown toggle
    const assetBtn = document.getElementById("assetBtn");
    const assetDropDown = document.getElementById("assetDropDown");

    if (assetBtn && assetDropDown) {
        assetBtn.addEventListener("click", () => {
            assetDropDown.classList.toggle("hidden");
        });
    }

    // Type filter
    document.querySelectorAll('#asset-filters .filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#asset-filters .filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const type = btn.dataset.filter;
            document.querySelectorAll('#asset-list .asset-item').forEach(item => {
                item.style.display = (type === 'all' || item.dataset.type === type) ? 'flex' : 'none';
            });
        });
    });

    // Countdown labels with duration
    document.querySelectorAll('.countdown').forEach(cd => {
        const mins = parseInt(cd.dataset.duration, 10) || 1;
        const end = Date.now() + mins * 60 * 1000;

        const tick = setInterval(() => {
            const diff = end - Date.now();
            if (diff <= 0) {
                cd.textContent = `M${mins} 00:00`;
                clearInterval(tick);
            } else {
                const t = Math.floor(diff / 1000);
                cd.textContent = `M${mins} ${String(Math.floor(t / 60)).padStart(2, '0')}:${String(t % 60).padStart(2, '0')}`;
            }
        }, 1000);
    });

    // Trade selection
    const selectedTrades = new Map();

    document.querySelectorAll('.trade-btn').forEach(button => {
        button.addEventListener('click', function () {
            const { asset, direction, close } = this.dataset;
            const key = `${asset}-${direction}`;

            alert(`Selected Asset: ${asset}\nDirection: ${direction}\nClose Time: ${close}`);

            if (selectedTrades.has(key)) {
                selectedTrades.delete(key);
                document.querySelector(`#selected-trades-item-${CSS.escape(key)}`)?.remove();
            } else {
                selectedTrades.set(key, { asset, direction, closeTime: close });
                addTradeToList(asset, direction, close);
            }
        });
    });

    function addTradeToList(asset, direction, closeTime) {
        const key = `${asset}-${direction}`;
        const li = document.createElement('li');
        li.id = `selected-trades-item-${key}`;
        li.className = "flex justify-between items-center bg-white dark:bg-gray-700 p-2 rounded shadow-sm";

        li.innerHTML = `
            <span>${asset.toUpperCase()} → ${direction.toUpperCase()} <span class="text-xs text-gray-500">(Close: ${closeTime})</span></span>
            <button class="remove-trade px-2 py-1 text-xs text-red-600 hover:underline" data-key="${key}">Remove</button>
        `;

        document.querySelector('#selected-trades').appendChild(li);

        li.querySelector('.remove-trade').addEventListener('click', function () {
            selectedTrades.delete(key);
            li.remove();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const paymentButton = document.getElementById('paymentButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownArrow = document.getElementById('dropdownArrow');
        const selectedOption = document.getElementById('selectedOption');
        const paymentOptions = document.querySelectorAll('.payment-option');

        function toggleDropdown() {
            dropdownMenu.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        }

        function selectOption(button) {
            const option = button.dataset.option;
            const buttonContent = button.innerHTML;
            selectedOption.innerHTML = buttonContent;
            toggleDropdown();
        }

        paymentButton.addEventListener('click', toggleDropdown);

        paymentOptions.forEach(option => {
            option.addEventListener('click', () => selectOption(option));
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!paymentButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownArrow.classList.remove('rotate-180');
            }
        });
    });
});
