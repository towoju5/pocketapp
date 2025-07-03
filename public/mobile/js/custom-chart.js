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

  