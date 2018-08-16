<?php
function instatwister_stat_crons() {
  // $txt = "user id date";
  // $myfile = file_put_contents('readme.md', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);

  // global $wpdb;
  // $wpdb->insert(
  //   $wpdb->prefix.'instatwister',
  //   array(
  //     'order_id' => 111,
  //     'product_id' => 222,
  //     'link' => '333',
  //     'server' => '444',
  //     'api' =>  '555',
  //     'service_id' =>  '666',
  //     'count' =>  777,
  //     'status' => 'waiting',
  //   )
  // );
}
add_action('instatwister_stat_crons_event', 'instatwister_stat_crons');
?>
