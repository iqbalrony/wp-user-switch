(function ($) {
	"use strict";

	$(document).ready(function () {

		var elSelect = $(".wpus-allow-user-wrap .selected-user-name");
		var select2Container = $('#wpus-select2-dropdown-container');

		elSelect.each(function(){
			var $item = $(this);
			console.log($item);
			$item.select2({
				placeholder: '--Please choose an option--',
				allowClear: true,
				multiple: true,
				dropdownParent: $item.parents('.selected-users').find('.wpus-select2-dropdown-container')
			});
		});



	});
	/*End document ready*/

})(jQuery);
