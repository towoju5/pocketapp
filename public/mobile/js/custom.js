async function initializeComponents() {
    try {
        // Load top nav
        const topNavResponse = await fetch("components/top-nav");
        const topNavHtml = await topNavResponse.text();
        document.getElementById("top-nav").innerHTML = topNavHtml;

        // Initialize wallet modal after top nav is loaded
        window.initializeWalletModal();

        // Load bottom nav
        const bottomNavResponse = await fetch("components/bottom-nav");
        const bottomNavHtml = await bottomNavResponse.text();
        document.getElementById("bottom-nav").innerHTML = bottomNavHtml;

        // Initialize dropdown after components are loaded
        console.log("Components loaded, initializing dropdown...");
        initializeAccountDropdown();

        // Show welcome template content
        const template = document.getElementById("welcome-template");
        document.getElementById("main-content").innerHTML =
            template.innerHTML;

        // Initialize chart after template is loaded
        window.forexChart.initialize();

        // Initialize Time Modal Bindings after dynamic insertion
        initTimeModal();
        // Bind Calculator Modal events
        initCalcModal();
        // Initialize counter after template is loaded
        updateCounter();
        // Add navbar initialization
        initializeNavbar();
    } catch (error) {
        console.error("Error initializing components:", error);
    }
}

function initTimeModal() {
    const timeContainer = document.getElementById("timeContainer");
    const timeModal = document.getElementById("timeModal");
    const timeInput = document.getElementById("timeInput");
    const timeDisplay = document.getElementById("timeDisplay");
    const cancelTime = document.getElementById("cancelTime");
    const saveTime = document.getElementById("saveTime");

    if (!timeContainer) {
        console.error("Time container not found.");
        return;
    }

    timeContainer.addEventListener("click", () => {
        timeInput.value = timeDisplay.textContent.trim();
        timeModal.classList.remove("hidden");
    });

    cancelTime.addEventListener("click", () => {
        timeModal.classList.add("hidden");
    });
    timeModal.addEventListener("click", (e) => {
        if (e.target === timeModal) timeModal.classList.add("hidden");
    });
    saveTime.addEventListener("click", () => {
        const newTime = timeInput.value.trim() || "00:00:00";
        timeDisplay.textContent = newTime;
        timeModal.classList.add("hidden");
    });
}

function initCalcModal() {
    const amountContainer = document.getElementById("amountContainer");
    const calculatorModal = document.getElementById("calculatorModal");
    const calcDisplay = document.getElementById("calcDisplay");
    const cancelCalc = document.getElementById("cancelCalc");
    const confirmCalc = document.getElementById("confirmCalc");

    // Bind buttons for digits and operations
    document.querySelectorAll(".calc-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const value = btn.textContent.trim();
            if (value === "=") {
                try {
                    calcDisplay.value = eval(calcDisplay.value);
                } catch (error) {
                    calcDisplay.value = "Error";
                }
            } else {
                if (calcDisplay.value === "0" || calcDisplay.value === "Error") {
                    calcDisplay.value = value;
                } else {
                    calcDisplay.value += value;
                }
            }
        });
    });

    amountContainer.addEventListener("click", () => {
        const amountDisplay = document.getElementById("amountDisplay");
        calcDisplay.value = amountDisplay.textContent.trim();
        calculatorModal.classList.remove("hidden");
    });

    cancelCalc.addEventListener("click", () => {
        calculatorModal.classList.add("hidden");
    });
    calculatorModal.addEventListener("click", (e) => {
        if (e.target === calculatorModal)
            calculatorModal.classList.add("hidden");
    });

    document.getElementById("calcClear").addEventListener("click", () => {
        calcDisplay.value = "";
    });

    confirmCalc.addEventListener("click", () => {
        const amountDisplay = document.getElementById("amountDisplay");
        amountDisplay.textContent = calcDisplay.value || "0";
        calculatorModal.classList.add("hidden");
    });
}

function updateCounter() {
    const leftCounter = document.querySelector(".progress-left");
    const rightCounter = document.querySelector(".progress-right");
    let count = 0;

    setInterval(() => {
        count = count >= 100 ? 0 : count + 1;
        leftCounter.textContent = `${count}%`;
        rightCounter.textContent = `${100 - count}%`;
    }, 100); // Updates every 100ms for a total of 10 seconds (100 steps)
}

// The User at the left nav bar
const eyeIcon = document.querySelector(".fa-eye");
const eyeSlashIcon = document.querySelector(".fa-eye-slash");
const sensitiveData = document.querySelectorAll(".sensitive");

eyeIcon.addEventListener("click", function () {
    toggleVisibility();
});

eyeSlashIcon.addEventListener("click", function () {
    toggleVisibility();
});

function toggleVisibility() {
    sensitiveData.forEach((element) => {
        if (element.dataset.original) {
            element.textContent = element.dataset.original;
            element.removeAttribute("data-original");
        } else {
            element.dataset.original = element.textContent;
            element.textContent = "*******";
        }
    });
    eyeIcon.classList.toggle("hidden");
    eyeSlashIcon.classList.toggle("hidden");
}

// Start initialization
initializeComponents();

// sub left menu
let previousContent = "mainContent";
function showContent(section) {
    document.getElementById(previousContent).classList.add("hidden");
    let newContentId = section + "Content";
    document.getElementById(newContentId).classList.remove("hidden");
    previousContent = newContentId;
}

function goBack() {
    document.getElementById(previousContent).classList.add("hidden");
    document.getElementById("mainContent").classList.remove("hidden");
    previousContent = "mainContent";
}

// Language
function toggleDropdown() {
    let dropdown = document.getElementById("languageContent");
    dropdown.classList.toggle("hidden"); // Toggle visibility
}
