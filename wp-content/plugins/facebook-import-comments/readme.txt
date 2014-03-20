=== Plugin Name ===
Contributors: baobabko
Donate link: N/A
Tags: facebook, comments, social plugins, facebook integration
Requires at least: 2.8
Tested up to: 3.1
Stable Tag: 1.4

Copy Facebook Comments Social Plugin data into your WordPress blog database.
 
== Description ==

Finally Facebook made it possible. You have a WordPress blog and you are using Facebook Comments Social plugin. You want that comments your visitors add to Facebook become part of your website, not only visually, but as a part of the WordPress database. In April 2011, Facebook released an extension to their protocol (the so called graph api) that makes it possible. This plugin uses the extended Facebook protocol to import comments from Facebook to WordPress database. 

Some visitors prefer to post comments to Facebook Social Plugin as these comments are becoming part of their social activity and are displayed on their wall.

Other visitors prefer not to use Facebook comments. They would like to post their comments to your WordPress blog.

The Facebook Import Comments combines both type of plugins and makes them part of your WordPress blog.

Search Engine Optimization (SEO) benefits should also be considered. Your site will be more dynamic and more attractive for spiders. As a result it will rate higher in search engine results. According to Facebook SEO (Search Engine Optimization) community is the reason for extending the Facebook Social Plugin to be extended to provide an interface for retrieving the comments.

== Screenshots ==
1. This screenshot shows Facebook Comments Social Plugin and imported comments shown in WordPress.
2. The administrative interface.

== Installation ==

1. Extract and upload the directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Upgrade Notice ==

To upgrade your current version, just replace the old plugin version with the current version. The plugin will automatically handle all the necessary changes.

== Frequently Asked Questions ==

Please ask your questions to help complete this section.

Q. I have some ideas about the plugin. What to do?

A. Great! The plugin is distributed under the GPL license. Everyone can contribute to it. There are many types of contributions. For example:
* comments to existing features
* suggestions for new features
* ideas
* translations in different human languages
* direct code contribution

If you have critiques, please share your opinion with me. I would be glad to here your opinion. Maybe I am wrong, or maybe you. Who knows? :-) Let's make the world a better place - together.

Q. What happens if a Facebook user removes its comment for my blog post? What action I should take in this case?

A. Since version 1.1 of the plugin Import Mode control setting allows you to specify the plugin's behavior in this case. By default when user removes a comment from facebook, it is also removed from the blog comment's list. You can change this behavior by using the "Append new" option.

Q. Is the plugin aligned with the Facebook policies?

A. The plugin is aligned with all known Facebook policies and respects user rights. If you think there are some issues with Facebook policies and user rights, I would be glad to hear about them and introduce necessary changes.
In addition some unique plugin features might be useful in this direction:
* "Delete All Imported Facebook Comments"
* Import Mode setting


Q. Can I separate comments comming from WordPress and from Facebook comments social plugin.

A. Yes, you modify your theme to visually and physically separate both types of comments. The plugin stores special value in the "comment_agent" database field which begins with "facebook-seo-comments". You can use this fact to modify behavior and visual appearance.


Q. Why the plugin stores the comments into the WordPress database?

A. There are three reasons
* The most important reason is performance. The WordPress database is used to cache Facebook API requests. The result is significant reduce in Facebook engine hits and reduced website traffic.
* Improved user experience.
* Seamless integration for comments posted through Facebook comments social plugin and WordPress comment features.


== Changelog ==

= 1.1 =
* New Feature: Import Mode which allows "append new" and "append new and delete missing" synchronization modes.
* Workaround: WordPress uses global variable $id. In some themes and plugins this variable is overwritten and the function get_the_ID() returns incorrect result.
* Changed default language settings: "Retrieve blog locale = No", "Retrieve additional locales = ''". Facebook fixed their API so locale parameter is not anymore required when querying the Facebook comments.

= 1.0 =
* Language options for Facebook connection.
* Protect plugin directory from browsing.


= 0.2.1 =
* Description changed to more distinguishable.

= 0.2 =
* Refresh Interval to reduce bandwidth consumption.</li>
* Administrative interface</li>
* Refresh Interval can be changed through the administrative interface.</li>
* All imported comments can be removed through the administrative interface.</li>
* The administrative interface displays the number of currently imported comments.</li>

= 0.1 =
* Ability to automatically and transparently synchronize Facebook Comments Social Plugin for a URL based on post's permalink</li>

