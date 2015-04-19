# Twitter User Timelines WordPress Plugin

Add Twitter timelines to your widget areas. It can detect the current author on archive and single pages and show their tweets only.

## Description

Twitter User Timelines is a plugin that tries to do Twitter feeds right. Instead of the inflexible Twitter widget I built the whole thing using the REST API. This allows me to use regular ol' HTML and CSS to style everything. It gives **you** a lot of power since you can override the default look in any way you like.

By default the plugin follows the [Twitter Display Guidelines](https://dev.twitter.com/overview/terms/display-requirements). I don't really agree with some of their guidelines so the CSS/HTML allows for any changes you may need.

#### Thanks

* [David Marcu](https://unsplash.com/davidmarcu) for the wonderful photo for the plugin's featured image

#### Translations

The plugin is currently available in English and Hungarian. Please feel free to submit any new languages via a pull request, I'd be mighty thankful.

#### Useful Links

This Github repository is for the development of this plugin. If you would like to read installation and in-depth usage instructions you might want to look at the WordPress plugin page instead.

* [Plugin Page](https://wordpress.org/plugins/twitter-user-timelines/)
* [SVN Repository](http://plugins.svn.wordpress.org/twitter-user-timelines/)
* [Twitter Display Requirements](https://dev.twitter.com/overview/terms/display-requirements)
* [Twitter REST API](https://dev.twitter.com/rest/public)


## Setting Up

To be able to use the plugin you will need to create a [Twitter application](https://apps.twitter.com/). You'll need the consumer key and the consumer secret it generates, paste it into the Twitter Timelines settings.

## Usage

Usage is pretty self-explanatory, if you bump into something you don't understand just head on over to the [Plugin Page](https://wordpress.org/plugins/twitter-user-timelines/). The "Twitter Field" setting is the only one which may cause some confusion.

### The Twitter Field

The Twitter field is a special setting in the widget which is needed to show the author's Tweets. The plugin of course doesn't know what an author's Twitter name is and there is no default WordPress setting to add this information.

Using the "Twitter Field" users can tell the plugin which usermeta field it should search for. You can add this using your own meta box or you can use [ACF](https://wordpress.org/plugins/advanced-custom-fields/)

## HTML Structure

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



# Want To Help?

If you like the plugin and you like helping others out there are a few things you can do:

* **[Review the plugin](https://wordpress.org/support/view/plugin-reviews/twitter-user-timelines)**: A good review never hurts and constructive criticism makes products better :)
* **Submit a translation** If you speak another language goodly you can submit a language file, I'd be mighty thankful! Take a look at the lang directory to see what languages we already have. If a language isn't there create one and submit a pull request. If you have no idea what I'm talking about drop me a line and I'll help you out
* **[Tip me on Gratipay](https://gratipay.com/danielpataki/)**
