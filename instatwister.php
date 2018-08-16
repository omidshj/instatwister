<?php
/**
 * @package itbc
 * @version 1.0
 */
/*
Plugin Name: instatwister
Plugin URI: https://wordpress.org/plugins/hello-dolly/
Description: a custom plugin for sanop for sale software. develop by hooraweb.
Author: hooraweb
Version: 1.0
Author URI: http://hooraweb.com
Text Domain: Sanop

*/

// add_action('woocommerce_checkout_process', 'is_phone');
//
// function is_phone() {
//     // $phone_number = $_POST['---your-phone-field-name---'];
//     // your function's body above, and if error, call this wc_add_notice
//     wc_add_notice( __( 'Your phone number is wrong.' ), 'error' );
// }

include ('setting.php');
include ('orders.php');
include ('report-cron.php');
include ('stat-cron.php');


function instatwister_payment_complete( $order_id ) {
  $setting = get_option('instatwister_setting');

  $order = new WC_Order( $order_id );
  $order_data = $order->get_data();
  // print_r($order);
  // die();
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

  // $items = $order->get_items();
  // $products = array();
	// foreach ($items as $key => $item) {
	// 	$item_data = $items[$key]->get_data();
	// 	$product = wc_get_product( $item_data['product_id'] );
	// 	$quantity = $item_data['quantity'];
	// 	$test_tracking_codes = woocommerce_get_product_terms($product->id, 'pa_test_tracking_code');
	// 	if($test_tracking_codes){
  //     global $wpdb;
	// 		$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'itbc WHERE order_id = '.$order_id.' and product_id = '. $product->id, OBJECT );
	// 		$quantity -= count($results);
	// 		$wpdb->query(
	// 			$wpdb->prepare(
	// 				"update wp_itbc set order_id = %d, product_id = %d WHERE order_id IS NULL AND product_id IS NULL LIMIT %d",
	// 				$order_id,
	// 				$product->id,
	// 				$quantity
	// 			)
	// 		);
	// 	}
	// }
}
// woocommerce_review_order_after_submit
// woocommerce_payment_complete
add_action( 'woocommerce_order_status_changed', 'instatwister_payment_complete', 10, 1 );

function instatwister_activation() {
  // $txt = "instatwister actived";
  // $myfile = file_put_contents('../wp-content/plugins/instatwister/readme.md', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
  // $txt = "instatwister";
  // $myfile = file_put_contents('readme.md', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

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
    status varchar(255) NULL,
    server_order_id varchar(255) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );


  $txt = "instatwister actived crons";
  $myfile = file_put_contents('readme.md', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

  if (! wp_next_scheduled ( 'instatwister_report_crons_event' ))
    wp_schedule_event(time(), 'seven', 'instatwister_report_crons_event');
  if (! wp_next_scheduled ( 'instatwister_stat_crons_event' ))
    wp_schedule_event(time(), 'seven', 'instatwister_stat_crons_event');

}
register_activation_hook( __FILE__, 'instatwister_activation' );

function instatwister_deactivation() {
	wp_clear_scheduled_hook('instatwister_report_crons_event');
	wp_clear_scheduled_hook('instatwister_stat_crons_event');
}
register_deactivation_hook(__FILE__, 'instatwister_deactivation');

// function instatwister_crons() {
//
//
// }
// register_activation_hook(__FILE__, 'instatwister_crons');

function instatwister_seven_min( $schedules ) {
	// add a 'weekly' schedule to the existing set
	$schedules['seven'] = array(
		'interval' => 7, //420,
		'display' => __('Seven Minutes')
	);
	return $schedules;

}
add_filter( 'cron_schedules', 'instatwister_seven_min' );

?>
