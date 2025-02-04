=== WP LinkedIn ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://paypal.me/vdvn
Tags: linkedin,resume,recommendations,profile,network updates
Requires at least: 2.7
Requires PHP: 5.2
Tested up to: 4.9.1
Stable tag: 2.9
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This plugin provides you with shortcodes to insert your LinkedIn profile and a rotating scroller of your LinkedIn recommendations in any Wordpress page or post. 

Please check <a href="http://vdvn.me/">vedovini.net</a> for examples.

The following shortcodes are available:

* `[li_recommendations width="480" length="200" interval="1000"]` displays a rotating scroller with the recommendations you received. Only available if LinkedIn granted you access to your full profile.
* `[li_profile]` displays your LinkedIn profile. Optional attributes are `fields` and `lang` to overide the general settings. Only basic profile is available unless LinkedIn granted you access to your full profile.
* `[li_card]` displays a simple LinkedIn card. Optional attributes are `picture_width` and `summary_length`, and `fields` and `lang` to overide the general settings.
* `[li_picture]` displays the original profile picture (size may vary depending on what you uploaded to LinkedIn). Optional attributes are `width`, `height` and `class`.
* `[li_profile_field]` outputs one field from the profile. This shortcode uses the "field" attribute to indicate the path to the target field. For example `[li_profile_field field="first-name"]` will output the profile's first name and `[li_profile_field field="location:name"]` will output the name of the profile's location.

To customize the rendering of the shortcodes you must create a `linkedin` folder in your theme or in the `wp-content` folder and then copy the template file you want to modify. The default template files are located in the plugin's `templates` folder.

You can override the `[li_profile]` shortcode's template by specifying the template code enclosed in the shortcode itself, for example `[li_profile]Hello M. {last-name}, how's your work at {positions:values:0:company:name}?[/li_profile]` will render something like `Hello M. Smith, how's your work at ACME Company?`.

See this post for more details on customization: [Showing more of your LinkedIn profile with WP LinkedIn](http://vdvn.net/more-wp-linkedin)

There are also several widgets. One widget displays the recommendations scroller, one displays your network updates, and two widgets show a "profile card" - one of which is the standard LinkedIn JavaScript profile widget, the other uses a customizable template.

We welcome volunteers to translate that plugin into more languages. If you wish to help then contact [@cvedovini](https://twitter.com/cvedovini) on Twitter or use that [contact form](http://vdvn.net/contact/).

Please check the [WP LinkedIn Multi-Users](http://vdvn.me/p1nd) extension if you need to show the profiles of multiple users.

And if you need to show company profiles or company updates, please check the [WP LinkedIn for Companies](http://vdvn.me/p1nr) extension.

Also available is the [WP LinkedIn Advanced Templates](http://vdvn.me/p2ub) extension that provides extended profile templates (including most of the profile sections) and a more sophisticated template for company page updates.

And if you want to save money then there's the [WP LinkedIn Extensions Bundle](http://vdvn.me/p39h) which includes all the extensions for a reduced price.


== Installation ==

This plugin follows the [standard WordPress installation method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `wp-linkedin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a LinkedIn API key/secret pair and register the redirect URI (follow the instructions on the settings page)
1. Generate an access token for the LinkedIn API (those tokens expire after 60 days so you will have to regenerate them from time to time)
1. The `Profile fields` field is the list of fields that will be available to the profile template for rendering - see this post for more details on customization: [Showing more of your LinkedIn profile with WP LinkedIn](http://vdvn.me/more-wp-linkedin)


== Frequently Asked Questions ==

= I get this error when I refresh my access token: Error message: Your application has not been authorized for the scope \\\"r_fullprofile\\\" (invalid_scope)` =

Uncheck the “full profile” checkbox in the options then try again. Since May 12, 2015, you need to be a LinkedIn partner and be granted full access to your profile to see more than [the basic profile fields](https://developer.linkedin.com/docs/fields/basic-profile). [See this article for more information](http://vdvn.me/p2lg) 

= How can I see my full profile =

Since May 12, 2015, you need to be a LinkedIn partner and be granted full access to your profile to see more than [the basic profile fields](https://developer.linkedin.com/docs/fields/basic-profile). [See this article for more information](http://vdvn.me/p2lg)

= The recommendations widget and shortcode are not working =

Since May 12, 2015, the recommendations are only accessible if you have full access to your profile. 
[See this article for more information](http://vdvn.me/p2lg)

= The updates shortcode (or the updates widget) doesn't show anything =

Since May 12, 2015 the LinkedIn API doesn't allow access to profile updates anymore. The shortcode and widget are not functional anymore. [See this article for more information](http://vdvn.me/p2lg)

= Does the plugin support multiple user profiles? =

No, it doesn't. But there is a premiun extension, [WP LinkedIn Multi-Users](http://vdvn.me/p1nd) that changes the behavior of this plugin so that shortcodes and widgets show the data of the author of the post or page.

= Does the plugin support company profiles? =

No, it doesn't. But there is a premiun extension, [WP LinkedIn for Companies](http://vdvn.me/p1nr) that provides shortcodes and widgets for company profiles and company updates.

= How to add the volunteer experiences section from my profile? =

The volunteer experiences section is in the templates provided by the premium extension [WP LinkedIn Advanced Templates](http://vdvn.me/p2ub) but is not activated by default. To activate it you must add the relevant profile fields to the list of fields, the minimum is `volunteer`. To get the full data use this:

`volunteer:(volunteer-experiences:(organization,cause,role,start-date, end-date,description))`

You also need to be granted access to your full profile to get data not in the basic profile. [See this article for more information](http://vdvn.me/p2lg)

= How to add the projects section to my profile? =

The projects section is in the templates provided by the premium extension [WP LinkedIn Advanced Templates](http://vdvn.me/p2ub) but is not activated by default. To activate it you must add the relevant profile fields to the list of fields:

`projects:(name,url,start-date,end-date,members:(name, person:(public-profile-url,first-name,last-name,picture-url,headline)), description)`

You also need to be granted access to your full profile to get data not in the basic profile. [See this article for more information](http://vdvn.me/p2lg)

= How to add the publications section to my profile? =

The publications section is in the templates provided by the premium extension [WP LinkedIn Advanced Templates](http://vdvn.me/p2ub) but is not activated by default. To activate it you must add the relevant profile fields to the list of fields:

`publications:(title,publisher,authors,date,url,summary)`

You also need to be granted access to your full profile to get data not in the basic profile. [See this article for more information](http://vdvn.me/p2lg)

= How to add the honors & awards section to my profile? =

The honors & awards section is in the templates provided by the premium extension [WP LinkedIn Advanced Templates](http://vdvn.me/p2ub) but is not activated by default. To activate it you must add the relevant profile fields to the list of fields:

`honors-awards:(name,issuer,date,description)`

You also need to be granted access to your full profile to get data not in the basic profile. [See this article for more information](http://vdvn.me/p2lg)

= How to add other sections to my profile? =

For other sections see [Showing more of your LinkedIn profile with WP LinkedIn](http://vdvn.me/more-wp-linkedin).

You also need to be granted access to your full profile to get data not in the basic profile. [See this article for more information](http://vdvn.me/p2lg)

= I have a slider somewhere and it doesn't work anymore when I add the recommendations' scroller =

It's usually due to an incompatibility between the different javascript components used. To solve the incompatibility it's better to choose one of the component and use only this one. To change the component that is used by the recommendations' scroller you will need to customize the `recommendations.php` template.

= I am constantly asked to get a new access token =

There can be several reasons to that problem, check the following:

- Make sure your server's time is correct.
- If you are using an external cache system make sure it's properly configured.
- Try to uncheck the `Verify SSL peer` option on the plugin's settings page.

= Since I updated to v1.6 the recommendation slider does not work anymore =

Since v1.6 the call to add the javascript for the recommendations slider to the page as been moved to the `recommendations.php` template. If you customized that template you must add the following line to the top of your custom template: `wp_enqueue_script('responsive-scrollable');`

= Profile picture is only 80x80 pixels is there a way to get a bigger one from LinkedIn? =

You can either add `picture-urls::(original)` to the list of fields and output that field in a customized template or use the `[li_picture]` shortcode.


== Screenshots ==

1. The recommendation shortcode in action (note that the CSS is customized in that example).
2. Full page using the profile shortcode and displaying the LinkedIn full profile.
3. LinkedIn profile card added after a post content.


== Upgrade Notice ==

= 2.0 =
*BREAKING CHANGE* Following the modifications to the LinkedIn developer program, version 2.0 of this plugin introduce breaking changes. [More information on vedovini.net](http://vdvn.me/p2lg)

= 1.6 =
*BREAKING CHANGE* Starting with version 1.6 the call to add the javascript for the recommendations slider to the page as been moved to the `recommendations.php` template. If you customized that template you must add the following line to the top of your custom template: `wp_enqueue_script('responsive-scrollable');`

= 1.18 =
*BREAKING CHANGE* The redirect uri that is used to process the OAuth access token has changed in this version. You must update the redirect urls setting in you LinkedIn API Application details. See the plugin settings page, API access section, for more.

= 1.14 =
As of April 11, 2014 LinkedIn requires that redirect uris be registered, thus forcing every plugin installation to have its own application key/secret pair and register the corresponding redirect uri. Follow the instructions on the plugin settings page.


== Changelog ==

= Version 2.9.1 =
- Small fixes

= Version 2.9 =
- Added the ability to specify a simple template directly in the `[li_profile]` shortcode 

= Version 2.8 =
- Updating the LinkedIn authorization URLs to latest version 

= Version 2.7 =
- Updated Spanish and Catalan translations
- Added Russian translations
- Added an option that enables network admins on multi-sites installations to choose to redirect each site individually instead of going through the network when connecting with LinkedIn. When that option is activated the network is hidden to the user but each site in the network that is using the plugin must have their own redirect_uri registered with the LinkedIn API

= Version 2.6.1 =
- Fixing a bug in the parsing of shortcodes attributes

= Version 2.6 =
- Added the `[li_profile_field]` shortcode
- Template tags function now accept query style arguments or arrays. For example you can call `wp_linkedin_picture("width=80&height=80");` or `wp_linkedin_picture(array('width' => 80, 'height' => 80));`
- Removed the Javascript LinkedIn profile widget

= Version 2.5.4 =
- Forcing network activation on multi-site installs

= Version 2.5.3 =
- Adding a comment in the updater's code to indicate it's only used by this plugin's extensions in order to comply with the WordPress.org repository Plugin Guidelines

= Version 2.5.2 =
- Fixing clear cache mechanism

= Version 2.5.1 =
- Some admin notices dismissibles and some marked as warnings instead of errors
- Added the extensions bundle to the list of available extensions

= Version 2.5 =
- Small enhancements in error handling
- Better cache system

= Version 2.4.2 =
- Fixing a bug that prevent checkbox options on the network options page to be saved when unchecked

= Version 2.4.1 =
- Using HTTPS to talk with the update server
- Commenting a line of debug code that impacts performances

= Version 2.4 =
- Adding WordPress Multisite compatibility

= Version 2.3.2 =
- Fixing extensions updates do not appear when the license in not valid

= Version 2.3.1 =
- Fixing extensions page not showing when there's no extension

= Version 2.3 =
- Better admin notices
- Improved framework for extensions and license management
- Improved options page, now with tabs
- Updated all translations

= Version 2.2.1 =
- Updating pot file
- Updating Portuguese and French translations
- Fixing a missing call to gettext

= Version 2.2 =
- Better error management and added flexibility to the authentication process

= Version 2.1.2 =
- Updating instructions to create the LinkedIn API application to match the changes on their side

= Version 2.1.1 =
- Changes for WordPress 4.3
- Fixing labels for Client ID and Client Secret in settings page
- Updating pot file and French translations

= Version 2.1 =
- Removed everything related to network updates
- Changed some defaults
- Moved most of the profile templates to the Advanced Templates plugin and left only what's necessary for the basic profile fields

= Version 2.0.2 =
- CSS fix for some cases when blockquotes have horizontal margins in the scroller
- Template fix to accomodate a bug whereby content see `<br/>` tags  introduced in the middle of HTML `<img>` tags containing line breaks

= Version 2.0.1 =
- Adding some missing `esc_url` in the templates.
- Updating Portuguese translations

= Version 2.0 =
- Updated to follow the restrictions on the API imposed by LinkedIn starting on May 12, 2015.[More information on vedovini.net](http://vdvn.me/p2lg)

= Version 1.19 =
- As an option you can now put your customized templates in a `linkedin` folder under the `wp-content` folder instead of your theme's folder. No need to create a child theme anymore or have the templates overwritten when you update your theme.
- Updated Portuguese translations.

= Version 1.18.3 =
- Fixing network updates templates that didn't show "Likes" properly.

= Version 1.18.2 =
- Updated Spanish and Catalan translations.

= Version 1.18.1 =
- Loading the text domain earlier in the plugin loading process so that translations for widget names are available.
- Updating Portuguese and French translations.
- Now working when fancy permalinks are not activated.

= Version 1.18 =
- Stripped down the jQuery Tools library to the scroller only to avoid clashes with other jQuery plugins and reduce footprint.
- Added support for honors & awards section to the profile template. If you want to activate it then make sure to add the following fields to the list of fields: `honors-awards:(name,issuer,date,description)`
- Added support for the `LI_DEBUG` variable. Set that define to `true` in the `wp-config.php` file and debug information will be printed by the plugin as HTML comments (especially usefull when debugging customized templates).
- Added the `li_picture` shortcode that enables you to print the original profile picture.
- Changed the uri that gets the access token from the API.

= Version 1.17.2 =
- Change in the way urls are built to make sure the correct separator for query parameters is always used.

= Version 1.17.1 =
- Some changes to the profile templates to remove unwelcomed warnings.

= Version 1.17 =
- Added support for publications section in the profile template. If you want to add that section to your output make sure to add the following fields to the list of fields: `publications:(title,publisher,authors,date,url,summary)`.
- Separated the profile template into several different templates to simplify customisation. Now if you just need to customize one section of the profile then you will only need to create a custom template for that section.

= Version 1.16 =
- Added Portuguese translations.
- Corrected internationalization bugs in class-admin.php

= Version 1.15 =
- Added Serbian translations.

= Version 1.14 =
- LinkedIn now requires that redirect uris be registered, thus forcing every user of the plugin to create their own application key/secret pair and register their redirect uri.

= Version 1.13.1 =
- Fixing display of a project's team members when they are not LinkedIn users or they don't have a public profile.
- Fixing doc about the list of field to display projects.

= Version 1.13 =
- Added support for projects section in the profile template. If you want to add that section to your output make sure to add the following fields to the list of fields: `projects:(name,url,start-date,end-date,members:(name,person:(public-profile-url, first-name,last-name,picture-url,headline)),description)`.

= Version 1.12.2 =
- Use options API directly instead of the transient API to avoid issues with object cache.
- Defaulting to single-byte API when multi-byte strings support is not enabled.

= Version 1.12.1 =
- Fixed version numbers.

= Version 1.12 =
- Added Catalan translations.

= Version 1.11.2 =
- Fixed a bug in the `Network Updates` template that was messing up the update text.

= Version 1.11.1 =
- Using `esc_url` in templates when printing links.
- Using `nl2br` instead of `wpautop` when printing recommendations text.

= Version 1.11 =
- Added 30mn caching for network updates to avoid API throttling.
- Small fixes.
- Added Spanish translations.

= Version 1.10 =
- Bundled jQueryTools within the plugin instead of using jQueryTools CDN.

= Version 1.9 =
- Fixed some translation issues.
- Fixed the default value for the list of post types to put the card on.
- Simplified the CSS and template for the LinkedIn card.
- Updated Dutch translations.

= Version 1.8 =
- Added a setting to let you choose on which post types your LinkedIn card will be inserted (before it was only inserted on posts).
- Fallback mechanism to load language files in a smarter way.

= Version 1.7 =
- Added links for volunteer translators.
- Added screenshots.
- Added language proficiencies to the profile template, use `languages:(language,proficiency)` in the list of fields to activate it.
- Dutch translations updated.
- Changed the way extensions can hook to the plugin.
- Removed the `jquery.dimensions.etc` plugin.
- Added Italian translations.
- Added Brazilian Portuguese translations.
- Fixed an issue where the recommendation slider won't work when the jQuery library is included in the footer of the blog.

= Version 1.6 =
- Updated Dutch translations.
- Using `wpautop` instead of `nl2br` in templates.
- Added template loading function with debug output of the template path. You must have `WP_DEBUG` set to `true` to see what template file is used. The file path will be printed inside an HTML comment just before the template output.
- Error messages are now visible only when `WP_DEBUG` set to `true` otherwise they are printed as HTML comments.
- Ability to add your LinkedIn card after each of your posts.
- Added hook `linkedin_oauthtoken` to enable extensions to override the LinkedIn API oauth token.
- Added hook `linkedin_template` to enable extensions to override the template to be used.
- Made the scroller truly responsive when width is set to `auto`.
- Put the scripts in the footer and moved calls to `wp_enqueue_script` to templates for more flexibility.
- Added network updates shortcode and widget.

= Version 1.5.5 =
- Bug fix: Invalid url to refresh token in token expiry alert email.

= Version 1.5.4 =
- Better error reporting and specifying ssl_verify parameter when fetching profile too.

= Version 1.5.3 =
- Fixing wrong name of parameter for wp_remote_get when exchanging code for token m(

= Version 1.5.2 =
- Tweaking templates and CSS
- Option to disable SSL verification (on some servers the proper ssl certificates are not installed thus preventing SSL verification).
- Option to have the plugin send an email when the access token becomes invalid or expires.

= Version 1.5.1 =
- Improved error handling when updating oauth token.
- Using another set of APP key/secret when `WP_DEBUG` is turned on (allows for having a dev environment without the access token being invalidated each time you switch).
- Allowing to override the APP key and secret by defining `WP_LINKEDIN_APPKEY` and `WP_LINKEDIN_APPSECRET` in `wp-config.php`.
- Added a profile widget using the LinkedIn JavaScript API.
- Changed the `readme.txt` file to move some details from the "Installation" page to the "Description" page.
- Changed from using `pre-wrap` in the stylesheet to using `nl2br` in templates in order to better preserve the text formatting.

= Version 1.5 =
- Changing the way the LinkedIn API keys and token are managed in order to simplify installation.
- Added a profile cache to improve performances and limit API calls.

= Version 1.4.3 =
- Updating string translations.

= Version 1.4.2 =
- Nice looking option page with donate button and Twitter widget.

= Version 1.4.1 =
- Fixing language codes in settings.
- Simplifying the javascript for the recommendation slider.

= Version 1.4 =
- Corrected link to post about customization in the readme.
- Modified the javascript for the recommendation slider so that it uses `$(document).ready()`.
- Added a widget and shortcode displaying a simple LinkedIn card.

= Version 1.3.8 =
- Corrected a bug that interfers with other plugins using output buffering.
- Updated French and Dutch localizations.

= Version 1.3.7 =
- Added a 'css' option for the widget width to disable setting the width using javascript. This allows to set the width using CSS, which is particularly useful with responsive themes.
- Added a link to the post on vedovini.net about customizing the plugin's output.

= Version 1.3.6 =
- Fixing how the presence of data is tested and adding error messages when the profile cannot be retrieved.

= Version 1.3.5 =
- Adding French and Dutch translations (Credit to Jan Spoelstra for the Dutch translations).
- Fixing path issue while laoding the text domain.
- Added credit section to the readme file.
- Changed name of bundled classes to avoid name colisions.

= Version 1.3.4 =
- Enable an 'auto' mode for the width of the recommendations in order to accomodate responsive themes. However, it won't work in some occasion where the width of the parent cannot be calculated. To activate it just use 'auto' as the recommendations width.

= Version 1.3.3 =
- Updating the css version.

= Version 1.3.2 =
- Forcing `clear: none` on recommendations `blockquote` otherwise the scroller might not work.
- Adding support for linking to recommender's profiles in the template and adding the fields in the default list of fields. If you want to add that to your output make sure to change the `recommendations-recieved` field to `recommendations-received:(recommendation-text,recommender:(first-name,last-name,public-profile-url))`.

= Version 1.3.1 =
- Upgrading the widget to use WP_Widget class, enabling several widgets instances.
- Modified the script and CSS to be more respectful of theming.

= Version 1.3 =
- Added the possibility to select the profile language in the settings and the `[li_profile]` shortcode.

= Version 1.2.1 =
- Added a test to avoid PHP error when no recommendations.

= Version 1.2.0 =
- Removed some unecessary code that prevented the fetching of some profile fields.
- Moved the inclusion of the default CSS to the template to enable one to remove and totally replace it.
- Added the option to provide a `field` attribute to the `[li_profile]` shortcode to override the list from the settings and enable having several different profiles.

= Version 1.1.0 =
- Adding the `interval` attribute to the shortcode and the widget to control the scroller's speed.

= Version 1.0.2 =
- Changing version of jQuery Tools to avoid conflicting with WP's jQuery.
- Adding a sidebar widget with the recommendation slider.

= Version 1.0.1 =
- Removing left over HTML comment in recommendations template file.

= Version 1.0.0 =
- Initial release.


== Credits ==

Following is the list of people and projects who helped me with this plugin, many thanks to them :)

- [Jan Spoelstra](http://www.linkedin.com/in/janspoelstra): Contributed the Dutch translations
- [Nathalie Aynié](http://nathalieaynie.com/): Contributed the Italian translations
- [Graciela Morel Centurion](http://ar.linkedin.com/pub/graciela-morel-centurion/4/61b/b9): Contributed the Brazilian Portuguese translations
- Andrew Kurtis, from [WebHostingHub](http://www.webhostinghub.com/): Contributed the Spanish translations
- [Jaume Villar](http://www.jaumevillar.info/): Contributed the Catalan translations
- Ogi Djuraskovic, from [First Site Guide](http://firstsiteguide.com/): Contributed the Serbian translations
- [Pedro Gaspar](http://twitter.com/pedro_gaspar): Contributed the Portuguese translations and corrected internationalization bugs in class-admin.php
- [Oriol Torrillas](http://www.otorrillas.tk): Contributed Spanish and Catalan translations
- Jose Maria Bescos: Contributed Spanish, Catalan and Russian translations


== Privacy Policy ==

This plugin does not collect any personal information from your visitors. However, profile information coming from the LinkedIn API may be temporarily cached for efficiency reasons.

The period of time this information is kept in the cache is controlled by the `WP_LINKEDIN_PROFILE_CACHE_TIMEOUT` define and defaults to 12 hours. You can override it by specifying it's value, in seconds, in the `wp-config.php` file. 