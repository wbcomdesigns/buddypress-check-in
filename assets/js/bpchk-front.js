jQuery(document).ready(function(){

	jQuery('.chk_in_part').hide();

	//Add Checkin icon under activity update textbox
	/*var checkin_html = '';
	checkin_html += '<div id="places_div">';
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
	checkin_html += '</div>';
	jQuery( '#whats-new-content' ).append( checkin_html );*/

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
            function( response )
						{
                jQuery( ".checkin-panel" ).html( "-at "+response ).show();
                jQuery( ".wait-text" ).hide();
                jQuery( ".locations" ).hide();
								var mapAnchor = jQuery('.checkin-panel #bpchk-temp-location').attr('href');
								var arr = mapAnchor.split("@");
								arr = arr[1];
								arr = arr.split(",");
								var lat = arr[0];
								var lon = arr[1];
								if(typeof jQuery('#map').html()=='undefined') {
								var mapHtml = '';
									mapHtml += '<div id="map" style="width:100%; height:200px">';
									mapHtml += '<div id="map_canvas" style="width:100%; height:200px"></div>';
									mapHtml += '<div id="crosshair"></div>';
									mapHtml += '</div>';
									jQuery('#places_div').append(mapHtml);
							  } else {
									jQuery( '#map' ).show();
								}
								var map; var geocoder;
								var centerChangedLast;
								var reverseGeocodedLast;
								var currentReverseGeocodeResponse;
								function initialize()
								{
									var latlng = new google.maps.LatLng(lat,lon);
									var myOptions = { zoom: 17, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP };
									var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
									var geocoder = new google.maps.Geocoder();
									var marker = new google.maps.Marker({ position: latlng, map: map, title: "Hello World!" });
								}
								initialize();
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
				jQuery( '#map' ).hide();
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
