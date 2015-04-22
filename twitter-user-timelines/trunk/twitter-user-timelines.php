<?php
/*
Plugin Name:       Twitter User Timelines
Description:       A flexiblbe widget that allows you to show an arbitrary Twitter timeline. It can also be tied to user profiles to show Twitter timelines for each of your users on author archive pages.
Version:           1.0.4
Author:            Daniel Pataki
Author URI:        http://danielpataki.com/
License:           GPLv2 or later
*/


// Include classes
include( 'class-tut-twitter.php' );
include( 'class-twitter-user-timeline.php' );

add_action('plugins_loaded', 'tut_load_textdomain');
/**
 * Load Text Domain
 *
 * Loads the textdomain for translations
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
function tut_load_textdomain() {
	load_plugin_textdomain( 'twitter-user-timelines', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}


add_action( 'wp_enqueue_scripts', 'tut_frontend_assets' );
/**
 * Front End Assets
 *
 * Enqueues the required assets for the plugin. This includes the Twitter
 * widgets JS file and the appropriate stylesheet. If a stylesheet with the
 * name of the current theme's folder exists it is loaded. If not, the
 * default stylesheet is used.
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
function tut_frontend_assets() {
    // Get theme directory name
	$theme = basename( get_template_directory() );

    // Determine which stylesheet to use
	$style = ( file_exists( plugin_dir_path( __FILE__ ) . 'styles/' . $theme . '.css' ) ) ? plugin_dir_url( __FILE__ ) . 'styles/' . $theme . '.css' : plugin_dir_url( __FILE__ ) . 'styles/default.css';

    // Twitter Widget
	wp_register_script( 'twitter-widgets', '//platform.twitter.com/widgets.js', array(), '1.0.0', true );

    // Styles
	wp_register_style( 'tut-style', $style );
}


add_action('admin_menu', 'tut_settings_page');
/**
 * Add Setting Page
 *
 * Adds the settings page which contains the fields for the consumer
 * key and secret. Also initializes the settings that hold these
 * values.
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
function tut_settings_page() {

    add_options_page( _x( 'Twitter Timelines', 'In the title tag of the page', 'twitter-user-timelines'  ), _x( 'Twitter Timelines', 'Menu title',  'twitter-user-timelines' ), 'manage_options', 'twitter-user-timelines-settings', 'tut_settings_page_content');

    add_action( 'admin_init', 'tut_register_settings' );

}


/**
 * Register Settings
 *
 * Registers plugin-wide settings, we use this for the consumer key
 * and secret
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
function tut_register_settings() {
	register_setting( 'tut_twitter_settings', 'tut_consumer_key' );
	register_setting( 'tut_twitter_settings', 'tut_consumer_secret' );
}


 /**
 * Settings Page Content
 *
 * The UI for the settings page. It contains the form, as well as
 * a quick check to make sure the given credentials work.
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
function tut_settings_page_content() {
?>
<div class="wrap">
<h2><?php _e( 'Twitter User Timelines', 'twitter-user-timelines' ) ?></h2>

<form method="post" action="options.php">
    <?php settings_fields( 'tut_twitter_settings' ); ?>
    <?php do_settings_sections( 'tut_twitter_settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Twitter Consumer Key', 'twitter-user-timelines' ) ?></th>
        <td><input type="text" name="tut_consumer_key" value="<?php echo esc_attr( get_option('tut_consumer_key') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Twitter Consumer Secret', 'twitter-user-timelines' ) ?></th>
        <td><input type="text" name="tut_consumer_secret" value="<?php echo esc_attr( get_option('tut_consumer_secret') ); ?>" /></td>
        </tr>

    </table>

    <?php
        $consumer_key = get_option( 'tut_consumer_key' );
        $consumer_secret = get_option( 'tut_consumer_secret' );

        if( !empty( $_GET['settings-updated'] ) && !empty( $consumer_key ) && !empty( $consumer_secret ) ) {
            $twitter = new Tut_Twitter( get_option('tut_consumer_key'), get_option( 'tut_consumer_secret' ) );
            $bearer = $twitter->get_bearer_token();
            if( is_wp_error( $bearer ) ) {
                echo '<div id="tut-twitter-credential-error" class="error tut-error">
<p><strong>' . __( 'We tried to verify your consumer key and secret but it seems they are incorrect. Make sure to copy-paste them from Twitter exactly.', 'twitter-user-timelines' ) . '</strong></p></div>';
            }
        }
    ?>

    <?php submit_button(); ?>

</form>
</div>
<?php
}


add_action( 'admin_enqueue_scripts', 'tut_equeue_assets' );
/**
 * Enqueue Admin Assets
 *
 * Enqueues the assets needed in the admin. Right now this is
 * just a simple script that shows/hides fields based on the
 * value of the widget type dropdown.
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
function tut_equeue_assets($hook) {
    if ( 'widgets.php' != $hook ) {
        return;
    }

    wp_enqueue_script( 'tut_script', plugin_dir_url( __FILE__ ) . 'script.js' );
}


add_action( 'widgets_init', 'tut_widget_init' );
/**
 * Initialize Widget
 *
 * Registers the widget with WordPress
 *
 * @author Daniel Pataki
 * @since 1.0.0
 *
 */
function tut_widget_init() {
    register_widget( 'Twitter_User_Timeline' );
}
