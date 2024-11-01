=== WP-AutoSocial ===
Contributors: paucapo
Donate link: http://capo.cat/donations/
Tags: post, posts, plugin, twitter, facebook, posting, update, status, share, api, app, bitly
Requires at least: 2.9
Tested up to: 3.3.1
Stable tag: 3.3.1

Share to Twitter and Facebook your published posts in your Wordpress blog!

== Description ==
Share to Twitter and Facebook your published posts in your Wordpress blog!

= Requirements =
* PHP5 (json_decode).


== Installation ==

= First time installation =
1. Upload the FOLDER 'wp-autosocial' to the /wp-content/plugins/
2. Activate the plugin 'WP-AutoSocial' through the 'Plugins' menu in admin
3. Go to 'Manage / AutoSocial' for more instructions

= Upgrade from a previous version =
1. Deactivate 'WP-AutoSocial' through the 'Plugins' menu in admin
2. Upload the FOLDER 'wp-autosocial' to the /wp-content/plugins/
3. Activate the plugin 'WP-AutoSocial' through the 'Plugins' menu in admin

<a href="http://capo.cat/wp-autosocial/twitter-help/">Twitter Help</a>
<a href="http://capo.cat/wp-autosocial/facebook-help/">Facebook Help</a>

== Frequently Asked Questions ==

= Can I update a Facebook Fan Page status? =
Yes, you can.

= I need a Twitter or Facebook application? =
Yes, you need both.

= How do I report a bug? =
<a href="mailto:pau@capo.cat">Contact me</a> or <a href="http://capo.cat/wp-autosocial/">leave a comment</a>, describe the problem as good as you can and what version you use.

= How can I support this plugin? =
Spread the word, report bugs and give me feedback or make a donation on my blog.

= I need more help!
<a href="http://capo.cat/wp-autosocial/twitter-help/">Twitter Help</a>
<a href="http://capo.cat/wp-autosocial/facebook-help/">Facebook Help</a>

== Screenshots ==

1. The WP-AutoSocial options page.
2. The WP-AutoSocial metabox in post page.

== Changelog ==

For later versions go to <a href="http://capo.cat/wp-autosocial/">this page</a>.

= 0.4.1 =
* Small correction to solve duplicate post problems with FeedWordPress.

= 0.4 =
* Remodelation of base code to only use wp_insert_post.
* Custom post types integration.

= 0.3.6 =
* Localization correction to use with qTranslate properly.
* Bit.ly integration (thanks to <a href="http://www.garymartinphotography.co.nz/">Gary Martin</a> for giving me the idea and a few lines of code).

= 0.3.5 =
* wp_insert_post hook added, solves problemes with another plugins, publish from mobile app...
* Post password protected correction, now don't show the content on Facebook updates.
* Shortcodes parsed on Facebook updates description.

= 0.3.4 =
* Global corrections in english texts (yes, my english it's not really good).
* Changes on this sidebar.
* Function graph to autoocial_graph, that was a really BIG error. Excuse me!
* Uninstall hook added.

= 0.3.3 =
* Better help sections (screenshots and explanations).

= 0.3.2 =
* Error on including facebook library (thank's Jordi Salord)
* Some corrections for Wordpress 3.3

= 0.3 =
* Meta box in post editor page with send option and links to the published post in Twitter and Facebook.
* Add configuration options for meta box.
* Add configuration options for automatically publish post to Facebook and Twitter (future post allowed).

= 0.2.1 =

* Small fix for Settings page (thank's Nathan).
* Allow to share future posts.

= 0.2 =

* Settings page design modification
* Fixed facebook update limit to 1000 characters (thank's Mark).
* Changed facebook publish to page way to get the token.

== Upgrade Notice == 
...
