jQuery(document).ready(function($){
	'use strict';

	$('#bpchk-pre-place-types').selectize({
		placeholder		: "Select Place Types",
		plugins			: ['remove_button'],
	});

	var acc = document.getElementsByClassName("bpchk-accordion");
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
	$(document).on('click', '.bpchk-accordion', function(){
		return false;
	});

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
	}

	$(document).on('keyup', '#bpchk-api-key', function(){
		var apikey = $(this).val();
		if( apikey != '' ) {
			$('#bpchk-verify-apikey').show();
		} else {
			$('#bpchk-verify-apikey').hide();
		}
	});

	//Verify the api key
	$(document).on('click', '#bpchk-verify-apikey', function(){
		var btn_val = $(this).html();
		var apikey = $('#bpchk-api-key').val();
		$(this).html( btn_val+' <i class="fa fa-refresh fa-spin"></i>' );

		var data = {
			'action'	: 'bpchk_verify_apikey',
			'apikey'	: apikey,
			'latitude'	: latitude,
			'longitude'	: longitude
		}
		$.ajax({
			dataType: "JSON",
			url: bpchk_admin_js_obj.ajaxurl,
			type: 'POST',
			data: data,
			success: function( response ) {
				console.log(response['data']['message']);
				if( response['data']['message'] == 'not-verified' ) {
					$('#bpchk-verify-apikey').html( btn_val+' <i class="fa fa-times"></i>' );
				} else {
					$('#bpchk-verify-apikey').html( btn_val+' <i class="fa fa-check"></i>' );
				}
			},
		});
	});

	//Open the settings panel once checkinby placetype is selected
	$(document).on('click', '.bpchk-checkin-by', function(){
		var checkin_by = $(this).val();
		if( checkin_by == 'placetype' ) {
			$('.bpchk-placetype-settings-row').show();
		} else {
			$('.bpchk-placetype-settings-row').hide();
		}
	});

	//Update the range value when the slider changes
	$(document).on('change', '#bpchk-google-places-range', function(){
		var val = $(this).val();
		$( '#range_disp' ).html( val+' kms.' );
		$( '#hidden_range' ).val( val );
	});

	//Select all place types
	$(document).on('change', '#bpchk-select-all-place-types', function(){
		if ( $( this ).is( ':checked' ) ) {
			$( '.google-place-types' ).prop( 'checked', true );
		} else {
			$( '.google-place-types' ).prop( 'checked', false );
		}
	});
});