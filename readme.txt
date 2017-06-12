=== BuddyPress Checkins ===
Contributors: wbcomdesigns,vapvarun
Donate link: https://wbcomdesigns.com/donate/
Tags: buddypress, checkins
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin allows buddypress members to checkin when doing post update,just like other social sites,you can add places where you visited.

Google Place API key is required for it,You can create your key from [Google Place Web Service Documentation](https://developers.google.com/places/web-service/) link.This plugin also provide to select place types for your members,like if you are foodies you can select food related place type and your members will able to post food related places on your website activity stream. In the same way you can select auto complete box if you want to automatically filled up the location by google map API on member activity page.Both the options will show you a google map on frontend if you type and select a location either in auto complete box or in place types list.

It will also show a google map for all the activity posts that has a location.If you need additional help you can contact us for [Custom Development](https://wbcomdesigns.com/hire-us/).

== Installation ==

1. Upload the entire bp-checkins folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= How can we add a place when updating post? =

Just goto your profile section where you can update post, if you have checked place types option in check in setting there you can see a map marker icon clicking on which you can add your checked in place but if you have checked auto complete option then there you can see an auto complete text box where you can type and select any location or place. After selection of any place you can see a google map below it.

= How to Get Google API Key ? =

Here are step-by-step instructions to create and save a Google API key:
Step 1) Navigate to the Google Developers Console.
https://developers.google.com/places/web-service/
Step 2) Login with Gmail account.
Step 3) Click on GET A KEY and select Project.
Step 4) Click On Enable API button.
Step 5) if you click on enable API button then open a popup with API Key.

= BuddyPress Checkins plugin requires BuddyPress ? =

Yes, It needs you to have BuddyPress installed and activated and the BuddyPress notifications component must be enabled.

== Screenshots ==

1. The screenshot shows the plugin name from the admin panel plugin listing and corresponds to screenshot-1.(png|jpg|jpeg|gif).
2. The screenshot shows the admin settings to add api key and select place types to fetch places and corresponds to screenshot-2.(png|jpg|jpeg|gif).
3. The screenshot shows the checkin icon on update activity page and corresponds to screenshot-3.(png|jpg|jpeg|gif).
4. The screenshot shows the listing of places when user clicks on checkin icon and corresponds to screenshot-4.(png|jpg|jpeg|gif).
5. The screenshot shows the posted activity and corresponds to screenshot-5.(png|jpg|jpeg|gif).
6. The screenshot shows the modified plugin name, version and short description from the admin panel plugin listing and corresponds to screenshot-6.(png|jpg|jpeg|gif).
7. The screenshot shows the modified admin settings to add api key and select place types to fetch places and corresponds to screenshot-7.(png|jpg|jpeg|gif).
8. The screenshot shows the modified checkin icon on update activity page and corresponds to screenshot-8.(png|jpg|jpeg|gif).
9. The screenshot shows the auto complete box on update activity page and corresponds to screenshot-9.(png|jpg|jpeg|gif).
10. The screenshot shows the posted activity with or without google map and corresponds to screenshot-10.(png|jpg|jpeg|gif).

== Changelog ==
= 1.0.0 =
* Initial release.
= 1.0.1 =
Improved documentation and default Place type selection option
= 1.0.2 =
Fixed Map Linking in activity for specific location
= 1.0.3 =
Location selection fixes

= 1.0.5 =

A New option auto complete is added in check in plugin setting. Now you can check either auto complete or place types options. 
If you check auto complete, a auto complete text box will be shown at the top of page under textarea on member activity page where you can type and select any location from the list.
Now all activity posts which have any place or location a google map will be shown below them to point that particular place.

