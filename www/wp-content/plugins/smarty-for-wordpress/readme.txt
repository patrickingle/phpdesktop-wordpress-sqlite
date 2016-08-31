=== Smarty for Wordpress ===
Contributors: phkcorp2005
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9674139
Tags: smarty wp-smarty
Requires at least: 2.8.6
Tested up to: 4.6
Stable tag: 3.1.30

Smarty for Wordpress permits your Smarty template file to be embedded in a Wordpress post/page.

== Description ==

Do you have Smarty templates that you would like to use in your wordpress blog?

Do you want a fast track migration from Smarty to Wordpress?

Smarty for Wordpress is the first plugin which incorporates a complete distribution of the Smarty template engine as a Wordpress plug-in. You embed your Smarty template file by using a Wordpress short code with the Smarty template file name and any variable which you want to be passed to your Smarty template file.

There are many useful frameworks written in Smarty and NOT Wordpress, like XCart, that can NOW be implemented into Wordpress with minimal changes and ease?

Stop! Don't throw away that Smarty Template file, Download Smarty for Wordpress, install, activate and use that Smarty template file in Wordpress TODAY!!!

The following publication, "Guide to Using Smarty for Wordpress Plugin" available on
Amazon at (http://www.amazon.com/dp/B00K5XTPL2) or Barnes & Noble (http://www.barnesandnoble.com/w/guide-to-the-smarty-for-wordpress-plugin-patrick-ingle/1123770360?ean=2940158127281) shows how to implement this framework plugin
in your wordpress blog.



**Usage**

First you need to create your Smarty directories under the theme path that you will be
using, e.g.

themes/theme_name/templates<br>
themes/theme_name/templates_c<br>
themes/theme_name/cache<br>
themes/theme_name/config<br>
themes/theme_name/plugins<br>
themes/theme_name/trusted<br>

If you wish to turn off Wordpress themes by changing the constant WP_USE_THEMES to false, you also need to
set the constant SMARTY_PATH in your wp-config file to the path containing your Smarty files. In addition, you
need to specify your Smarty Loader file. This file is your index.php replacement for the Smarty templates and 
obtain a copy of the smarty instance, make any necessary assignments and load your initial templates. The difference
between this implementation and smarty-only, is you have the full wordpress codex at your dispoable from
your Smarty routines.

You may use Smarty for Wordpress either in you PHP/Theme files or from your Wordpress posts
and pages. To use the API, simple invoke the function that returns the page
information requested. There are three API's for this purpose:

If you wish to use Smarty in your custom worpress php files, then

1. Invoke the smarty_get_instance() function to get an instance of the Smarty class 
	with the directories preset to your current theme.
2. The simply use the Smarty class members as you would normally use, e.g $mySmarty->assign('name','value');
	to assign a template variable with a value.

You can also invoke the API's from your Wordpress pages/posts through short codes.

Create a Wordpress page and enter the following short code: 

	[smarty-display tpl=home.tpl] 
	
	where home.tpl is your smarty template located in the templates path

If you want to pass a single variable with the template, use 

	[smarty-display tpl=home.tpl name=myVariable value="some value"] 

	where name is the variable name specified in you smarty template file, and
	value is the value to be passed to your smarty template file that the above
	variable represents
	
if you want to pass multiple variables to the smarty template, use

	[smarty-load-multiple tpl=home.tpl name='my1,my2,my3' value='1,2,test']
	
	where name holds a comma delimited list of smarty template variable names while
	value holds a comma delimited list of associated values for the variable names.

The admin page under Settings permits setting the following Smarty attributes:
- SmartyBC, for enabling backward compatibility
- Auto literal
- Cache lifetime
- Cache modified check
- Config booleanized
- Config overwrite
- Config read hidden
- Debugging
- Force compile
- PHP handling
- Use sub-directories

Testing Smarty for Wordpress can be accomplished by creating a post with the following shortcode,
[smarty-test]

Testing the Smarty Demo included in the Smarty distribution can be accomplished by creating a page or post with the following shortcode.
[smarty-demo]

== Installation ==

To instal this plugin, follow these steps:

1. Download the plugin
2. Extract the plugin to the `/wp-content/plugins/` directory
3. Create the Smarty directories under your theme path
4. Activate the plugin through the 'Plugins' menu in WordPress
5. You are now ready to use the plugin. See the Admin page from Settings|Smarty for Wordpress for
tips and techniques on usage

== Credits ==

We make honorable mention to anyone who helps make Smarty for Wordpress a better plugin!

== Contact ==

DO NOT ASK FOR SUPPORT FROM www.smarty.net!
Support is provided at https://github.com/patrickingle/smarty-for-wordpress/issues. You will require a free account on github.com

Please contact phkcorp2005@gmail.com or visit the above forum with questions, comments, or requests.

== Frequently Asked Questions ==

Please do not be afraid of asking questions?<br>

(There are no stupid or dumb questions!)

= How can I dynamically set my Smarty variables before loading my Smarty template =
* Use a third party plugin called, Exec-PHP (<a href="http://wordpress.org/extend/plugins/exec-php/">http://wordpress.org/extend/plugins/exec-php/</a>) permits execution of PHP code from the post/page. You can them use smarty_get_instance to set your variables and load your template from within your WP pages/posts.

= How do I change WP_USE_THEMES to false without changing Wordpress core files like index.php =
* You need to install runkit on your web server and then in your wp-config.php, you need to add the following two lines: runkit_constant_remove('WP_USE_THEMES'); define('WP-USE_THEMES',false); Now Wordpres themes will be turned off and load the Smarty Loader instead, if enabled.
* See <a href="http://php.net/manual/en/runkit.installation.php">http://php.net/manual/en/runkit.installation.php</a> for assistance on installing runkit

= What configurations are needed to load Smarty without Wordpress themes =
* You need to specify two constants in your wp-config. 
* define('SMARTY_PATH','/var/www/smarty'); // where your smarty files are located
* define('SMARTY_LOADER','/var/www/smartyloader.php'); // the name of the file which will load your smarty files

= My Smarty templates are not loading from the Smarty loader, I just get a blank screen =
* You need to invoke the plugin function smarty_get_instance() and assign to your $smarty variable. 

= How do I access the WPDB object from the Smarty Loader =
* use the syntax global $wpdb; within the smarty loader file.

== Changelog ==
= 3.1.30.1 =
* Fix loading issues with SmartyBC class

= 3.1.30 =
* Updated to Smarty version 3.1.30, added SmartyBC setting

= 3.1.27.1 =
* Updated Admin page

= 3.1.27 =
* Updated to smarty version 3.1.27

= 3.1.21 =
* Updated to smarty version 3.1.21

= 3.1.18 =
* Updated to smarty version 3.1.18

= 3.1.13.4 =
* Change child themes support to off by default, use the constant SMARTY_CHILDTHEMES = true, to turn on child themes support

= 3.1.13.3 =
* Fix when WP_USE_THEMES is off, wp-login and wp-register will not load the smarty loader
* Enable child themes support, where the smarty files reside in the smarty directory structure under the theme selected including any child theme

= 3.1.13.2 =
* Fix plugins array definition in smarty directory initialization routine as reported by a user.

= 3.1.13.1 =
* Fix when WP_USE_THEMES is off, wp-admin will not load the smarty loader but permit access to Wordpress admin dashboard.

= 3.1.13 =
* Upgrade to smarty version 3.1.13
* When WP_USE_THEMES is false, will load a user define Smarty loader, and now your Smarty templates have access to the Wordpress codex

= 3.0.7.2 =
* Fix an issue when specifying multiple smarty plugin directories

= 3.0.7.1 =
* Fix deprecated issue, function call 'assign_by_ref' is unknown or deprecated

= 3.0.7 =
* Upgrade to smarty version 3.0.7
* Added support for smarty plugins and trusted user directories
* Added support to pass multiple variables to the template

= 3.0.5 =
* Upgrade to smarty version 3.0.5

= 2.6.26.1 =
* Added the smarty-display shortcode in addition to smarty-load 

= 2.6.26 =
* Modified from Smarty version 2.6.26 to work as a Wordpress plugin

== Upgrade Notice ==

The next stable release of Smarty is available and this plugin upgraded appropriately.
