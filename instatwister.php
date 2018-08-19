<?php
/**
 * @package instatwister
 * @version 1.0
 */
/*
Plugin Name: instatwister
Plugin URI: https://github.com/omidshj/instatwister
Description: a custom plugin for instatwister.
Author: hooraweb
Version: 1.0
Author URI: http://hooraweb.com
Text Domain: instatwister
*/

include ('setting.php');
include ('orders.php');
include ('report-cron.php');
include ('stat-cron.php');


function instatwister_payment_complete( $order_id ) {
  $setting = get_option('instatwister_setting');

  $order = new WC_Order( $order_id );
  $order_data = $order->get_data();
  $products = $order->get_items();
  foreach ($products as $product) {
    $product_data = $product->get_data();
    $product_id = $product_data['product_id'];
    if ( isset($setting[$product_id]) && isset($setting[$product_id]['active']) ){
      global $wpdb;
      $wpdb->insert(
        $wpdb->prefix.'instatwister',
        array(
          'order_id' => $order_id,
          'product_id' => $product_id,
          'link' => $order_data['billing'][ $setting['global']['link_field'] ],
          'server' => $setting[$product_id]['server'],
          'api' =>  $setting[$product_id]['api'],
          'service_id' =>  $setting[$product_id]['service_id'],
          'count' =>  $setting[$product_id]['count'],
          'status' => 'waiting',
        ),
        array(
          '%d',
          '%d',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
        )
      );
    }
  }
}
// woocommerce_review_order_after_submit
// woocommerce_payment_complete
add_action( 'woocommerce_order_status_changed', 'instatwister_payment_complete', 10, 1 );

function instatwister_activation() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'instatwister';
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
    order_id  bigint(20) NOT NULL,
    product_id  bigint(20) NOT NULL,
    link  text NULL,
		server varchar(255) NULL,
    api varchar(255) NULL,
    service_id varchar(255) NULL,
    count varchar(255) NULL,
    status bigint(20) 0,
    status_desc text NULL,
    server_order_id varchar(255) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

  // if (! wp_next_scheduled ( 'instatwister_report_crons_event' ))
  //   wp_schedule_event(time(), 'seven', 'instatwister_report_crons_event');
  // if (! wp_next_scheduled ( 'instatwister_stat_crons_event' ))
  //   wp_schedule_event(time(), 'seven', 'instatwister_stat_crons_event');
}
register_activation_hook( __FILE__, 'instatwister_activation' );

function instatwister_deactivation() {
	wp_clear_scheduled_hook('instatwister_report_crons_event');
	wp_clear_scheduled_hook('instatwister_stat_crons_event');
}
register_deactivation_hook(__FILE__, 'instatwister_deactivation');

function instatwister_seven_min( $schedules ) {
	$schedules['seven'] = array(
		'interval' => 420,
		'display' => __('Seven Minutes')
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'instatwister_seven_min' );

?>
