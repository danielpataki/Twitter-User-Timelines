<?php

/**
 * Twitter User Timeline Class
 *
 * This class handles the authentication with Twitter and the
 * retrieval of tweets.
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
class Twitter_User_Timeline extends WP_Widget
{

    /**
     * Constructor
     *
     * The widget constructor uses the parent class constructor
     * to add the widget to WordPress, we just provide the basic
     * details
     *
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    public function __construct()
    {
        $widget_details = array(
            'classname' => 'twitter-user-timelines',
            'description' => __( 'Add an arbitrary twitter timeline or tie it to any user profile', 'twitter-user-timelines' )
        );

        parent::__construct( 'twitter-user-timelines', __( 'Twitter User Timeline', 'twitter-user-timelines' ), $widget_details );

    }

    /**
     * Widget Form
     *
     * The form shown in the admin when building the widget.
     * We make sure that the consumer key/secret has been given. if not,
     * we let the user know and the form is not shown.
     *
     * Based on the value of the widget type dropdown, some fields are
     * hidden/shown, this is handled via JS.
     *
     * @param array $instance The widget details
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    public function form( $instance ) {

        // Check consumer key and secret
        $consumer_key = get_option( 'tut_consumer_key' );
        $consumer_secret = get_option( 'tut_consumer_secret' );

        if( empty( $consumer_key ) || empty( $consumer_secret ) ) {
            ?>
            <p>
                <?php printf( __( 'Twitter only allows access to its API with authentication. This means you must create a Twitter application and define the consumer key and consumer secret of your application in the <a href="%s">plugin settings</a>', 'twitter-user-timelines' ), admin_url() . '/options-general.php?page=twitter-user-timelines-settings' ) ?>
            </p>
            <?php
        }
        else {

            // Gather values
            $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
            $theme = ( !empty( $instance['theme'] ) ) ? $instance['theme'] : 'light';
            $count = ( !empty( $instance['count'] ) ) ? $instance['count'] : 5;
            $username = ( !empty( $instance['username'] ) ) ? $instance['username'] : 'WordPress';
            $override = ( !empty( $instance['override'] ) ) ? $instance['override'] : array();
            $twitter_field = ( !empty( $instance['twitter_field'] ) ) ? $instance['twitter_field'] : '';

            // Widget type options
            $override_options = array(
                'single_post' => __( 'On Posts', 'twitter-user-timelines' ),
                'single_page' => __( 'On Pages', 'twitter-user-timelines' ),
                'author_archive' => __( 'Author Archive', 'twitter-user-timelines' ),
            );

            ?>
            <div class='twitter-user-timeline'>
                <p>
                    <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'twitter-user-timelines' ) ?> </label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
                </p>

                <p>
                    <label for="<?php echo $this->get_field_name( 'count' ); ?>"><?php _e( 'Tweets To Show:', 'twitter-user-timelines' ) ?> </label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
                </p>

                <p>
                    <label for="<?php echo $this->get_field_name( 'username' ); ?>"><?php _e( 'Default Twitter Username:', 'twitter-user-timelines' ) ?> </label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>" />
                </p>

                <p class='tut-overide'>
                    <label for="<?php echo $this->get_field_name( 'override' ); ?>"><?php _e( 'Show Author Tweets:', 'twitter-user-timelines' ) ?> </label><br>
                    <?php
                        $i=0;
                        foreach( $override_options as $value => $name ) :
                            $checked = ( in_array( $value, $instance['override'] ) ) ? 'checked="checked"' : '';
                    ?>
                        <input <?php echo $checked ?> type='checkbox' id="<?php echo $this->get_field_id( 'override' ); ?>-<?php echo $i ?>" name="<?php echo $this->get_field_name( 'override' ); ?>[]" value='<?php echo $value ?>'> <label for='<?php echo $this->get_field_id( 'override' ); ?>-<?php echo $i ?>'><?php echo $name ?> <br>
                    <?php $i++; endforeach ?>

                </p>

                <p class='tut-twitter-field hidden'>
                    <label for="<?php echo $this->get_field_name( 'twitter_field' ); ?>"><?php _e( 'Twitter Field:', 'twitter-user-timelines' ) ?> </label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'twitter_field' ); ?>" name="<?php echo $this->get_field_name( 'twitter_field' ); ?>" type="text" value="<?php echo esc_attr( $twitter_field ); ?>" />
                </p>

				<p>
                    <label for="<?php echo $this->get_field_name( 'theme' ); ?>"><?php _e( 'Theme:', 'twitter-user-timelines' ) ?> </label><br>

				    <input <?php checked( $theme, 'light' ) ?> class="widefat" id="<?php echo $this->get_field_id( 'theme' ); ?>_light" name="<?php echo $this->get_field_name( 'theme' ); ?>" type="radio" value='light' /> <label for="<?php echo $this->get_field_id( 'theme' ); ?>_light"> Light </label><br>

					<input <?php checked( $theme, 'dark' ) ?> class="widefat" id="<?php echo $this->get_field_id( 'theme' ); ?>_dark" name="<?php echo $this->get_field_name( 'theme' ); ?>" type="radio" value='dark' /> <label for="<?php echo $this->get_field_id( 'theme' ); ?>_dark"> Dark </label>

                </p>



            </div>

            <?php

        }

    }


    /**
     * Update Handling
     *
     * Before the instance is returned we retrieve the tweets and
     * store them in a transient.
     *
     * @param array $new_instance The newly saved widget values
     * @param array $old_instance The old widget values
     * @uses retrieve_tweets()
     * @return array The final widget values
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    public function update( $new_instance, $old_instance ) {
        $tweets = $this->retrieve_tweets( $new_instance['username'], $new_instance['count'] );
        delete_transient( 'tut_tweets_' . $new_instance['username'] );
        set_transient( 'tut_tweets_' . $new_instance['username'], $tweets, HOUR_IN_SECONDS );
        return $new_instance;
    }

    /**
     * Get Tweets
     *
     * Retrieves the tweets for the widget. If the transient exists
     * it will be loaded from there, otherwise it is fetched from Twitter
     *
     * @param string $username The Twitter screen name
     * @param int $count The number of tweets to get
     * @return array An array of retrieved tweets
     * @uses retrieve_tweets();
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    function get_tweets( $username, $count = 10 ) {
        $tweets = get_transient( 'tut_tweets_' . $username );
        if( empty( $tweets ) ) {
            $tweets = $this->retrieve_tweets( $username, $count );
            set_transient( 'tut_tweets_' . $username, $tweets, HOUR_IN_SECONDS );
        }

        return $tweets;

    }

    /**
     * Retrieve Tweets
     *
     * Fetch a user's Tweets from the Twitter API
     *
     * @param string $username The Twitter screen name
     * @param int $count The number of tweets to get
     * @return array An array of retrieved tweets
     * @uses Tut_Twitter
     * @uses Tut_Twitter::get_user_tweets()
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    function retrieve_tweets( $username, $count = 10 ) {
        $consumer_key = get_option( 'tut_consumer_key' );
        $consumer_secret = get_option( 'tut_consumer_secret' );
        $twitter = new Tut_Twitter( $consumer_key, $consumer_secret );
        $tweets = $twitter->get_user_tweets( $username, $count );
        return $tweets;
    }

    /**
     * Front End Output
     *
     * Handles the visitor-facing side of the widget. We enqueue our
     * assets here to make sure they only load when needed. The tweets
     * are retrieved and then displayed according to Twitter's specs.
     *
     * @param array $args The widget area details
     * @param array $instance The widget details
     * @link https://dev.twitter.com/overview/terms/display-requirements
     * @uses get_tweets()
     * @uses format_tweet_text()
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    public function widget( $args, $instance ) {

        // Enqueue assets
		wp_enqueue_script( 'twitter-widgets' );
		wp_enqueue_style( 'tut-style' );

        // Determine whose timeline is shown
        $screen_name = $this->determine_screen_name( $instance );

        // Get timeline
        $tweets = $this->get_tweets( $screen_name, $instance['count'] );

        // Before widget and title
        echo $args['before_widget'];

        if( !empty( $instance['title'] ) ) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        // Follow link
    	echo '<a class="tut-follow-link" href="https://twitter.com/intent/follow?screen_name=' . $tweets[0]['user']['screen_name'] . '"><span class="tut-twitter-icon"></span> follow @' . $tweets[0]['user']['screen_name'] . '</a>';

        // Start the tweet list
        echo '<ul class="tut-tweets tut-theme-' . $instance['theme'] . '">';
        foreach( $tweets as $tweet ) {

        // Individual tweets
		?>
			<li class='tut-tweet tut-screen-name-<?php echo $tweet['user']['screen_name'] ?>' id='tut-tweet-<?php echo $tweet['id'] ?>'>
				<header>
					<a href='http://twitter.com/<?php echo $tweet['user']['screen_name'] ?>'><img class='tut-profile-image' src='<?php echo $tweet['user']['profile_image_url'] ?>'></a>
					<div class='tut-user'>
						<div class='tut-user-name'><a href='http://twitter.com/<?php echo $tweet['user']['screen_name'] ?>'><?php echo $tweet['user']['name'] ?></a></div>
						<div class='tut-screen-name'><a href='http://twitter.com/<?php echo $tweet['user']['screen_name'] ?>'>@<?php echo $tweet['user']['screen_name'] ?></a></div>
					</div>
				</header>
				<div class='tut-text'><?php echo $this->format_tweet_text( $tweet ) ?></div>

				<footer>
					<div class='tut-time'><a href='http://twitter.com/<?php echo $tweet['user']['screen_name'] ?>/status/<?php echo $tweet['id'] ?>'><?php echo date( 'd F, Y', strtotime( $tweet['created_at'] ) ) ?></a></div>

					<div class='tut-actions'>
						<a class='tut-reply' href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $tweet['id'] ?>"></a>
						<a class='tut-retweet' href="https://twitter.com/intent/retweet?tweet_id=<?php echo $tweet['id'] ?>"></a>
						<a class='tut-favorite' href="https://twitter.com/intent/favorite?tweet_id=<?php echo $tweet['id'] ?>"></a>
					</div>
				</footer>
			</li>
		<?php

        // End tweet list
        }
        echo '</ul>';


        echo $args['after_widget'];
    }

    /**
     * Determine Twitter Screen Name
     * 
     * Determines the Twitter user to show tweets for. This is a
     * factor of the given default username, the overrides and
     * the type of page we are currently on .
     * 
     * @param array $instance The widget details
     * @return string Twitter screen name
     * @author Daniel Pataki
     * @since 1.0.0
     * 
     */
    function determine_screen_name( $instance ) {
        $screen_name = empty( $instance['username'] ) ? 'WordPress' : $instance['username'];

        if( is_author() && in_array( 'author_archive', $instance['override'] ) ) {
            $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
            $user_screen_name = get_user_meta( $author->ID, $instance['twitter_field'], true );
            if( !empty( $user_screen_name ) ) {
                $screen_name = $user_screen_name;
            }
        }

        if( is_single() && in_array( 'single_post', $instance['override'] ) ) {
            global $post;
            $user_screen_name = get_user_meta( $post->post_author, $instance['twitter_field'], true );
            if( !empty( $user_screen_name ) ) {
                $screen_name = $user_screen_name;
            }
        }

        if( is_page() && in_array( 'single_page', $instance['override'] ) ) {
            global $post;
            $user_screen_name = get_user_meta( $post->post_author, $instance['twitter_field'], true );
            if( !empty( $user_screen_name ) ) {
                $screen_name = $user_screen_name;
            }
        }


        return $screen_name;
    }

    /**
     * Format Tweet Text
     *
     * Makes sure that all links, mentions and hashtags are linked
     * properly.
     *
     * @param array $tweet Single tweet details
     * @return string Formatted tweet text
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
	function format_tweet_text( $tweet ) {
		$text = $tweet['text'];

		foreach( $tweet['entities']['urls'] as $url ) {
			$tweet['text'] = str_replace( $url['url'], '<a href="' . $url['url'] . '">' . $url['url'] . '</a>', $tweet['text'] );
		}

		foreach( $tweet['entities']['user_mentions'] as $mention ) {
			$tweet['text'] = str_replace( '@' . $mention['screen_name'], '<a href="http://twitter.com/' . $mention['screen_name'] . '">@' . $mention['screen_name'] . '</a>', $tweet['text'] );
		}

		foreach( $tweet['entities']['hashtags'] as $hashtag ) {
			$tweet['text'] = str_replace( '#' . $hashtag['text'], '<a href="https://twitter.com/hashtag/' . $hashtag['text'] . '">#' . $hashtag['text'] . '</a>', $tweet['text'] );
		}

		return $tweet['text'];
	}

}


?>
