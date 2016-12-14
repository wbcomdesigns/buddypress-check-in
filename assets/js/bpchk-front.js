jQuery(document).ready(function(){

	//Add Checkin icon under activity update textbox
	var checkin_html = '';
	checkin_html += '<a href="javascript:void(0);" id="position-me" title="Checkin here!">';
	checkin_html += '<span><i class="fa fa-map-marker bpchk-location-marker"></i></span>';
	checkin_html += '</a>';

    checkin_html += '<a href="javascript:void(0);" id="bpchk-close" title="Close">';
    checkin_html += '<span><i class="fa fa-times bpchk-close-marker"></i></span>';
    checkin_html += '</a>';

	checkin_html += '<p class="wait-text" style="display: none;"><i>Please Wait...</i></p>';
	checkin_html += '<div class="locations">';
	checkin_html += '<ul id="locations-list">';
	checkin_html += '</ul>';
	checkin_html += '</div>';
	jQuery( '#whats-new-content' ).prepend( checkin_html );

    //Append a division under the text area to store the check in location
    jQuery('<div class="checkin-panel"></div>').insertBefore('#position-me');

	//Click icon to getnearby places
    jQuery(document).on("click", "#position-me", function(){
        navigator.geolocation.getCurrentPosition(function success(pos) {
            var crd = pos.coords;
            jQuery( ".wait-text" ).html( "<i>Please Wait...</i>" ).show();
            jQuery.post(
                ajaxurl,
                {
                    'action' : 'bpchk_get_locations',
                    'latitude' : crd.latitude,
                    'longitude' : crd.longitude,
                },
                function( response ) {
                    if( response['found'] == 'no' ) {
                        console.log( response['msg'] );
                    } else {
                        jQuery( ".wait-text" ).hide();
                        jQuery( ".locations" ).show();
                        var li_html = '';

                        for( i in response ){
                            var div_html = '';

                            div_html += '<div class="loc-icon">';
                            div_html += '<img height="25px" width="25px" title="'+response[i]['name']+'" src="'+response[i]['icon']+'">';
                            div_html += '<div class="loc-title">';
                            div_html += '<h6>'+response[i]['name']+'</h6>';
                            div_html += '<span>'+response[i]['vicinity']+'</span>';
                            div_html += '</div>';
                            div_html += '</div>';

                            li_html += '<li class="single-location" data-reference="'+response[i]['reference']+'" id="'+response[i]['place_id']+'">';
                            li_html += div_html;
                            li_html += '</li>';
                        }
                        jQuery( "#locations-list" ).html( li_html )
                        jQuery( '#bpchk-close' ).show();
                    }
                },
                "JSON"
            );
        });
    });

    //Get place details
    jQuery(document).on("click", ".single-location", function(){
        var place_id = jQuery(this).attr("id");
        var reference = jQuery(this).data('reference');
        jQuery( ".wait-text" ).html( "<i>Checking in...</i>" );
        jQuery( ".wait-text" ).show();
        jQuery.post(
            ajaxurl,
            {
                'action' : 'bpchk_get_location_detail',
                'place_id' : place_id,
                'reference' : reference,
            },
            function( response ){
                jQuery( ".checkin-panel" ).html( "-at "+response ).show();
                jQuery( ".wait-text" ).hide();
                jQuery( ".locations" ).hide();
            }
        );
    });

    //Close the checkins list
    jQuery(document).on('click', '#bpchk-close', function(){
        jQuery( '.locations' ).hide();
    });

    //Cancel This Location
    jQuery(document).on('click', '#bpchk-cancel-place', function(){
        jQuery( '.checkin-panel' ).hide();
        jQuery( '#bpchk-close' ).hide();
        jQuery.post(
            ajaxurl,
            {
                'action' : 'bpchk_cancel_temp_location',
            },
            function( response ){
                console.log( response );
            }
        );
    });
});
