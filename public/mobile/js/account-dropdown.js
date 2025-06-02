function initializeAccountDropdown() {
  // Add small delay to ensure DOM is ready
  setTimeout(() => {
    console.log("Initializing dropdown with delay...");

    const dropdown = document.getElementById("accountDropdown");
    const modal = document.getElementById("dropModal");
    const accountType = dropdown?.querySelector(".account-type");
    const accountBalance = dropdown?.querySelector(".account-balance");

    // Debug log
    console.log("Found elements:", {
      dropdown,
      modal,
      accountType,
      accountBalance,
    });

    if (!dropdown || !modal) {
      console.error("Required elements not found. Ensure IDs are correct:", {
        dropdownExists: !!dropdown,
        modalExists: !!modal,
        accountTypeExists: !!accountType,
        accountBalanceExists: !!accountBalance,
      });
      return;
    }

    // Toggle dropdown
    dropdown.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      const isHidden = modal.classList.contains("hidden");
      modal.classList.toggle("hidden");
      dropdown.setAttribute("aria-expanded", !isHidden);
    });

    // Handle account selection using event delegation
    modal.addEventListener("click", (e) => {
      const option = e.target.closest(".account-option");
      if (!option) return;

      const type = option.dataset.accountType;
      const balance = option.dataset.balance;

      // Update display
      accountType.textContent = type;
      accountBalance.textContent = balance;

      // Save selection
      localStorage.setItem("selectedAccountType", type);
      localStorage.setItem("selectedAccountBalance", balance);

      // Close dropdown
      modal.classList.add("hidden");
      dropdown.setAttribute("aria-expanded", "false");
    });

    // Close when clicking outside
    document.addEventListener("click", (e) => {
      if (!modal.contains(e.target) && !dropdown.contains(e.target)) {
        modal.classList.add("hidden");
        dropdown.setAttribute("aria-expanded", "false");
      }
    });

    // Load saved selection
    const savedType = localStorage.getItem("selectedAccountType");
    const savedBalance = localStorage.getItem("selectedAccountBalance");
    if (savedType && savedBalance) {
      accountType.textContent = savedType;
      accountBalance.textContent = savedBalance;
    }
  }, 100); // Small delay to ensure DOM is ready
}

// Remove the automatic initialization
// Let index.html handle the initialization timing
