<?php
// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete all transients we created
$sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
        FROM  $wpdb->options
        WHERE `option_name` LIKE '_transient_tut_tweets_%'
        ORDER BY `option_name`";

$transients = $wpdb->get_results( $sql );

if( !empty( $transients ) ) {
    foreach( $transients as $transient ) {
        if( !empty( $transient->name ) ) {
            $name = str_replace( '_transient_', '', $transient->name );
            delete_transient( $name );
        }
    }
}

// Remove our options
delete_option( 'tut_consumer_key' );
delete_option( 'tut_consumer_secret' );
