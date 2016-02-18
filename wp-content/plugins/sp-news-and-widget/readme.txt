=== WP News and Scrolling Widgets  ===
Contributors: wponlinesupport, anoopranawat 
Tags: wordpress news plugin, main news page scrolling , wordpress vertical news plugin widget, wordpress horizontal news plugin widget , Free scrolling news wordpress plugin, Free scrolling news widget wordpress plugin, WordPress set post or page as news, WordPress dynamic news, news, latest news, custom post type, cpt, widget, vertical news scrolling widget, news widget
Requires at least: 3.1
Tested up to: 4.3
Author URI: http://wponlinesupport.com
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an News custom post type, News widget, vertical and horizontal  scrolling news widget to Wordpress.

== Description ==

Every CMS site needs a news section. SP News  allows you add, manage and display news, date archives, widget, vertical and horizontal news scrolling widget on your website.

View [DEMO](http://demo.wponlinesupport.com/sp-news/) for additional information.

View [screenshots](http://wordpress.org/plugins/sp-news-and-widget/screenshots/) for additional information.

= Installation help and support =
* Please check [Installation and Document ](http://wponlinesupport.com/document/document-wp-news-and-scrolling-widgets/)  on our website. 
* Get [Free installation and setup](http://wponlinesupport.com/plugin-installation-support/)  on your website.

= Important Note For How to Install =
* Please make sure that Permalink link should not be "/news" Otherwise all your news will go to archive page. You can give it other name like "/ournews, /latestnews etc"  
* Now you can Display news post with the help of short code : 
<code> [sp_news] </code>
* Also you can Display the news post with category wise :
<code> Sports news [sp_news category="category_id"] </code>
* Display News with Grid:
<code>[sp_news grid="2"] </code>
* Also you can Display the news post with Multiple categories wise 
<code> Sports news : 
[sp_news category="category_id"]

Arts news 
[sp_news category="category_id"]
</code>

* **Complete shortcode example:**
<code>[sp_news limit="10" category="category_id" grid="2"
 show_content="true" show_category_name="true"
show_date="false" content_words_limit="30" ]</code>

* Comments for the news

* Added Widget Options like Show News date, Show News Categories, Select News Categories.

* Users that are using version 2.1 please paste the shortcode in their News page <code> [sp_news] </code> and If your Permalink link is www.yourdoamin.com/news then plaese change from "news" to other name like "ournews", "latestnews etc" otherwise all your news will go to archive page.

= Following are News Parameters: =

* **limit :** [sp_news limit="10"] (Display latest 10 news and then pagination).
* **category :**  [sp_news category="category_id"] (Display News categories wise).
* **grid :** [sp_news grid="2"] (Display News in Grid formats).
* **show_date :** [sp_news show_date="false"] (Display News date OR not. By default value is "True". Options are "ture OR false")
* **show_content :** [sp_news show_content="true" ] (Display News Short content OR not. By default value is "True". Options are "ture OR false").
* **show_category_name :** [sp_news show_category_name="true" ] (Display News category name OR not. By default value is "True". Options are "ture OR false").
* **content_words_limit :** [sp_news content_words_limit="30" ] (Control News short content Words limt. By default limit is 20 words).


This plugin add a News custom post type,  News widget,  vertical and horizontal news scrolling widget( With setting page 'setting -> News Widget Setting') to your Wordpress site.

The plugin adds a News tab to your admin menu, which allows you to enter news items just as you would regular posts.

If you are getting any kind of problum with news page means your are not able to see all news items then please remodify your permalinks Structure for example 
first select "Default" and save then again select "Custom Structure "  and save. 

Finally, the plugin adds a Recent News Items widget and vertical news scrolling widget , which can be placed on any sidebar available in your theme. You can set the title of this list and the number of news items to show.

= Added New Features : =
* Added Widget Options like Show News date, Show News Categories, Select News Categories.
* Shortcode <code> [sp_news] </code> for news page.
* Category wise News <code> Sports news [sp_news category="category_id"] </code>
* Display News with Grid [sp_news grid="2"]
* Added pagination [sp_news limit="10"]
* Added new shortcode parameters ie show_content, show_category_name and content_words_limit
* Added new shortcode parameters show_date


= Features include: =
* Just create a news page with any name and add the shortcode  <code> [sp_news] </code>
* Vertical and horizontal (Also added thumbnail option) news widget with setting page
* Setting page
* Easy to configure
* Smoothly integrates into any theme
* Yearly, Monthly and Daily archives
* News Categories
* News Tags
* 3 News widgets 
* CSS and JS file for custmization
* Widget Options like Show date, Show Categories, Select News Categories.

 
== Installation ==

1. Upload the 'sp-news-and-widget' folder to the '/wp-content/plugins/' directory.
1. Activate the SP News plugin through the 'Plugins' menu in WordPress.
1. Add and manage news items on your site by clicking on the  'News' tab that appears in your admin menu.
1. Create a page with the any name and paste this short code  <code> [sp_news] </code>.
1. (Optional) Add and configure the News Items widget, vertical and horizontal news scrolling widget for one or more your sidebars.
1. Go to admin 'Setting page -> News Widget Setting' and enter your settings for  vertical and horizontal news scrolling widgets eg Scrolling Direction, Number of news items,  delay  etc. 

== Frequently Asked Questions ==

= What news templates are available? =

This plugin use your theme file content.php file

= Can I filter the list of news items by date? + =

Yes. Just as you can display a list of your regular posts by year, month, or day, you can display news items for a particular year (/news/2013/), month (/news/2013/04/), or day (/news/2013/04/20/).

= Do I need to update my permalinks after I activate this plugin? =

No, not usually. But if you are geting "/news" page OR 404 error on single news then please  update your permalinks to Custom Structure.   

= Are there shortcodes for news items? =

Yse  <code> [sp_news] </code>.

== Screenshots ==

1. Display News with grid view
2. A complate view with comments
3. Display News with List view
4. Add new news
5. Single News view
6. Widgets
7. Admin setting page 
8. Widgets Options

== Changelog ==

= 3.2.1 =

* Added new shortcode parameters show_date.
* Fixed some bugs.

= 3.2 =

* Widget Options like Show News date, Show News Categories, Select News Categories.

= 3.1.1 =
* Solved categories bug

= 3.1 =
* Added new shortcode parameters ie show_content, show_category_name and content_words_limit
* Fixed some bug

= 3.0 =
* Display News with List view
* Display News with Grid [sp_news grid="2"]
* Added pagination [sp_news limit="10"]

= 2.2.1 =
* fixed the bug : Shows news on top of static page 


= 2.2 =
* Call the news post with shortcode
* Call the news post with category wise


= 2.1 =
* Scroll main page news
* Setting page for enable or disable main page news scrolling
* Setting page for main news page vertical and horizontal news scrolling

= 2.0 =
* Added Vertical and horizontal news scrolling widget with setting page
* New UI designs
* Admin setting page

= 1.0 =
* Initial release
* Adds custom post type for News item
* Adds all and single page templates for news
* Adds Letest news widget
* Adds Vertical news scrolling widget



== Upgrade Notice ==

= 3.2.1 =

* Added new shortcode parameters show_date.
* Fixed some bugs.


= 3.2 =

* Widget Options like Show News date, Show News Categories, Select News Categories.

= 3.1.1 =
* Solved categories bug

= 3.1 =
* Added new shortcode parameters ie show_content, show_category_name and content_words_limit
* Fixed some bug

= 3.0 =
* Display News with List view
* Display News with Grid [sp_news grid="2"]
* Added pagination [sp_news limit="10"]

= 2.2.1 =
* fixed the bug : Shows news on top of static page

= 2.2 =
* Call the news post with shortcode
* Call the news post with category wise

= 2.1 =
Scroll main page news
Setting page for enable or disable main page news scrolling
Setting page for main news page vertical and horizontal news scrolling

= 2.0 =
Added Vertical and horizontal news scrolling widget with setting page

= 1.0 =
Initial release


