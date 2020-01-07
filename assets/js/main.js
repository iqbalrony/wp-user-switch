(function ($) {
	"use strict";

	$(document).ready(function () {
		var $icon = $('.wpus_front_list span.wpus_front_icon');
		var $img = $icon.find('img');
		var $title = $icon.find('span.wpus_front_title');
		var $list = $('.wpus_front_list ul');

		$(document).on('click', function (e) {
			var t = $(e.target);
			console.log(t[0]);
			if (t[0] === $icon[0] || t[0] === $img[0] || t[0] === $title[0]) {
				if ($list.hasClass('visible')) {
					$list.removeClass('visible');
				} else {
					$list.addClass('visible');
				}
				console.log('inside');
			} else {
				console.log('not inside');
				$list.removeClass('visible');
			}
		});

	});
	/*End document ready*/

})(jQuery);