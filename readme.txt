=== MoneyPress : CafePress Edition ===
Plugin Name: MoneyPress : CafePress Edition
Contributors: cybersprocket
Donate link: http://www.cybersprocket.com/products/wpquickcafepress/
Tags: plugin,post,page,cafepress,affiliate,shirts,pod,print-on-demand,store,products,ecommerce,revenue sharing,storefront,cj,commission-junction
Requires at least: 2.6
Tested up to: 3.1.1
Stable tag: 3.5

Put CafePress product listings on your posts and pages using a simple short code. List your own products or earn affiliate revenue.  A premium plugin.

== Description ==

This plugin allows you to quickly and easily display products from CafePress on any page or post via a simple shortcode.  Install the plugin and you can add products to your existing blog posts or pages just be entering a shortcode. Multiple configuration options allow you to customize the display.  If you are a CafePress retailer, this plugin is for you!

Note: This is a premium plugin.   See the FAQ for details about our license.


= Features =

* Uses your own API key.
* No revenue sharing, you keep 100% of your sales.
* Built in affiliate tracking for Commission Junction affiliates.

= Looking For Customized WordPress Plugins? =

If you are looking for custom WordPress development for your own plugins, give 
us a call.   Not only can we offer competitive rates but we can also leverage 
our existing framework for WordPress applications which reduces development time 
and costs.

Learn more at: http://www.cybersprocket.com/services/wordpress-developers/

= Related Links =

* [MoneyPress CafePress Edition Product Info](http://www.cybersprocket.com/products/wpquickcafepress/)
* [MoneyPress CafePress Edition Support Pages](http://redmine.cybersprocket.com/projects/wp-cafepress/wiki)
* [Other Cyber Sprocket Plugins](http://wordpress.org/extend/plugins/profile/cybersprocket/) 
* [Custom WordPress Development](http://www.cybersprocket.com/services/wordpress-developers/)
* [Our Facebook Page](http://www.facebook.com/cyber.sprocket.labs)

== Installation ==

= Requirements =

* PHP 5.1+
* SimpleXML enabled (must be enabled manually during install for PHP versions before 5.1.2)

= How To Install =

* Get the ZIP file from Cyber Sprocket
* Get your API key from the CafePress developer site.
* Install the plugin using the zip file.
* Go to MoneyPress : CafePress in the WordPress Settings menu.
* Enter your CafePress Developer API key.

= Upgrade Notice =

This is a major release update for any 2.X users, you will need to re-enter 
settings on your admin panel including the license key and CafePress API key 
after upgrading.

These changes are necessary to move the MoneyPress : CafePress Edition plugin 
onto our standard MoneyPress codebase. This allows us to keep the plugins 
consistent amongst the different editions and gives this plugin the benefit of 
any bug fixes or new base features we add when updating the other MoneyPress 
plugins.

*Note:* When you upgrade this plugin, you will need to update other 
MoneyPress plugins you may have installed if there is a plugin available.


== Frequently Asked Questions ==

= What percentage of my sales does Cyber Sprocket keep? =

None.  Everything you earn through this plugin is yours, we don't siphon off 
any of your sales or earn anything outside of our one-time license fee.

= How do I get my CafePress API Key? =

Sign up at <a href="http://developer.cafepress.com/">Developer.cafepress.com</a> 
and fill out the Register My Application form.  It takes less than 5 minutes and 
is easier than setting up your original CafePress store.

= Why a license fee? =

It helps us support the product and provide regular updates.

= What type of support do I get? =

Cyber Sprocket Labs provides online forums where you can post questions.  The
developers read the forums on a regular basis.   Most inquiries are responded
to within 7 days.

WordPress plugins are a fun way to share our work with the community, but it is 
not our main revenue stream.  Please be patient when looking for responses from 
our dev team.   We want everyone to enjoy using our products, but we must attend 
to the projects that pay the rent first.  Low cost plugins are fun, and we do 
want to support them but it can take a week or more to address any issues you
may be having.

= Will you customize the plugin for me? =

If you want a modification and need it in a hurry, contact us for a quote on
getting this done.  Any work we can re-use and share with the communinty as
part of main plugin can usually be completed in a few weeks.  We charge $60/hr
for this work with most projects running 6-10 hours.    If you want a private
modification we charge $120 for the work.

You can also suggest features or modificaitons in the forum.  We try to release
new features every few months and get most of our ideas from the forums.

= Are there any other fees? =

No, just the initial license fee.  Upgrades are free.  

= What are the terms of the license? =

The license is based on GPL.  You get the code, feel free to modify it as you
wish.  We prefer that our customers pay us because they like what we do and 
want to support our efforts to bring useful software to market.  Learn more
on our [CSL License Terms page](http://redmine.cybersprocket.com/projects/commercial-products/wiki/Cyber_Sprocket_Labs_Licensing_Terms "CSL License Terms page").

= Who Is Cyber Sprocket Labs? =

Cyber Sprocket Labs is a software consulting firm.  We develop custom complex
web applications, mobile applications, and desktop applications for our clients.
If you are looking for help developing and deploying your application, contact
us for a quote.  Our rates start at $80/hr for long term engagements of 6 months
or more and $120/hr for short term projects.

= How can i translate the plugin into my language? =

* Find on internet the free program POEDIT, and learn how it works.
* Use the .pot file located in the languages directory of this plugin to create or update the .po and .mo files.
* Place these file in the languages subdirectory.
* If everything is ok, email the files to lobbyjones@cybersprocket.com and we will add them to the next release.
* For more information on POT files, domains, gettext and i18n have a look at the I18n for WordPress developers Codex page and more specifically at the section about themes and plugins.

== Screenshots ==

1. The plugin in action.
2. The settings menu in the admin panel.
3. Entering a license key and API key via admin panel.
4. The email you will receive upon purchasing the plugin.
5. Getting a CafePress API key from CafePress.


== Changelog ==

= v3.4 (January 1st 2011) =

* Test with WordPress 3.0.4
* Update readme file.

= v3.3 (December 12,2010) =

* Missed a couple of things...
* Quick patch to fix [quickcafepress] errors
* Store ID and Section ID errors when using input arrays

= v3.2 (December 12,2010) =

* Fixed a problem with [quickcafepress] tag on legacy systems
* Fixed cache setting issue (was not saving cache length)
* Extended debugging mode output on product lookups.
* Added proper CSS formatting for new div tags

= v3.0 (November 29th, 2010) =

* Major update move CafePress Edition to a standard codebase.
* The selling price display can now be set to match your locale.
* Better inline help on settings page.
* Ability to specify which "page" of products to return via page="#" qualifier.
* Ability to set default section ID or specify in short code.
* Ability to set default store ID or specify in short code.
* New shortcodes, can be specified as a single shortcode entry, no URL required with store ID set.
* Updated the licensing code and product fetching code with a more robust connection algorithm.
* Added a debugging flag to the settings page to help when things go awry.

= v2.2 (June 10th, 2010) =

* Minor updates extending help.
* Better license lookup.
* Eliminated missing cache message for regular users.

= v2.0 (May 31st, 2010) =

* Earn revenue on ANY CafePress store via the affiliate program.
* Improved data retrieval for faster response and fewer "no products found" messages.
* Eliminated basic error messages for users,errors only appear for admins.
* Improved error reporting.

= v1.2.2 (May 7th, 2010) =
* Fixed loading of thickbox (lightbox image zooming)
* Correctly formatted the readme.txt file

= v1.2.1 (May 3rd, 2010) =
* Try to create cache directory if it does not exist
* Better error handling concerning permissions of cache directory
* Updated product description

= v1.2 (April 19th, 2010) =
* Minor documentation fixes
* Added "demo mode" for non-licensed copies
* Added notification for missing license info
* Added license verification
* Restyled options page to look much nicer

= v1.1 (March 15th, 2010) =
* Initial release (only released on cybersprocket.com)

