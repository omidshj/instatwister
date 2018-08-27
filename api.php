<?php
function japApiAdd($order){
  $post = array(
    'key' => $order->token,
    'action' => 'add',
    'service' => $order->service_id,
    'link' => $order->link,
    'quantity' => $order->count,
    'server' => $order->server,
  );
  $result = japApiConnect($post);
  if ($result) {
    global $wpdb;
    $wpdb->update(
      $wpdb->prefix.'instatwister',
      array(
        'status' => 1,
        'status_desc' => $result,
        'server_order_id' => json_decode($result, true)['order']
      ),
      array(
        'id' => $order->id
      )
    );
  }
}

function japApiStatus($order){
  $post = array(
    'key' => $order->token,
    'action' => 'status',
    'order' => $order->server_order_id,
    'server' => $order->server,
  );
  $result = japApiConnect($post);
  if ($result) {
    $data = json_decode($result);
    global $wpdb;
    $wpdb->update(
      $wpdb->prefix.'instatwister',
      array(
        'status' => ($data->status == 'Completed')? 2: 1,
        'status_desc' => $result,
      ),
      array(
        'id' => $order->id
      )
    );
    if ($data->status == 'completed') {
      instatwister_ckeck_order_complete($order);
    }
  }
}

function japApiConnect($post){
  $_post = Array();
  if (is_array($post)) {
      foreach ($post as $name => $value) {
          $_post[] = $name.'='.urlencode($value);
      }
  }

  $ch = curl_init($post['server']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  if (is_array($post)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
  }
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
  $result = curl_exec($ch);
  if (curl_errno($ch) != 0 && empty($result)) {
    $result = false;
  }
  curl_close($ch);
  return $result;
}

function instatwister_ckeck_order_complete($order){
  global $wpdb;
  $orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}instatwister WHERE order_id = {$order->order_id}", OBJECT );
  $complete = true;
  foreach ($orders as $ord)
    if ($ord->status != 2)
      $complete = false;
  if ($complete) {
    $woo_order = wc_get_order( $order->order_id );
    $woo_order->update_status( 'completed' );
  }
}
?>
