function initializeWalletModal() {
  console.log("Initializing wallet modal..."); // Debug log

  const walletButton = document.getElementById("walletButton");
  const walletModal = document.getElementById("walletModal");
  const closeWalletModal = document.getElementById("closeWalletModal");

  if (!walletButton || !walletModal || !closeWalletModal) {
    console.error("Wallet modal elements not found:", {
      button: !!walletButton,
      modal: !!walletModal,
      closeButton: !!closeWalletModal,
    });
    return;
  }

  // Open modal
  walletButton.addEventListener("click", function (e) {
    console.log("Wallet button clicked");
    e.preventDefault();
    e.stopPropagation();
    walletModal.classList.remove("hidden");
    document.body.style.overflow = "hidden"; // Prevent background scrolling
  });

  // Close modal with button
  closeWalletModal.addEventListener("click", function (e) {
    console.log("Close button clicked");
    e.preventDefault();
    walletModal.classList.add("hidden");
    document.body.style.overflow = "";
  });

  // Close modal when clicking backdrop
  walletModal.addEventListener("click", function (e) {
    if (e.target === walletModal) {
      console.log("Clicked modal backdrop");
      walletModal.classList.add("hidden");
      document.body.style.overflow = "";
    }
  });

  // Close modal with Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !walletModal.classList.contains("hidden")) {
      console.log("Escape pressed");
      walletModal.classList.add("hidden");
      document.body.style.overflow = "";
    }
  });

  // Wallet input value to buton
  document.getElementById("amountInput").addEventListener("input", function () {
    document.getElementById("amountDisplay").textContent = this.value.replace(
      /\B(?=(\d{3})+(?!\d))/g,
      ","
    );
  });

  //   Toggle promo code input
  document.getElementById("promo").addEventListener("change", function () {
    document.getElementById(
      "promoInput"
    ).parentElement.parentElement.style.display = this.checked
      ? "block"
      : "none";
  });

  //   Profile
  document
    .querySelector('button[aria-label="Profile status"]')
    .addEventListener("click", () => {
      document.querySelector(".left-navbar").classList.add("active");
      document.querySelector(".navbar-overlay").classList.add("active");
    });

  document.querySelector(".navbar-overlay").addEventListener("click", () => {
    document.querySelector(".left-navbar").classList.remove("active");
    document.querySelector(".navbar-overlay").classList.remove("active");
  });
}

// Export the initialization function
window.initializeWalletModal = initializeWalletModal;
