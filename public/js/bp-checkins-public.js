jQuery(document).ready(function ($) {
	'use strict';

	var autocomplete1;
	var autocomplete2;
	function initialize() {
		if (navigator.geolocation) {

			var options = {
				enableHighAccuracy: true,
				timeout: 5000,
				maximumAge: 0
			};

			navigator.geolocation.getCurrentPosition(success, error, options);
		} else {
			x.innerHTML = "Geolocation is not supported by this browser.";
		}

		var loc_xprof = document.getElementById(bpchk_public_js_obj.bpchk_loc_xprof);
		if (loc_xprof) {
			var autocomplete3 = new google.maps.places.Autocomplete(loc_xprof);
			google.maps.event.addListener(
				autocomplete3, 'place_changed', function () {
					var place3 = autocomplete3.getPlace();
					var latitude3 = place3.geometry.location.lat();
					var longitude3 = place3.geometry.location.lng();
					bpchk_loc_xprof_ajax_save(latitude3, longitude3);
				}
			);
		}
	}
	function error(e) {

		console.log("error code:" + e.code + 'message: ' + e.message);

	}
	function success(position) {
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;

		var myLocation = new google.maps.LatLng(lat, lng);

		var mapOptions = {
			center: new google.maps.LatLng(myLocation.lat(), myLocation.lng()),
			zoom: 13,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		/*start google map api code*/
		if (document.getElementById('checkin-by-autocomplete-map')) {
			var map = new google.maps.Map(
				document.getElementById('checkin-by-autocomplete-map'),
				mapOptions
			);

			var marker = new google.maps.Marker(
				{
					position: myLocation,
					map: map,
					title: "you are here"
				}
			);

			// Create the search box and link it to the UI element.
			var input = document.getElementById('bpchk-autocomplete-place');
			var searchBox = new google.maps.places.SearchBox(input);
			// map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
			// Bias the SearchBox results towards current map's viewport.
			map.addListener(
				'bounds_changed', function () {
					searchBox.setBounds(map.getBounds());
				}
			);

			var markers = [];
			// Listen for the event fired when the user selects a prediction and retrieve
			// more details for that place.
			searchBox.addListener(
				'places_changed', function () {
					var places = searchBox.getPlaces();

					if (places.length == 0) {
						return;
					}

					// Clear out the old markers.
					markers.forEach(
						function (marker) {
							marker.setMap(null);
						}
					);
					markers = [];

					// For each place, get the icon, name and location.
					var bounds = new google.maps.LatLngBounds();
					places.forEach(
						function (place) {
							if (!place.geometry) {
								console.log("Returned place contains no geometry");
								return;
							}
							var icon = {
								url: place.icon,
								size: new google.maps.Size(71, 71),
								origin: new google.maps.Point(0, 0),
								anchor: new google.maps.Point(17, 34),
								scaledSize: new google.maps.Size(25, 25)
							};

							// Create a marker for each place.
							markers.push(
								new google.maps.Marker(
									{
										map: map,
										icon: icon,
										title: place.name,
										position: place.geometry.location
									}
								)
							);

							if (place.geometry.viewport) {
								// Only geocodes have viewport.
								bounds.union(place.geometry.viewport);
							} else {
								bounds.extend(place.geometry.location);
							}

							var latitude1 = place.geometry.location.lat();
							var longitude1 = place.geometry.location.lng();
							$('#bpchk-checkin-place-lat').val(latitude1).trigger('change');
							$('#bpchk-checkin-place-lng').val(longitude1).trigger('change');
						}
					);
					map.fitBounds(bounds);
				}
			);
			/*end google map api code*/
		}
	}
	google.maps.event.addDomListener(window, 'load', initialize);

	function bpchk_loc_xprof_ajax_save(latitude3, longitude3) {

		var place = $('#' + bpchk_public_js_obj.bpchk_loc_xprof).val();

		var data = {
			'action': 'bpchk_save_xprofile_location',
			'place': place,
			'latitude': latitude3,
			'longitude': longitude3
		}

		$.ajax(
			{
				dataType: "JSON",
				url: bpchk_public_js_obj.ajaxurl,
				type: 'POST',
				data: data,
				success: function (response) {

				},
			}
		);
	}

	// Open the tabs - my places
	var acc = document.getElementsByClassName("bpchk-myplace-accordion");
	var i;
	for (i = 0; i < acc.length; i++) {
		acc[i].onclick = function () {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight) {
				panel.style.maxHeight = null;
			} else {
				panel.style.maxHeight = panel.scrollHeight + "px";
			}
		}
	}
	$(document).on(
		'click', '.bpchk-myplace-accordion', function () {
			return false;
		}
	);

	$('#bpchk-add-place-visit-date').datepicker({ dateFormat: 'yy-mm-dd', minDate: 0 });

	// Send AJAX to save the temp location just as location changed during checkin by autocomplete
	$(document).on(
		'change', '#bpchk-checkin-place-lng', function () {
			$('.bpchk-place-loader').show();
			var latitude = $('#bpchk-checkin-place-lat').val();
			var longitude = $('#bpchk-checkin-place-lng').val();
			var place = $('#bpchk-autocomplete-place').val();
			var add_as_my_place = 'no';
			if ($('#bpchk-add-as-place').is(':checked')) {
				add_as_my_place = 'yes';
			}
			// $('#bpchk-autocomplete-place').addClass('bpchk-autocomplete-place');
			var data = {
				'action': 'bpchk_save_temp_location',
				'place': place,
				'latitude': latitude,
				'longitude': longitude,
				'add_as_my_place': add_as_my_place
			}
			// console.log(data);
			$.ajax(
				{
					dataType: "JSON",
					url: bpchk_public_js_obj.ajaxurl,
					type: 'POST',
					data: data,
					success: function (response) {
						if (response['data']['message'] == 'temp-locaition-saved') {
							$('#bpchk-add-as-place').attr('disabled', true);
							$('.bpchk-place-loader').hide();
							$('#bpchk-autocomplete-place').removeClass('bpchk-autocomplete-place');
						}
					},
				}
			);
		}
	);

	// Open the checkin panel when clicked
	$(document).on(
		'click', '.bpchk-allow-checkin', function () {
			$('.bp-checkin-panel').slideToggle(500);
			window.onload = initialize();
		}
	);

	$(document).on(
		'click', '.bpchk-select-place-to-checkin', function () {
			var clicked_event = $(this);
			var place_reference = $(this).data('place_reference');
			var place_id = $(this).data('place_id');
			var add_as_my_place = 'no';
			if ($('#bpchk-add-as-place').is(':checked')) {
				add_as_my_place = 'yes';
			}

			clicked_event.html('<span class="bpchk-place-select-loader">Selecting location..<i class="fa fa-refresh fa-spin"></i></span>');

			// $('.bpchk-select-place-to-checkin').each(function(){
			// $(this).html('Select this location');
			// });
			// $('.bpchk-select-place-to-checkin').not(this).each(function(){
			// $(this).html('Select this location');
			// });
			var data = {
				'action': 'bpchk_select_place_to_checkin',
				'place_reference': place_reference,
				'place_id': place_id,
				'add_as_my_place': add_as_my_place
			}
			$.ajax(
				{
					dataType: "JSON",
					url: bpchk_public_js_obj.ajaxurl,
					type: 'POST',
					data: data,
					success: function (response) {
						$('.bpchk-select-place-to-checkin').html('Select');
						clicked_event.html('Selected');
						console.log(response['data']['message']);
						$('.bpchk-single-location-added').html(response['data']['html']);
						$('.bpchk-places-fetched, #bpchk-add-as-place, #bpchk-add-my-place-label').hide();
					},
				}
			);
		}
	);

	// Show the places panel
	$(document).on(
		'click', '#bpchk-show-places-panel', function () {
			$('.bpchk-places-fetched').slideToggle(500);
			// $('.bpchk-places-fetched, #bpchk-add-as-place, #bpchk-add-my-place-label').show();
		}
	);

	// //Hide the places panel
	// $(document).on('click', '#bpchk-hide-places-panel', function(){
	// $('.bpchk-places-fetched').slideToggle(500);
	// $('.bpchk-places-fetched, #bpchk-add-as-place, #bpchk-add-my-place-label').hide();
	// });
	// Cancel checkin  - the temporary location
	$(document).on(
		'click', '#bpchk-cancel-checkin', function () {
			$('.bpchk-select-place-to-checkin').each(
				function () {
					$(this).html('Select this location');
				}
			);
			var data = {
				'action': 'bpchk_cancel_checkin'
			}
			$.ajax(
				{
					dataType: "JSON",
					url: bpchk_public_js_obj.ajaxurl,
					type: 'POST',
					data: data,
					success: function (response) {
						console.log(response['data']['message']);
						$('.bpchk-checkin-temp-location').remove();
						$('#bpchk-show-places-panel').click();
						$('.bpchk-allow-checkin').click();
					},
				}
			);
		}
	);

	$(
		function () {
			$("#accordion").accordion({ collapsible: true, heightStyle: "content" });
		}
	);

	$("#aw-whats-new-submit").click(
		function () {
			$('.bpchk-select-place-to-checkin').each(
				function () {
					$(this).html('Select this location');
				}
			);
			$('.bpchk-checkin-temp-location').remove();
			if ($('.bp-checkin-panel').is(':visible')) {
				$('.bp-checkin-panel').slideToggle(500);
			}
			if ($('.bpchk-places-fetched').is(':visible')) {
				$('.bpchk-places-fetched').slideToggle(500);
			}

		}
	);

	jQuery(document).on('click', '.bpcp-checkin-trash', function (e) {
		var parent_id = jQuery(this).parent().parent().attr('id');
		var attr_key = jQuery(this).attr('attr-key');
		jQuery.post(
			ajaxurl,
			{
				'action': 'bpchk_delete_user_checkin_location',
				'checkin_id': attr_key,
				success: function (response) {
					jQuery('#' + parent_id).next().remove();
					jQuery('#' + parent_id).remove();
				},
			}
		);
	});

	$(document).on('focus', '#whats-new, #bppfa-whats-new', function () {

		if ($(".rtmedia-plupload-container .bpchk-marker-container").length == 0 && $(".rtmedia-plupload-container").length != 0) {
			$(".bpchk-marker-container").appendTo(".rtmedia-plupload-container");

		}
		if ($(".rtmedia-plupload-container").length != 0) {
			$('#whats-new-options .bpchk-marker-container').hide();
		}
		//$( '.bp-checkin-panel' ).appendTo('#whats-new-options');

		jQuery('.bpchk-marker-container').click(function () {
			jQuery('.bpquotes-bg-selection-div').hide();
			jQuery('.bpolls-polls-option-html').hide();
		});
	});

});
