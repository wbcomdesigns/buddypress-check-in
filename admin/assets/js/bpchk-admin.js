jQuery(document).ready(function(){
	//Select all place types
	jQuery(document).on('change', '#bpchk_select_all_place_types', function(){
		if( jQuery( this ).is( ':checked' ) ) {
			jQuery( '.place_types' ).prop( 'checked', true );
		} else {
			jQuery( '.place_types' ).prop( 'checked', false );
		}
	});

	//Range slider on admin page
    jQuery( '#google_range' ).change(function(){
        var range = jQuery( this ).val();
        jQuery( '#range_disp' ).html( range+" kms." );
        jQuery( '#hidden_range' ).val( range );
        console.log( jQuery( '#hidden_range' ).val() );
    });
});