// $.sidebarMenu = function (menu) {
// 	var animationSpeed = 300;

// 	$(menu).on("click", "li a", function (e) {
// 		var $this = $(this);
// 		var checkElement = $this.next();

// 		if (checkElement.is(".treeview-menu") && checkElement.is(":visible")) {
// 			checkElement.slideUp(animationSpeed, function () {
// 				checkElement.removeClass("menu-open");
// 			});
// 			checkElement.parent("li").removeClass("active");
// 		}

// 		//If the menu is not visible
// 		else if (
// 			checkElement.is(".treeview-menu") &&
// 			!checkElement.is(":visible")
// 		) {
// 			//Get the parent menu
// 			var parent = $this.parents("ul").first();
// 			//Close all open menus within the parent
// 			var ul = parent.find("ul:visible").slideUp(animationSpeed);
// 			//Remove the menu-open class from the parent
// 			ul.removeClass("menu-open");
// 			//Get the parent li
// 			var parent_li = $this.parent("li");

// 			//Open the target menu and add the menu-open class
// 			checkElement.slideDown(animationSpeed, function () {
// 				//Add the class active to the parent li
// 				checkElement.addClass("menu-open");
// 				parent.find("li.active").removeClass("active");
// 				parent_li.addClass("active");
// 			});
// 		}
// 		//if this isn't a link, prevent the page from being redirected
// 		if (checkElement.is(".treeview-menu")) {
// 			e.preventDefault();
// 		}
// 	});
// };
// $.sidebarMenu($(".sidebar-menu"));

// // Custom Sidebar JS
// jQuery(function ($) {
// 	//toggle sidebar
// 	$("#toggle-sidebar").on("click", function () {
// 		$(".page-wrapper").toggleClass("toggled");
// 	});

// 	// Pin sidebar on click
// 	$("#pin-sidebar").on("click", function () {
// 		if ($(".page-wrapper").hasClass("pinned")) {
// 			// unpin sidebar when hovered
// 			$(".page-wrapper").removeClass("pinned");
// 			$("#sidebar").unbind("hover");
// 		} else {
// 			$(".page-wrapper").addClass("pinned");
// 			$("#sidebar").hover(
// 				function () {
// 					// console.log("mouseenter");
// 					$(".page-wrapper").addClass("sidebar-hovered");
// 				},
// 				function () {
// 					// console.log("mouseout");
// 					$(".page-wrapper").removeClass("sidebar-hovered");
// 				}
// 			);
// 		}
// 	});

// 	// Pinned sidebar
// 	$(function () {
// 		$(".page-wrapper").hasClass("pinned");
// 		$("#sidebar").hover(
// 			function () {
// 				// console.log("mouseenter");
// 				$(".page-wrapper").addClass("sidebar-hovered");
// 			},
// 			function () {
// 				// console.log("mouseout");
// 				$(".page-wrapper").removeClass("sidebar-hovered");
// 			}
// 		);
// 	});

// 	// Toggle sidebar overlay
// 	$("#overlay").on("click", function () {
// 		$(".page-wrapper").toggleClass("toggled");
// 	});

// 	// Added by Srinu
// 	$(function () {
// 		// When the window is resized,
// 		$(window).resize(function () {
// 			// When the width and height meet your specific requirements or lower
// 			if ($(window).width() <= 768) {
// 				$(".page-wrapper").removeClass("pinned");
// 			}
// 		});
// 		// When the window is resized,
// 		$(window).resize(function () {
// 			// When the width and height meet your specific requirements or lower
// 			if ($(window).width() >= 768) {
// 				$(".page-wrapper").removeClass("toggled");
// 			}
// 		});
// 	});
// });

// // Day Filter
// $(function () {
// 	$(".day-filters .btn").click(function () {
// 		$(".day-filters .btn").removeClass("btn-info");
// 		$(this).addClass("btn-info");
// 	});
// });

// /***********
// ***********
// ***********
// 	Bootstrap JS 
// ***********
// ***********
// ***********/

// // Tooltip
// var tooltipTriggerList = [].slice.call(
// 	document.querySelectorAll('[data-bs-toggle="tooltip"]')
// );
// var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
// 	return new bootstrap.Tooltip(tooltipTriggerEl);
// });

// // Popover
// var popoverTriggerList = [].slice.call(
// 	document.querySelectorAll('[data-bs-toggle="popover"]')
// );
// var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
// 	return new bootstrap.Popover(popoverTriggerEl);
// });

$(document).ready(function () {


	$(".cta-button").click(function () {
		var action = $(this).val();

		// get the CTA button data-value attribute
		var direction = $(this).data('value');
		$("#direction").val(direction);
		$("#tradeForm").submit();
	});

	const $dropdownButton = $('#dropdownButton');
	const $dropdownContent = $('#dropdownContent');
	const $dropdownArrow = $('#dropdownArrow');

	// Toggle dropdown visibility
	$dropdownButton.on('click', function () {
		$dropdownContent.toggleClass('hidden');
		$dropdownArrow.toggleClass('rotate-180');
	});

	// Close dropdown when clicking outside
	$(document).on('click', function (e) {
		if (!$dropdownButton.is(e.target) && !$dropdownContent.is(e.target) && $dropdownContent.has(
			e.target).length === 0) {
			$dropdownContent.addClass('hidden');
			$dropdownArrow.removeClass('rotate-180');
		}
	});

	$(".js-loading-container").hide()
	$('#leftSideBarArrow').click(function () {
		$(".hidden_text").toggle()
		this.toggleClass('fa-arrow-right-long');
	})


	const $timeInput = $('#timeInput');

	// Handle input for auto-insertion of colons
	$timeInput.on('input', function () {
		let value = $(this).val();

		// Remove non-numeric and non-colon characters
		value = value.replace(/[^0-9]/g, '');

		// Auto-add colons at the 3rd and 6th positions
		if (value.length > 2) {
			value = value.substring(0, 2) + ':' + value.substring(2);
		}
		if (value.length > 5) {
			value = value.substring(0, 5) + ':' + value.substring(5);
		}

		// Trim to max 8 characters (hh:mm:ss)
		value = value.substring(0, 8);

		$(this).val(value);
	});

	// Validate on blur
	$timeInput.on('blur', function () {
		const value = $(this).val();

		// Validate the format
		const isValid = /^([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/.test(value);

		if (!isValid && value !== '') {
			alert('Invalid time format! Please use hh:mm:ss.');
			$(this).val(''); // Clear invalid input
		}
	});
});