jQuery( document ).ready( function () {
    //Select all place types
    jQuery( document ).on( 'change', '#bpchk_select_all_place_types', function () {

        if ( jQuery( this ).is( ':checked' ) ) {
            jQuery( '.place_types' ).prop( 'checked', true );
        } else {
            jQuery( '.place_types' ).prop( 'checked', false );
        }
    } );

    //Range slider on admin page
    /*jQuery( '#google_range' ).change(function(){
     var range = jQuery( this ).val();
     jQuery( '#range_disp' ).html( range+" kms." );
     jQuery( '#hidden_range' ).val( range );
     console.log( jQuery( '#hidden_range' ).val() );
     });*/

    jQuery( document ).on( 'change', '#auto_complete_radio', function () {
        if ( jQuery( this ).is( ':checked' ) ) {
            jQuery( '#auto_complete_radio' ).prop( 'checked', true );
            jQuery( '#place_types_radio' ).prop( 'checked', false );
            jQuery( '.place_types' ).prop( 'disabled', true );
            jQuery( '#bpchk_select_all_place_types' ).prop( 'disabled', true );
            jQuery( '.place_type_div p' ).css( 'color', 'lightgrey' );
        }
    } );

    jQuery( document ).on( 'change', '#place_types_radio', function () {
        if ( jQuery( this ).is( ':checked' ) ) {
            jQuery( '#place_types_radio' ).prop( 'checked', true );
            jQuery( '#auto_complete_radio' ).prop( 'checked', false );
            jQuery( '.place_types' ).prop( 'disabled', false );
            jQuery( '#bpchk_select_all_place_types' ).prop( 'disabled', false );
            jQuery( '.place_type_div p' ).css( 'color', 'rgb(68, 68, 68)' );
        }
    } );

} );
function updateRangeField( val ) {
    document.getElementById( 'hidden_range' ).value = val;
    jQuery( '#range_disp' ).html( val+' kms.' );

}