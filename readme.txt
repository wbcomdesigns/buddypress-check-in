=== BuddyPress Checkins ===

Contributors: wbcomdesigns, vapvarun
Donate link: https://wbcomdesigns.com/donate/
Tags: buddypress, check-ins , BuddyPress Location, update check-ins, location
Requires at least: 5.0.0
Tested up to: 5.3.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This plugin allows BuddyPress members to share their location when they are posting activities, you can add places where you visited, nearby locations based on google places.

Auto Complete feature: You can add location for your choice start typing location name and it will suggest based on your inbut and you can select it.

Google Place API key is required for it, You can create your key from [Google Place Web Service Documentation](https://developers.google.com/places/web-service/) link.

It will also show a google map for all the activity posts that has a location. If you need additional help you can contact us for [BuddyPress Check-ins](https://wbcomdesigns.com/downloads/buddypress-checkins/).


== Installation ==

1. Upload the entire bp-check-in folder to the /wp-content/plugins/ directory.

2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Does This plugin requires BuddyPress? =

Yes, It needs you to have BuddyPress installed and activated.

= What is the use of API Key option provided in general settings section? =

With the help of Google Places API Key, a user can check-in with places autocomplete while updating post in BuddyPress and list checked in a location in google map.

= Does this plugin require current location service? =

Yes, this plugin require location service and you can allow it from browser settings.

= How can we add a place when updating post? =

Just go to your profile section where you can update post, if you have checked place types option in check in setting where you can see a map marker icon clicking on which you can add your checked-in place but if you have checked autocomplete option then there you can see an auto-complete text box where you can type and select any location or place. After selection of any place, you can see a google map below it.

= Where can I see all check-ins activity? =

Check-ins filter option is provided in BuddyPress filter drop-down option to list all check-ins activity.

= Where can I see favorite locations? =

All favorite locations are listed under Check-ins tab at BuddyPress profile page.

= How to set location at profile page? =

The plugin provides x-profile location field to set location at BuddyPress edit profile page.

= How to go for any custom development? =

If you need additional help you can contact us for <a href="https://wbcomdesigns.com/contact/" target="_blank" title="Wbcom Designs">Wbcom Designs</a>.

== Screenshots ==

1. screenshot-1 -

2. screenshot-2 -

3. screenshot-3 -

== Changelog ==

= 1.4.0 =
* Fix - Added plugin review notice.
* Fix - Hide Checking toggle to hide other button like Quotes, Polls

= 1.3.0 =

* Fix - updated settings
* Fix - Added condition for BuddyPress plugin already active
* Fix - Checkin listing will be displayed to logged in user at their profile only ( no public listing of checkins)

= 1.2.0 =

* Enhancement- Plugin backend settings ui enhancement.
* Enhancement- BP 4.3.0 compatibility.
* Fix- Google placetypes missing with nouveau #44.
* Fix- Add as my place fix #45.

= 1.1.0 =

* Enhancement- Multisite Support

= 1.0.8 =

* Enhancement- Code Quality Improviement with WPCS
* Fix - Tanslation Fixes

= 1.0.7 =

* Fix - UI Improvements
* Fix - Error with PHP 7.0+ version.

= 1.0.6 =

* Fix - Location fixes

= 1.0.5 =

* Fix - A New option autocomplete is added in check-ins plugin setting. Now you can check either autocomplete or place types options.
* Fix - If you check autocomplete, a autocomplete text box will be shown at the top of the page under textarea on member activity page where you can type and select any location from the list.
* Fix - All activity posts which have any place or location a google map will be shown below them to point that particular place.
* Enhancement - A new x-profile location field is added at BuddyPress profile page from where a user can set location.

= 1.0.4 =

* Fix - Dual File Fixes

= 1.0.3 =

* Fix - Location selection fixes

= 1.0.2 =

* Fix - Fixed Map Linking in activity for specific location

= 1.0.1 =

* Fix - Improved documentation and default Place type selection option

= 1.0.0 =

* Fix - Initial release.
