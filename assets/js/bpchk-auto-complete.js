// JavaScript Document

var checkin_html = '';
//checkin_html += '<a href="javascript:void(0);" id="autoComp" title="Checkin here!"><span><i class="fa fa-map-marker bpchk-location-marker"></i></span></a> Checkin here';
//checkin_html += 'Checkin here';
checkin_html += '<input id="txtPlaces" name="auto_complete" type="text" size="100" placeholder="Enter your location">';
checkin_html += '<br/><div id="map" style="display:none; width:100%; height:200px">';
checkin_html += '<div id="map_canvas" style="width:100%; height:200px"></div>';
checkin_html += '<div id="crosshair"></div></div>';
checkin_html += '<label for="add_location" style="display:none" id="add_location_lbl">';
checkin_html += '<input id="add_location" checked type="checkbox" name="add_location" value="1"/>Add this to my places</label>';
var hidden_html = '';
hidden_html += '<input type="hidden" id="lat" name="lat" value=""/>';
hidden_html += '<input type="hidden" id="lon" name="lon" value=""/>';
hidden_html += '<input type="hidden" id="city" name="city" value=""/>';
hidden_html += '<input type="hidden" id="state" name="state" value=""/>';
hidden_html += '<input type="hidden" id="state_long" name="state_long" value=""/>';
hidden_html += '<input type="hidden" id="zip_code" name="zip_code" value=""/>';
hidden_html += '<input type="hidden" id="country" name="country" value=""/>';
hidden_html += '<input type="hidden" id="country_long" name="country_long" value=""/>';
hidden_html += '<input type="hidden" id="formatted_address" name="formatted_address" value=""/>';
checkin_html = checkin_html + hidden_html;
jQuery("#whats-new-textarea").append(checkin_html);
/*jQuery(document).on("click", "#autoComp", function(){
	jQuery('.chk_in_part').toggle();
});*/
jQuery(document).on("click", "#add_location", function() {
	if (document.getElementById('add_location').checked) {
			jQuery('#add_location').val(1);
	}
	else {
			jQuery('#add_location').val(0);
	}
});


//------------------------------------------------------------------------------------------------

google.maps.event.addDomListener(window, 'load', function () {
var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));
google.maps.event.addListener(places, 'place_changed', function () {
var place = places.getPlace();
var address = place.formatted_address;             
var geocoder = new google.maps.Geocoder();
geocoder.geocode({"address":address }, function(results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
			var latitude = results[0].geometry.location.lat();
			var longitude = results[0].geometry.location.lng();
			jQuery('#map').show();
			jQuery('#add_location_lbl').show();
			initializeMap(latitude,longitude);
			jQuery('#lat').val(latitude);
			jQuery('#lon').val(longitude);

			var arrAddress = place.address_components;
			var itemRoute='';
			var itemLocality='';
			var itemCountry='';
			var itemPc='';
			var itemSnumber='';

		// iterate through address_component array
		jQuery.each(arrAddress, function (i, address_component) {
			if (address_component.types[0] == "route"){
				itemRoute = address_component.long_name;
			}
			if (address_component.types[0] == "locality"){
				city_short_name = address_component.short_name;
						city_long_name = address_component.long_name;
			}
				if (address_component.types[0] == "administrative_area_level_1"){
				state_short_name = address_component.short_name;
						state_long_name = address_component.long_name;
			}
			if (address_component.types[0] == "country"){
				country_short_name = address_component.short_name;
						country_long_name = address_component.long_name;
			}
				if (address_component.types[0] == "postal_code"){
				zip_code = address_component.short_name;
			}
			if (address_component.types[0] == "street_number"){
				street_number = address_component.long_name;
			}
				if (address_component.types[0] == "street_name"){
				street_name = address_component.long_name;
			}
		});
		jQuery('#city').val(city_long_name);
		jQuery('#state').val(state_short_name);
		jQuery('#state_long').val(state_long_name);
		jQuery('#country').val(country_short_name);
		jQuery('#country_long').val(country_long_name);
		jQuery('#zip_code').val(zip_code);
		jQuery('#formatted_address').val(address);
	}
});
});
});

var map;
var geocoder;
var centerChangedLast;
var reverseGeocodedLast;
var currentReverseGeocodeResponse;
function initializeMap(latitude,longitude) {
		var latlng = new google.maps.LatLng(latitude,longitude);
		var myOptions = {
				zoom: 12,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		var geocoder = new google.maps.Geocoder();
		var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				title: "Hello World!"
		});
}
    
			
			
			
			