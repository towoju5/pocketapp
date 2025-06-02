// function switchTab(tabId) {
//     // Hide all content sections
//     document.querySelectorAll('.content').forEach(content => {
//         content.classList.add('hidden');
//     });

//     // Show selected content
//     document.getElementById(tabId).classList.remove('hidden');

//     // Update tab styles
//     document.querySelectorAll('.tab-btn').forEach(btn => {
//         btn.classList.remove('bg-blue-600');
//         btn.classList.add('bg-gray-800');
//     });

//     // Highlight active tab
//     event.currentTarget.classList.remove('bg-gray-800');
//     event.currentTarget.classList.add('bg-blue-600');
// }

function switchTab(tabId, clickedButton) {
  // Hide all content sections
  document.querySelectorAll(".content").forEach((content) => {
    content.classList.add("hidden");
  });

  // Show selected content
  document.getElementById(tabId).classList.remove("hidden");

  // Update tab styles
  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.style.background = "#1d2130";
    btn.style.border = "1px solid #454a56";
  });

  // Highlight active tab
  clickedButton.style.background = "#314463";
  clickedButton.style.border = "1px solid #009af9";
}

// Set the default active button and content on page load
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("bythetime").classList.remove("hidden"); // Show default content
  const firstButton = document.querySelector(".tab-btn");
  if (firstButton) {
    firstButton.style.background = "#314463";
    firstButton.style.border = "1px solid #009af9";
  }
});
