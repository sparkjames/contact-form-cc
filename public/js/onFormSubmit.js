// console.log( $('#contact-form') );
$( "#contact-form" ).submit(function( e ) {
	// console.log('submitting form');
	e.preventDefault();

	var this_form = $(this);

	this_form.find('button[type="submit"]').attr('disabled', 'disabled');
	this_form.find('.alert').hide();

	$.ajax({
		type: 'POST',
		url: '/contact-form-cc/public/inc/formHandler.php',
		// url: '/inc/formHandler.php',
		data: this_form.serialize(),
		dataType: 'json',
		success: function(result){
			// console.log('===== SUCCESS =====');
			// 	console.log(result);

				if( result.errors.length ){
					// Show form errors

				} else if( result.successful_submit === true ){
					this_form.find('.alert-success').show(125);
					this_form.trigger('reset');
				}
		},
		error: function( xhr, errorType, error ){
				// console.log('===== ERROR =====');
				// console.log(xhr);
				// console.log(errorType);
				// console.log(error);
				this_form.find('.alert-error').show(125);
		},
		complete: function( xhr, status ){
				// console.log('===== COMPLETE =====');
				// console.log(xhr);
				// console.log(status);

				this_form.find('button[type="submit"]').removeAttr('disabled');
		}
	});

});

console.log('bottom of js file');
