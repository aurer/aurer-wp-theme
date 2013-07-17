jQuery(document).ready(function($){	
	
	var widgets = ['lastfm', 'reader', 'tweets'];
	$.each(widgets, function(i,name){
		var checkbox = $('#option-enable-'+name);
		var textfield = $('#option-theme-'+name+'-id');

		if(!checkbox.is(':checked')){
			textfield.parents('tr').hide();
		}

		checkbox.on('click', function(e, o){
			if( $(this).is(':checked') ){
				textfield.parents('tr').fadeIn();
			} else {
				textfield.parents('tr').hide();
			}
		});
	});

});