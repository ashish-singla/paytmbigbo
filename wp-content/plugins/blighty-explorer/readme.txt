=== Blighty Explorer ===
Contributors: Blighty
Tags: dropbox explorer, dropbox, file manager, explorer, file management, document management, digital store, integrate dropbox, embed dropbox, dropbox upload
Donate link: http://blighty.net/go/blighty-explorer-plugin-paypal-donation/
Requires at least: 4.1.1
Tested up to: 4.3.1
Stable tag: 1.9.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Allows the complete file structure of a folder in Dropbox to be shared and navigated within a Wordpress page or post. Also allows for file uploads.

== Description ==
Allows the complete file structure of a folder in Dropbox to be shared within a Wordpress page or post. The folder can then be navigated via the Wordpress site and files selected and downloaded. Changes in the Dropbox folder, such as the addition or deletion of files, are reflected through to the Wordpress site.

File uploads to Dropbox are also supported.

== Installation ==
- Connect this plugin to your Dropbox account.
- This will create a subfolder called Blighty Explorer in your Apps folder within Dropbox.
- Place your folders and files you wish to share with the WordPress installation inside the Apps/Blighty Explorer subfolder.
- Use the shortcode [bex_folder] in your post or page to display a folder structure / file navigator.
- Use the shortcode [bex_upload] in your post or page to display a file upload dialog.

== Frequently Asked Questions ==

= Can I use share two different folders from the same Dropbox account on two different installations? =
Yes. Structure your Apps/Blighty Explorer folder in Dropbox to have two subfolders. Change the root folder in the settings page on each installation to the subfolder you want to share.

= I like the icons in the file explorer. Can I use them in my software? =
With version 1.3.0, I implemented several icons from the wonderful Silk Icon set by [famfamfam](http://www.famfamfam.com/lab/icons/silk/). They are used under Creative Commons Attribution 3.0 License.

= I've upgraded from Version 1.2.1 or earlier. Do I still need the WP SVG Icons plugin? =
WP SVG Icons provides an easy to implement selection of icons that this plugin used to use in previous versions. It is no longer required and, if you have no other need for it, can be deactivated and deleted. The current version of the Blighty Explorer plugin will detect the presence of WP SVG Icons and prompt you to uninstall it. You can dismiss this prompt if you like.

= Can I use both [bex_folder] and [bex_upload] on the same page? =
Absolutely! The plugin is smart enough to tie the two together. For best results, place the shortcodes side by side: [bex_folder][bex_upload]

= Where has the admin menu gone? =
With version 1.5.1, you'll find them under the Settings menu.

= It seems my pop-up blocker is stopping file downloads. =
Prior to version 1.5.1, there may have been problems with certain browsers and/or pop-up blockers. Hopefully this has been resolved with v1.5.1. If you're still having problems, please open a support ticket stating browser and anything else that may help, and I'll take a look to resolve it.

= I want to only allow logged-in WordPress users or users with certain roles access to using the plugin. How do I do that? =
Use a plugin such as [User Specific Content](https://wordpress.org/plugins/user-specific-content/) in conjunction with this one in order to protect the page.

== Screenshots ==

1. Admin screen to connect to your Dropbox account and set plugin options.
2. Example folder navigation.
3. Example file upload.

== Changelog ==
= Version 1.9.3 - November 15th, 2015 =

* Bug fix. "Allow Uploads" setting not reflecting status correctly in admin options.

= Version 1.9.2 - October 27th, 2015 =

* Minor fix to stylesheet for bex-wrapper selector.

= Version 1.9.1 - September 10th, 2015 =

* Added support for root folder override on both [bex_folder] and [bex_upload] shortcodes.
* Added the folder to the upload email.

= Version 1.9.0 - September 9th, 2015 =

* Added functionality to allow uploads into "current" folder.

= Version 1.8.0 - August 12th, 2015 =

* Added support for lightbox-type plugins.
* Improved handling of file downloads and presentation.
* Improved handling of failed Dropbox authentication on setup.

= Version 1.7.2 - August 8th, 2015 =

* Fixed a bug when there is an & in the Dropbox folder name.

= Version 1.7.1 - August 8th, 2015 =

* Fixed a bug with column header formatting introduced with 1.7.0.

= Version 1.7.0 - August 7th, 2015 =

* Allow for directional sorting by filename
* Added option to download files when selected instead of presenting them in the browser.

= Version 1.6.0 - August 4th, 2015 =

* Added WordPress Role support.
* Improved selection of root folder.

= Version 1.5.2 - June 25th, 2015 =

* More improvements to file downloads.

= Version 1.5.1 - June 25th, 2015 =

* Moved the options menu in the admin to under the settings link.
* Improved the way files are downloaded. Some pop-up blockers were preventing this before.

= Version 1.5.0 - June 18th, 2015 =

* Added caching to reduce hits to Dropbox API
* Added direct link to settings from WordPress' Installed Plugins page.
* Added option to allow uploads for a user that's not logged in.
* Cleaned up some code that could have caused conflicts with other plugins.

= Version 1.4.1 - June 9th, 2015 =

* Bug fix. Removed erroneous space from utilities.php.

= Version 1.4.0 - June 8th, 2015 =

* Added upload functionality.
* Optional email sent to admin when a file is uploaded.
* Allows files to be uploaded to a dedicated folder for review (not visible in the folder view).
* Show Dropbox account information in the admin options.

= Version 1.3.2 - May 20th, 2015 =

* Fixed a bug that caused problems with the folder/file navigation with the default path setting of blank or /.
* Tidied up some HTML in the admin page.

= Version 1.3.1 - May 18th, 2015 =

* Fixed a bug that caused problems with the folder/file navigation when WordPress permalinks were left to their default setting.

= Version 1.3.0 - May 9th, 2015 =

* Replaced the need for the WP SVG Icons plugin.
* Added new Silk icon set from [famfamfam](http://www.famfamfam.com/lab/icons/silk/).
* Show unique icons by file type.
* Only show notices on all plugins and Blighty Explorer pages in the admin.
* Tidied up the formatting of the Dropbox explorer / folder hierarchy.

= Version 1.2.0 - April 30th, 2015 =

* Added stylesheet support for improved formatting.
* Added support to optionally display file modification date and filesize in the folder/file list.
* Sorted folders to always display above files in the folder/file list.
* Added a link in the Admin to the WordPress Support Forums for this plugin.
* Tidied up some of the code.

= Version 1.1.1 - April 19th, 2015 =

* Improved root foldername validation.

= Version 1.1.0 - April 18th, 2015 =

* Added admin settings to specify an optional starting (or root) subfolder.
* Added Frequently Asked Questions.

= Version 1.0.0 - April 14th, 2015 =

* Initial release.

== Upgrade Notice ==
* 1.9.3 - Bug fix. "Allow Uploads" setting not reflecting status correctly in admin options.
