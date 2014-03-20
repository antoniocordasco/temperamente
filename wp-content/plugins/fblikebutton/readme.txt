=== FaceBook Like Button Plugin for Wordpress ===
Author: Dean Peters (HealYourChurchWebSite.com) 
Contributors: Dean Peters
Donate link: http://www.salvationarmyusa.org/
Plugin link: http://healyourchurchwebsite.com/2010/04/22/the-facebook-like-button-plugin-for-wordpress/
Download link: http://bit.ly/fblikebuttonwp
Tags: Facebook Like Button,Facebook Open Graph,Social Plugins,Social Media,Facebook,Like,Facebook Like
Requires at least: 2.9
Tested up to: 2.9
Version: 0.4a
Stable tag: 0.4

== Description ==
The FBLikeButton Plugin for WordPress allows you to configure and display the FaceBook Like Button before and/or after each post and/or page, and whether or not you want the button to appear on your homepage.

The FaceBook Like Button enables users to make connections to your pages and share content back to their friends on Facebook with one click. Since the content is hosted by Facebook, the button can display personalized content whether or not the user has logged into your site. For logged-in Facebook users, the button is personalized to highlight friends who have also liked the page.

For more information about the FaceBook Like Button and how it works, please visit the <a href="http://developers.facebook.com/docs/reference/plugins/like/">FaceBook Like Button Reference Page</a>.

For more information about the plugin and/or its author, visit us at <a href="http://healyourchurchwebsite.com/2010/04/22/the-facebook-like-button-plugin-for-wordpress/">HealYourChurchWebsite.com</a>.
== Installation ==

This plugin follows the [standard WordPress installation method][]:

1. Upload the 'fblikebutton.zip' file to the `/wp-content/plugins/` directory using wget, curl of ftp.
2. 'unzip' the 'fblikebutton.zip' which will create the folder to the directory `/wp-content/plugins/fblikebutton` 
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin through 'FBLikeButton' submenu in the the 'Settings' section of the Wordpress admin menu
5. Modify the fields to choice and save.
 
[standard WordPress installation method]: http://codex.wordpress.org/Managing_Plugins#Installing_Plugins

== Frequently Asked Questions ==
1. Can I put the FB Like Button in pages?<br />Not yet, but there are plans for that in the next few versions.
2. Can I modify the look and feel of the FB Like Button?<br />Yes, we've added the following attributes to the iframe tag:<br /> id="fbLikeIframe" name="fbLikeIframe" class="fbLikeContainer"
3. Will there be more versions?<br />Yes, there are several other little items we want to fix, but I wanted to get a working version out the door ASAP.
4. Why can I see my changes on preview, but not on my web pages?<br />The preview does not automatically save your changes to the database. You'll still need to hit the 'Update Options' button to make your changes permanent.
== Screenshots ==
1. Screenshot FBLikeButton Admin Screen
2. Screenshot FBLikeButton in action

== Help and Updates ==

The original blog post:
* http://healyourchurchwebsite.com/2010/04/22/the-facebook-like-button-plugin-for-wordpress/

The Facebook Plugin Page:
* http://wordpress.org/extend/plugins/fblikebutton/

== Upgrade Notice ==
version 0.4 now availble - now includes a preview on the admin page, along with a prior fix the layout option issue and while offering more options, top-to-bottom, for your home page, and individual posts and pages

== Changelog ==
= version 0.4a ==
* update help documentation, get SVN to load CSS for brand-new preview feature

= version 0.4 =
* add a preview section to the admin page so you can see changes as they occur

= version 0.3 =
* resolve a reported issue with the layout option
* modify, extend some of the help text

= version 0.2f =
* removed uneeded stuff 
* updated screenshots
* renamed screenshot files
* updated readme.txt to comply with SVN

= version 0.2 =

* added display on home/front page option (thanks http://blog.nazab.com)
* added display on individual page option
* added display on individual post option
* tweaked some code for speed
* changed line 9 of fblike_content_filter.php get_page_link with get_permalink (thanks http://www.hi-d.ch/)

= version 0.1 =
* created the baseline plugin, more to come
