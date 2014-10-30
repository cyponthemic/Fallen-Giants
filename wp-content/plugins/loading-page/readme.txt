=== Loading Page with Loading Screen ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/content-tools/loading-page
Tags:animation,page performance,page effects,performance,render time,wordpress performance,image,images,load,loading,lazy,screen,loading screen,lazy loading,fade effect,posts,Post,admin,plugin,fullscreen
Requires at least: 3.0.5
Tested up to: 4.0
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Loading Page with Loading Screen plugin performs a pre-loading of images on your website and displays a loading progress screen with percentage of completion. Once everything is loaded, the screen disappears.

== Description ==

Loading Page with Loading Screen features:

	→ Displays a screen showing loading percentage of a given page
	→ Displays the page's content with an animation after complete the loading process
	→ Increase the WordPress performance
	→ Allows to select the colors of the loading progress screen
	→ Allows to display or remove the text showing the loading percentage
	→ Pre-loads the page images

Loading Page with Loading Screen plugin performs a pre-loading of image on your website and displays a loading progress screen with percentage of completion. Once everything is loaded, the screen disappears.

**More about the Main Features:**

* Displays a screen showing loading percentage of a given page. In heavy pages the "Loading Page with Loading Screen" plugin allows to know when the page appearance is ready.
* Allows to display the loading screen on homepage only, or in all pages of website.
* Allows to select the colors of the loading progress screen. By default the colour of loading screen is black, but it may be modified to adjust the look and feel of the loading screen with website's design.
* Allows to display or remove the text showing the loading percentage.

The base plugin, available for free from the WordPress Plugin Directory, has all the features you need to displays an loading screen on your website.

**Premium Features:**

* Allows to choose a loading progress screen. The premium version of plugin includes multiple loading screens.
* Allows to select from multiple possible animations, to display the page's content after complete the loading process.
* Improves the page performance.
* Lazy Loading feature allows to load faster and reduce the bandwidth consumption. The images are big consumers of bandwidth and loading time, so a WordPress website with multiple images can improve its performance and reduce the loading time with the lazy loading feature. 
* Allows to select an image as a placeholder, to replace the real images during pre-loading. It's recommended to select the lighter images possible to increase the WordPress performance, the image selected will be used in place of images that are not loaded in the first viewport.

**Demo of Premium Version of Plugin**

[http://demos.net-factor.com/loading-page/wp-login.php](http://demos.net-factor.com/loading-page/wp-login.php "Click to access the Administration Area demo")

[http://demos.net-factor.com/loading-page/](http://demos.net-factor.com/loading-page/ "Click to access the Public Page")



**What is Lazy Loading?**

Lazy Loading means that images outside of viewport (visible part of webpage) will not be loaded before user scrolls to them, this action improve the download speed of webpages and reduce the bandwidth consumption. With lazy loading the WordPress performance is increased substantially. 

Normally we only see a part of a webpage we are visiting. To see the rest we usually have to scroll down. For example, in pages with a lot of content in the middle, we can’t see the footer without scrolling down. If Lazy Loading is enabled, only the images in areas that are actually viewed by the user are loaded, and the render time of completed page is reduced. The images in all other areas are loaded only when the user attempts to actually view them by scrolling down to them. This technique increases the loading speed and reduces the bandwidth consumption, as it only loads the images in areas actually "consumed" by the user.

If you want more information about this plugin or another one don't doubt to visit my website:

[http://wordpress.dwbooster.com](http://wordpress.dwbooster.com "CodePeople WordPress Repository")

== Installation ==

**To install Loading Page with Loading Screen, follow these steps:**

1. Download the zipped plugin.
2. Go to the **Plugins** section on your Wordpress dashboard.
3. Click on **Add New**.
4. Click on the **Upload** link.
5. Browse and locate the zipped plugin that you have just downloaded. 
6. Once installed, activate the plugin by clicking on **Activate**. 

== Interface ==

To use Loading Page with Loading Screen on your website, simply activate the plugin. If you wish to modify any of the default options, go to the plugin's Settings. They can be found either by going to Settings > Loading Page on your Wordpress dashboard, or by going to Plugins; a link to Settings can be found in the plugin description.

The Loading Page with Loading Screen setup is divided in two sections: the first one is dedicated to the activation and  setup of the loading screen, and the second to the delayed loading of the images that are not shown immediately ( images that require on-page scrolling in order to be seen).

**Loading Screen Setup**

The setup options for the loading screen are:  

* **Enable loading screen**: activates preloading of images and displays a loading screen while the webpage is loading. 
* **Display loading screen only in**: displays a loading screen only on homepage, all pages, or specific pages or posts. In the last case the IDs of pages or posts should be separated by comma symbol "," 
* **Select the loading screen**: allows to choose a loading screen. The premium version of plugin include multiple loading screens.
* **Select background color**: allows to select the background color for your loading screen compatible with the design guidelines of your website.
* **Select images as background**: allows to display an image as loading screen background, the image can be displayed tiled or centered.
* **Display image in fullscreen**: allows to adjust the background image in fullscreen mode.
* **Select foreground color**: Allows to select the color of the graphics and texts that display the loading progress information.
* **Apply the effect on page**: Display the page's content with an animation after complete the loading process.
* **Display loading percent**: Shows the percentage of loading. The loading percent is calculated in function of images in the page.

**Lazy Loading Setup (in premium version only)**

The options to set up Lazy Loading and increase the WordPress performance are:

* **Enable lazy loading**: Enables the delayed loading of images outside of the current viewing area of the user improving the rendering time of complete page.
* **Select the image to load by default**: Choose an image to be shown as a placeholder of the actual images, the loading of which will be delayed. It's recommended the selection of a light image to increase the WordPress performance.

== Frequently Asked Questions ==

= Q: How the lazy loading increase the WordPress performance? =

A: The lazy loading doesn't load the website images until images be in the viewport. If the user never scrolls the webpage, some images won't download with a reduction in the bandwidth consumption.

= Q: Could I display the loading screen on homepage only? =

A: Yes, that's possible. Go to the settings page of plugin and check the option "homepage only".

= Q: Is possible display the loading screen in some pages only? =

A: Yes, that's possible. Go to the settings page of plugin and check the option "the specific pages", and enter the posts or pages IDs, separated by the comma symbol ",".

= Q: Might I display an image as loading screen background? =

A: Yes, that's possible. Go to the settings page of plugin and select the image in the option "Select image as background". The image can be displayed tiled or centred.

= Q: Are the loading screens supported by all browsers? =

A: There are some loading screens that require of the canvas object, all modern browsers include the canvas object. The screens with special requirements display a caveat text when are selected.

= Q: Why can't I see the animation effect after complete the loading screen? =

A: Please be sure you are using a browser with CSS3 support.

== Screenshots ==
1. Loading Page Preview
2. Loading Screen Available
3. Benefits to use Lazy Load
4. Plugin Settings

== Changelog ==

= 1.0 =

* First version released.

= 1.0.1 =

* Improves the plugin documentation.
* Performs a pre-loading of the images on your website, and displays a loading progress screen with percentage of completion.
* Allows to display an image as background of the loading screen.
* Allows to display the background image in fullscreen mode.
* Associates effects to the page loaded.
* Allows to display the loading screen only on homepage, all pages, or particular pages of website.
* Corrects an issue with the resources loaded in Internet Explorer.
* Reduces the interval of time to display the loading screen.
* Corrects an issue with the percentage text in the loading screen.
* Excludes some files from the loading process.