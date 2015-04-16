<?php

/**
 * Twitter API Class
 *
 * This class handles the communication with the Twitter API.
 * At the moment it can only verify credentials and retrieve
 * timelines but it can be extended easily.
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
class Tut_Twitter {

    /**
     * Twitter Consumer Key
     *
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    private $consumer_key;

    /**
     * Twitter Consumer Secret
     *
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    private $consumer_secret;

    /**
     * Twitter Bearer Token
     *
     * @author Daniel Pataki
     * @since 1.0.0
     */
    private $bearer_token;

    /**
     * Constructor
     *
     * Sets up the consumer key and secret properties
     *
     * @param string $consumer_key Twitter consumer key
     * @param string $consumer_secret Twitter consumer secret
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    function __construct( $consumer_key, $consumer_secret ) {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
    }

    /**
     * Get Bearer Token
     *
     * The bearer token is used to sign API requests. See Twitter's
     * Application-only authentication for more info.
     *
     * @link https://dev.twitter.com/oauth/application-only
     * @return self
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    function get_bearer_token() {

        // URL encode consumer key and secret
        $consumer_key = rawurlencode( $this->consumer_key );
        $consumer_secret = rawurlencode( $this->consumer_secret );

        // Assemble bearer credentials
        $bearer_token_credentials = base64_encode( $consumer_key . ':' . $consumer_secret );

        // Request a bearer token from Twitter
        $request = wp_remote_post('https://api.twitter.com/oauth2/token', array(
            'headers' => array(
                'Authorization' => 'Basic ' . $bearer_token_credentials,
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
            ),
            'body' => 'grant_type=client_credentials',
            'httpversion' => '1.1'
        ));

        // The bearer token
        $response = json_decode( $request['body'], true );

        // Return if unsuccessful with an error
        if( empty( $request ) || $request['response']['code'] != 200 || $response['token_type'] != 'bearer' ) {
            return new WP_Error( 'bad_credentials', __( 'Your consumer key or consumer secret is incorrect', 'twitter-user-timelines' ) );
        }

        // Set the berer token
        $this->bearer_token = $response['access_token'];
        return $this;

    }


    /**
     * Get User Tweets
     *
     * Requests a user's timeline and returns the tweets from it.
     *
     * @param string $screen_name The screen name to get tweets for
     * @param int $count The number of tweets to retrieve
     * @return array The retrieved tweets
     * @uses get_bearer_token();
     * @author Daniel Pataki
     * @since 1.0.0
     *
     */
    function get_user_tweets( $screen_name = 'WordPress', $count = 10 ) {

        // Get bearer token if not set
        if( empty( $this->bearer_token ) ) {
            $this->get_bearer_token();
        }

        // Set API request parameters
        $params = array(
            'count' => $count,
            'screen_name' => $screen_name,
            'trim_user' => false,
            'exclude_replies' => false,
            'contributor_details' => false
        );

        $params = http_build_query( $params );

        // Retrieve tweets from Twitter
        $request = wp_remote_get('https://api.twitter.com/1.1/statuses/user_timeline.json?' . $params, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->bearer_token,
            ),
        ));

        // If everything is ok return the tweets
        if( $request['response']['code'] == 200 ) {
            return json_decode( $request['body'], true );
        }

        return false;
    }

}

?>
