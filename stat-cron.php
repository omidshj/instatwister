<?php
require_once ("api.php");
function instatwister_stat_crons() {
  global $wpdb;
  $orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}instatwister WHERE status = 1 LIMIT 7", OBJECT );
  foreach ($orders as $order) {
    if ($order->api == 'jap') {
      japApiStatus($order);
    }
  }
}
add_action('instatwister_stat_crons_event', 'instatwister_stat_crons');
?>
