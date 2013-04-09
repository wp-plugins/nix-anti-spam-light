jQuery(document).ready(function($) {
	var active = $('form input[type=checkbox]');
	var input  = $('form input[type=text]');

	if ( active.prop('checked') != true ) {
		input.each(function() {
			$(this).attr('readonly','readonly').addClass('disabled-input');
		})
	}

	active.click(function(){
		if ( $( this ).prop('checked') != true) {
			input.each(function(){
				$(this).attr('readonly','readonly').addClass('disabled-input');
			})
		} else {
			input.each(function(){
				$(this).removeAttr('readonly').removeClass('disabled-input');
			})
		}
	})
});