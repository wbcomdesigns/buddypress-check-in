=== BuddyPress Checkins ===
Contributors: wbcomdesigns
Donate link: https://wbcomdesigns.com/donate/
Tags: buddypress, checkins
Requires at least: 3.0.1
Tested up to: 4.8.2
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin allows buddypress members to check-in while doing post update,just like other social sites,you can add places where you visited.

Google Place API key is required for it,You can create your key from [Google Place Web Service Documentation](https://developers.google.com/places/web-service/) link.This plugin also provide to select place types for your members,like if you are foodies you can select food related place type and your members will able to post food related places on your website activity stream. In the same way you can select auto complete box if you want to automatically filled up the location by google map API on member activity page.Both the options will show you a google map on frontend if you type and select a location either in auto complete box or in place types list.Also the plugin provides an xprofile field to set location at BuddyPress profile page.

It will also show a google map for all the activity posts that has a location.If you need additional help you can contact us for [Custom Development](https://wbcomdesigns.com/hire-us/).

== Installation ==

1. Upload the entire bp-checkins folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Does This plugin requires BuddyPress ? =

Yes, It needs you to have BuddyPress installed and activated.

= What is the use of API Key option provided in general settings section ? =

With the help of Google Places Api Key, user can check-in with places autocomplete while updating post in buddypress and list checked in location in google map.

= How can we add a place when updating post? =

Just goto your profile section where you can update post, if you have checked place types option in check in setting there you can see a map marker icon clicking on which you can add your checked-in place but if you have checked auto complete option then there you can see an auto complete text box where you can type and select any location or place. After selection of any place you can see a google map below it.

= Where can I see all check-ins activity? =

Check-ins filter option is provided in BuddyPress filter dropdown option to list all check-ins activity.

= Where can I see favourite locations? =

All favourite locations are listed under Check-ins tab at BuddyPress profile page.

= How to set location at profile page? =

The plugin provides xprofile location field to set location at BuddyPress edit profile page.

= How to go for any custom development? =

If you need additional help you can contact us for <a href="https://wbcomdesigns.com/contact/" target="_blank" title="Custom Development by Wbcom Designs">Custom Development</a>.



== Screenshots ==
1. screenshot-1 - shows the general settings in the plugin.
2. screenshot-2 - shows the support section in the plugin.
3. screenshot-3 - shows the front end panel, to checkin by autocomplete.
4. screenshot-4 - shows the posted activity update using checkin by autocomplete.
5. screenshot-5 - shows the front end panel, to checkin by place types.
6. screenshot-6 - shows the member profile, showing favourite locations.
7. screenshot-7 - shows the edit profile page, to set location using xprofile field.
8. screenshot-8 - shows the profile page, showing location set by xprofile field.

== Changelog ==
= 1.0.0 =
* Initial release.
= 1.0.1 =
Improved documentation and default Place type selection option
= 1.0.2 =
Fixed Map Linking in activity for specific location
= 1.0.3 =
Location selection fixes
= 1.0.4 = 
Dual File Fixes
= 1.0.5 =
A New option autocomplete is added in check-ins plugin setting. Now you can check either autocomplete or place types options. 
If you check autocomplete, a autocomplete text box will be shown at the top of page under textarea on member activity page where you can type and select any location from the list.
Now all activity posts which have any place or location a google map will be shown below them to point that particular place.
A new xprofile location field is added at buddypress profile page from where user can set location.