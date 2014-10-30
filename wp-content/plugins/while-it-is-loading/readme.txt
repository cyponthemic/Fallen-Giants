=== While Loading ===
Contributors: Garmur
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JVGHP29EWE85G
Tags: loading, start, display, screen, svg, html5, css3, javascript, personalization, gear, lazy load
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

It shows a screen while all the content loads. If you have a page that takes long time to display it is a good idea to show a "wait until the page loads" image. It seems faster because now they see a message and a gear instead of just dealing with partially rendered content.

= Features  =

* A screen with a picture and a title that disappears when it's loaded.
* Possibility to change the loading icon.
* Lazy loading content images.

== Installation ==

1. Upload the entire `while-it-is-loading` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Write `<?php wl_gear(); ?>` just after body tag in your current template.
4. Please, if you have written `<script>document.write(pantalla)</script>`, delete it.

== Frequently asked questions ==

= Can I change the color background? =
Yes. You can also set transparency.

= Is it possible to select other images? =
Yes. Upload images to `img` folder and select them from options.

= Where are the options? =
See the options section on **Settings > While loading**.

== Screenshots ==

1. View of settings page.
2. Screen when the page is being loaded.

== Changelog ==

= 3.0 =
* Added lazy load.
* Tweak - Images selection.
* Tweak - Fading of loading screen.

= 2.0 =
* Added new images.
* Added a color picker.
* Fade out.
* i18n.
* Other minor details.

= 1.7.14 =
* Fixed: Display is now fixed position.
* Other minor details.

= 1.0 =
* Initial Release.
