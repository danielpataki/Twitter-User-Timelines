=== Twitter User Timelines ===
Contributors: danielpataki
Tags: twitter, widget, social
Requires at least: 3.5.0
Tested up to: 4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Twitter streams to your widget areas. It can detect the current author on archive and single pages and show their tweets only.

== Description ==

Twitter User Timelines is a plugin that tries to do Twitter feeds right. Instead of the inflexible Twitter widget I built the whole thing using the REST API. This allows me to use regular ol' HTML and CSS to style everything. It gives **you** a lot of power since you can override the default look in any way you like.

The widget allows you to show different tweets where it makes sense. You can choose to show a post's current author's tweets for example. You can override the default Twitter user on single post, single page and author archive pages.

= Setup =

Please note that since the Twitter API requires authentication you will need to create a Twitter application to get a consumer key and secret. This is super easy, I've included instructions in the Installation section.

= Thanks =

* [David Marcu](https://unsplash.com/davidmarcu) for the wonderful photo for the plugin's featured image

== Installation ==

= Automatic Installation =

Installing this plugin automatically is the easiest option. You can install the plugin automatically by going to the plugins section in WordPress and clicking Add New. Type "Twitter User Timelines" in the search bar and install the plugin by clicking the Install Now button.

= Manual Installation =

To manually install the plugin you'll need to download the plugin to your computer and upload it to your server via FTP or another method. The plugin needs to be extracted in the `wp-content/plugins` folder. Once done you should be able to activate it as usual.

If you are having trouble, take a look at the [Managing Plugins](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation) section in the WordPress Codex, it has more information on this topic.

= Setting Up =

To be able to use the plugin you will need to create a Twitter application. This is super easy! Head on over to the [Twitter Apps](https://apps.twitter.com/) website and log in with your regular Twitter account.

Click the "Create New App" button. Type a name, a description and a Website for your app. The website should be the site you will be using the plugin on. Agree to the developer agreement and click the "Create your Twitter Application" button.

All done! In the next screen you should see four tabs, click on "Keys and Access Tokens". The first section should list your consumer key and consumer secret. You will need to paste these into the plugin settings.

The plugin settings can be found in the "Settings" section in your WordPress admin, in the "Twitter Timelines" submenu. Paste the consumer key and secret there.

= Usage =

You can add a Twitter timeline to any widget area you have. Head on over to the "Widgets" sub-section within "Appearance" in the admin. You should see a widget titled "Twitter User Timeline". Drag this into any widget area you have.

Once done you can set the widget "Title", "Tweets To Show" and "Default Twitter Username". Tweets from the default Twitter username will be shown everywhere, unless otherwise specified using the "Show Author Tweets" setting.

= Author Tweets =

The author tweets section allows you to replace the default tweets with ones specific to your authors on single posts, single pages and author archives. If you select "On Posts" for example, the plugin will detect the author of the post and show her/his tweets.

= The Twitter Field =

The Twitter field is a special setting in the widget which is needed to show the author's Tweets. The plugin of course doesn't know what an author's Twitter name is and there is no default WordPress setting to add this information.

If you have already added a Twitter field via a plugin find out which usermeta field name it uses, try asking the developer if it isn't obvious.

You can also add a custom field yourself using the awesome [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) plugin. Once installed, go to the new "Custom Fields" section in the admin and click on "Add New" up top.

Set a name for your option group (eg: User Settings) and click "+Add Field". Create a text field with the label "Twitter". Note the field name, this is what you will need to paste into the Widget's "Twitter Field" field.

Scroll down a bit until you get to the "Location" options. Under "Show this field group if" set the selector to "User". Now scroll up and save the field group. If you now go to "Users" and click on any user, you should find a Twitter field in her/his profile. Add the user's Twitter username here and the plugin will take care of the rest.

= Theme =

The theme allows you to set a theme to use, light or dark. "Light" should be used when the widget has a light background, "Dark" should be used when the widget has a dark background.


= HTML Structure =

I've tried to keep the HTML as modular as possible to make it easy for theme authors to modify the looks. The HTML structure of the Twitter feed can be generalized like this:

```
<a class='tut-follow-link' href=''></a>
<ul class='tut-tweets tut-theme-[theme]'>
    <li class='tut-tweet tut-screen-name-[screen_name]' id='tut-tweet-[tweet_id]'>
        <header>
            <a href=''><img class='tut-profile-image' src=''>
            <div class='tut-user'>
                <div class='tut-user-name'><a href=''></a></div>
                <div class='tut-screen-name'><a href=''></a></div>
            </div>
        </header>
        <div class='tut-text'></div>
        <footer>
            <div class='tut-time'><a href=''></a></div>
            <div class='tut-actions'>
                <a class='tut-reply' href=''></a>
                <a class='tut-retweet' href=''></a>
                <a class='tut-favorite' href=''></a>
            </div>
        <footer>
    </li>
</ul>
```



== Screenshots ==

1. Tweets in the Twenty Fifteen theme
2. The Widget Settings
3. Tweets in the No Nonsense theme
4. Tweets in the Twenty Fourteen theme
5. Tweets in the Hueman theme

== Changelog ==

= 1.0.8 (2015-10-09) =
* Better logic for author detection

= 1.0.7 (2015-09-08) =
* Added list handling on the backend only, frontend on the way

= 1.0.6 (2015-07-24) =
* Default twitter handle can now be empty

= 1.0.5 (2015-05-12) =
* Added widget_title filter to the widget title

= 1.0.4 (2015-04-22) =

* Fixed twitter action icons not showing up

= 1.0.3 (2015-04-21) =

* Fixed some minor bugs
* Added some missing translations

= 1.0.2 (2015-04-21) =

* WordPress 4.2 compatibility check

= 1.0.1 =

* Fixed a minor issue with overrides

= 1.0.0 =

* Initial Release.
