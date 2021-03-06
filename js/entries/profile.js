'use strict';

import "../main.js";
import $ from "jquery";

$(window).on('load', function() {
	$(".unread-notification").mouseover(function() {
		if (!$(this).hasClass("unread-notification"))
			return;

		$(this)
			.removeClass("unread-notification")
			.addClass("read-notification");

		let id = $(this).find(".notification-id").html().trim();
		id = parseInt(id);

		$.ajax({
			method: "GET",
			url: "/api/readNotification",
			data: {
				id: id
			}
		})
	});	
})
