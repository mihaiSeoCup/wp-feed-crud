jQuery(document).ready(function(){

	jQuery('#trigger_import').submit(function(event) {
		event.preventDefault();

		jQuery('.ajax_row').hide();
		jQuery('.animation').show();
		jQuery('#trigger_import input[type="submit"]').prop( "disabled", true );

		var data = {
			'action': 'wpfcrud_ajax_call',
		};

		jQuery.ajax({
			url: ajax_object.ajax_url,
			data: data,
			method: "POST"
		}).done(function(resp) {
			if(resp == 'done'){
				jQuery('.animation').hide();
				jQuery('.ajax_row').html("Import finished");
				jQuery('.ajax_row').show();
				jQuery('#trigger_import input[type="submit"]').prop( "disabled", false );

			}
		});

	});
});