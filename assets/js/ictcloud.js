$(document).ready(function() {

	$(".tablefiles").find('tr').find('td').find('i').addClass('fileiconhide');

	$(".tablefiles").find('tr').on({
		mouseenter: function () {
        	$(this).find('td').find('.fileiconhide').removeClass('fileiconhide');
    	},
    	mouseleave: function () {
			$(this).find('td').find('i').addClass('fileiconhide');
    	}
	});
});