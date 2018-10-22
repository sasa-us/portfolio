$(function() {

	// Get the form.
	var form = $('#ajax-contact');

	// Get the messages div.
	var formMessages = $('#form-messages');

	// Set up an event listener for the contact form.
	$(form).submit(function(e) {
		// Stop the browser from submitting the form.
		e.preventDefault();

		// Serialize the form data.
		var formData = $(form).serialize();

		// Submit the form using AJAX.
		// add sending ... icon
		
		var i = $('<i>', {
			class: 'fas fa-calculator'
		});
		$('#sendingLogo').append(i);

		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData
		})
		.done(function(response) {
			// Make sure that the formMessages div has the 'success' class.
			$(formMessages).removeClass('error');
			$(formMessages).addClass('success');


			// Set the message text.
			var emailtext = 'Your message has been sent.';
			// $(formMessages).text(response);
			$(formMessages).text(emailtext);

			$('#sendingLogo').empty();

			// Clear the form.
			$('#name').val('');
			$('#email').val('');
			$('#subject').val('');
			$('#message').val('');
		})
		.fail(function(data) {
			// Make sure that the formMessages div has the 'error' class.
			$(formMessages).removeClass('success');
			$(formMessages).addClass('error');
			$('#sendingLogo').empty();
			// Set the message text.
			if (data.responseText !== '') {
				$(formMessages).text(data.responseText);
			} else {
				$(formMessages).text('Oops! An error occured and your message could not be sent.');
			}
		});

	});

});
