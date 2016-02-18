=== Customize Admin ===
Contributors: vanderwijk
Author URI: http://www.vanderwijk.com/
Tags: custom, admin, customize, logo, login, dashboard, css, meta
Requires at least: 3.5
Tested up to: 4.3.1
Stable tag: 1.7.1

With this plugin you can use customize the appearance of the WordPress login page and dashboard.

== Description ==

With this plugin you can use customize the appearance of the WordPress login page and dashboard. You can upload a custom image for the login screen and specify the link attached to the logo. By default you are redirected to the homepage of your site.

You can find more information about this plugin and a screencast video which shows the plugin in action on the [plugin homepage](http://www.vanderwijk.com/wordpress/wordpress-customize-admin-plugin/).

The Customize Admin plugin also allows you to disable selected dashboard widgets and it can also remove the WordPress meta generator tag from the head section in your website's html code.

== Screenshots ==

1. You can specify the logo image and clickthrough link on the options page. It is also possible to disable the generator meta tag and specified dashboard widgets.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
If you have manage options rights you will see the new Custom Admin Settings menu.
3. Specify a clickthrough url for the logo if required.
4. Specify the url for the custom logo. The path can be relative from the root or include the domain name.
5. If you have not yet uploaded a logo, you can do so via the Upload Image button. Make sure you click 'Insert into Post'. For the best result, use an image of maximum 67px height by 326px width.
6. Click Save Changes.

== Frequently Asked Questions ==

= Why did you make another admin logo plugin?  =

There are already quite a few plugins that offer similar functionality, but the fact that my plugin uses the WordPress Media Library makes it super easy to upload and edit your own logo.

I also am not aware of any other plugins that allow you to specify a clickthrough url for the logo. 

Finally, this plugin is ready to be localized. All you have to do is to use the POT file for translating.

== Changelog ==

= 1.7.1 =
Updated the nl_NL translation file

= 1.7 =
The plugin is now using the media uploader which was introduced in WP 3.5

= 1.6.6 =
Changed dashboard widget visibility settings for WordPress 3.8 widget changes

= 1.6.4 =
WordPress 3.8 compatibility fixes

= 1.6.1 =
Added an option for removing the RSS feed links from the head section of the html source.

= 1.6 =
The Customize Admin plugin now includes the possibility to select a background color for the login screen by using the color picker and you can now also add custom CSS code to style the WordPress login screen.

= 1.5.1 = 
Changed get_bloginfo('siteurl') to get_bloginfo('url') to prevent notices from being displayed on the login screen when debug is enabled.

= 1.5 =
Added option to remove dashboard RSD and WLW meta tags and image size fix for login logo

= 1.4 =
Added option to remove selected dashboard widgets and a fix for an issue that was introduced by WordPress 3.4 which put the title tag value of the logo in the head section of the html.

= 1.3 =
Added option to remove generator meta tag from the head section of the html source.

= 1.2 =
Code cleanup, inclusion of [option to remove the admin shadow](http://www.vanderwijk.com/updates/remove-wordpress-3-2-admin-shadow-plugin/) which was introduced in WordPress 3.2.

= 1.1 =
Minor update, moved the Customize Admin Options page to the Settings menu.

= 1.0 =
First release

== Upgrade Notice ==
