jQuery(document).ready(function($){
	'use strict';
	var autocomplete1;
	var autocomplete2;
	function initialize() {
		var input1 = document.getElementById('bpchk-autocomplete-place');
		var input2 = document.getElementById('bpchk-add-place-title');
		var autocomplete1 = new google.maps.places.Autocomplete(input1);
		var autocomplete2 = new google.maps.places.Autocomplete(input2);

		google.maps.event.addListener(autocomplete1, 'place_changed', function () {
			var place1 = autocomplete1.getPlace();
			var latitude1 = place1.geometry.location.lat();
			var longitude1 = place1.geometry.location.lng();
			$('#bpchk-checkin-place-lat').val( latitude1 ).trigger('change');
			$('#bpchk-checkin-place-lng').val( longitude1 ).trigger('change');
		});

		google.maps.event.addListener(autocomplete2, 'place_changed', function () {
			var place2 = autocomplete2.getPlace();
			var latitude2 = place2.geometry.location.lat();
			var longitude2 = place2.geometry.location.lng();
			document.getElementById('bpchk-place-latitude').value = latitude2;
			document.getElementById('bpchk-place-longitude').value = longitude2;
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);

	//Open the tabs - my places
	var acc = document.getElementsByClassName("bpchk-myplace-accordion");
	var i;
	for ( i = 0; i < acc.length; i++ ) {
		acc[i].onclick = function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight){
				panel.style.maxHeight = null;
			} else {
				panel.style.maxHeight = panel.scrollHeight + "px";
			} 
		}
	}
	$(document).on('click', '.bpchk-myplace-accordion', function(){
		return false;
	});

	//Add the checkin html in post new form
	var checkin_html = bpchk_public_js_obj.checkin_html;
	$(checkin_html).insertAfter( 'textarea#whats-new' );

	$( '#bpchk-add-place-visit-date' ).datepicker( { dateFormat: 'yy-mm-dd', minDate: 0 } );

	//Send AJAX to save the temp location just as location changed during checkin by autocomplete
	$(document).on('change', '#bpchk-checkin-place-lng', function() {  
		var latitude = $('#bpchk-checkin-place-lat').val();
		var longitude = $('#bpchk-checkin-place-lng').val();
		var place = $('#bpchk-autocomplete-place').val();
		var add_as_my_place = 'no';
		if( $('#bpchk-add-as-place').is(':checked') ) {
			add_as_my_place = 'yes';
		}

		$('#bpchk-autocomplete-place').addClass('bpchk-autocomplete-place');
		
		var data = {
			'action'			: 'bpchk_save_temp_location',
			'place'				: place,
			'latitude'			: latitude,
			'longitude'			: longitude,
			'add_as_my_place'	: add_as_my_place
		}
		$.ajax({
			dataType: "JSON",
			url: bpchk_public_js_obj.ajaxurl,
			type: 'POST',
			data: data,
			success: function( response ) {
				if( response['data']['message'] == 'temp-locaition-saved' ) {
					$('#bpchk-add-as-place').attr( 'disabled', true );
					$('#bpchk-autocomplete-place').removeClass('bpchk-autocomplete-place');
				}
			},
		});
	});

	//Open the checkin panel when clicked
	$(document).on('click', '.bpchk-allow-checkin', function(){
		$('.bp-checkin-panel').slideToggle();
	});

	//Send an AJAX to fetch the places when checkin is to be done by placetype
	if( bpchk_public_js_obj.checkin_by == 'placetype' ) {
		var latitude = '';
		var longitude = '';
		bpchk_get_current_geolocation();
		function bpchk_get_current_geolocation() {
			if (navigator.geolocation) { 
				navigator.geolocation.getCurrentPosition(showPosition);
			} else {
				console.log( 'Geolocation is not supported by your browser.' );
			}
		}
		function showPosition(position) {
			latitude = position.coords.latitude;
			longitude = position.coords.longitude;

			var data = {
				'action'			: 'bpchk_fetch_places',
				'latitude'			: latitude,
				'longitude'			: longitude
			}
			
			$.ajax({
				dataType: "JSON",
				url: bpchk_public_js_obj.ajaxurl,
				type: 'POST',
				data: data,
				success: function( response ) {
					console.log( response['data']['message'] );
					$('.checkin-by-placetype').html( response['data']['html'] );
				},
			});
		}
	}

	$(document).on('click', '.bpchk-select-place-to-checkin', function(){
		var place_reference = $(this).data('place_reference');
		var place_id = $(this).data('place_id');
		var add_as_my_place = 'no';
		if( $('#bpchk-add-as-place').is(':checked') ) {
			add_as_my_place = 'yes';
		}
		$(this).html( 'Selecting...' );
		var data = {
			'action'			: 'bpchk_select_place_to_checkin',
			'place_reference'	: place_reference,
			'place_id'			: place_id,
			'add_as_my_place'	: add_as_my_place
		}
		$.ajax({
			dataType: "JSON",
			url: bpchk_public_js_obj.ajaxurl,
			type: 'POST',
			data: data,
			success: function( response ) {
				console.log( response['data']['message'] );
				$('.bpchk-single-location-added').html( response['data']['html'] );
				$('.bpchk-places-fetched, #bpchk-add-as-place, #bpchk-add-my-place-label').hide();
			},
		});
	});

	//Show the places panel
	$(document).on('click', '#bpchk-show-places-panel', function(){
		$('.bpchk-places-fetched, #bpchk-add-as-place, #bpchk-add-my-place-label').show();
	});

	//Hide the places panel
	$(document).on('click', '#bpchk-hide-places-panel', function(){
		$('.bpchk-places-fetched, #bpchk-add-as-place, #bpchk-add-my-place-label').hide();
	});

	//Cancel checkin  - the temporary location
	$(document).on('click', '#bpchk-cancel-checkin', function(){
		var data = {
			'action' : 'bpchk_cancel_checkin'
		}
		$.ajax({
			dataType: "JSON",
			url: bpchk_public_js_obj.ajaxurl,
			type: 'POST',
			data: data,
			success: function( response ) {
				console.log( response['data']['message'] );
				$('.bpchk-checkin-temp-location').remove();
				$('#bpchk-show-places-panel').click();
				$('.bpchk-allow-checkin').click();
			},
		});
	});
});