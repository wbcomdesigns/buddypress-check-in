// JavaScript Document
var checkin_html = '';
checkin_html += '<div id="places_div">';
checkin_html += '<a href="javascript:void(0);" id="position-me" title="Checkin here!">';
checkin_html += '<span><i class="fa fa-map-marker bpchk-location-marker"></i></span>';
checkin_html += '</a> Checkin here';
checkin_html += '<a href="javascript:void(0);" id="bpchk-close" title="Close">';
checkin_html += '<span><i class="fa fa-times bpchk-close-marker"></i></span>';
checkin_html += '</a>';
checkin_html += '<p class="wait-text" style="display: none;"><i>Please Wait...</i></p>';
checkin_html += '<div class="locations">';
checkin_html += '<ul id="locations-list">';
checkin_html += '</ul>';
checkin_html += '</div>';
checkin_html += '</div>';
jQuery("#whats-new-textarea").append(checkin_html);
		