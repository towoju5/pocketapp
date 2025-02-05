

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

// // handle form submission.
// $("#tradeForm").on("submit", function (e) {
//     e.preventDefault();
//     $(".cta-button").prop("disabled", true);
//     $.ajax({
//         url: $(this).attr("action"),
//         method: "POST",
//         data: $(this).serialize(),
//         success: function (response) {
//             if (response.status) {
//                 toastr.success(response.message);
//                 // Display trade data
//                 const trade = response.trade;
//                 const tradeHtml = response.html;
//                 $("#tradesList").prepend(tradeHtml);

//                 // Start countdown
//                 let timeLeft = trade.trade_close_time;
//                 const countdownInterval = setInterval(() => {
//                     if (timeLeft <= 0) {
//                         clearInterval(countdownInterval);
//                         $(`.countdown-${trade.id}`).text("Completed");
//                         return;
//                     }

//                     $(`.countdown-${trade.id}`).text(`${timeLeft} seconds`);
//                     timeLeft--;
//                 }, 1000);

//                 // Reset form
//                 $("#tradeForm")[0].reset();
//             } else {
//                 toastr.error(response.message);
//             }
//         },
//         error: function (xhr) {
//             toastr.error("An error occurred while placing the trade");
//             console.error(xhr);
//         },
//     });
//     $(".cta-button").prop("disabled", false);
// });
