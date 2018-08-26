<?php
require_once ("api.php");
function instatwister_report_crons() {
  global $wpdb;
  $orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}instatwister WHERE status = 0 LIMIT 7", OBJECT );
  foreach ($orders as $order) {
    if ($order->api == 'jap') {
      japApiAdd($order);
    }
  }

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
  //     'status' => 1,
  //   )
  // );

}
add_action('instatwister_report_crons_event', 'instatwister_report_crons');

// function instatwister_jap_add($order){
//   $post = Array(
//     'key' => $order->token,
//     'action' => 'add',
//     'service' => $order->service_id,
//     'link' => $order->link,
//     'quantity' => $order->count,
//   );
//   $_post = Array();
//   foreach ($post as $name => $value)
//     $_post[] = $name.'='.urlencode($value);
//   $ch = curl_init($order->server);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   curl_setopt($ch, CURLOPT_POST, 1);
//   curl_setopt($ch, CURLOPT_HEADER, 0);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
//   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
//   $result = curl_exec($ch);
//   if (curl_errno($ch) != 0 && empty($result)) {
//       $result = false;
//   }
//   curl_close($ch);
//   if ($result) {
//     global $wpdb;
//     $wpdb->update(
//       $wpdb->prefix.'instatwister',
//       array(
//         'status' => 1,
//         'status_desc' => $result,
//         'server_order_id' => json_decode($result)['order']
//       ),
//       array(
//         'id' => $order->id
//       )
//     );
//   }
// }

/*
class Api
{
    public $api_url = 'https://justanotherpanel.com/api/v2'; // API URL

    public $api_key = ''; // Your API key

    public function order($data) { // add order
        $post = array_merge(array('key' => $this->api_key, 'action' => 'add'), $data);
        return json_decode($this->connect($post));
    }

    public function status($order_id) { // get order status
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'status',
            'order' => $order_id
        )));
    }

    public function multiStatus($order_ids) { // get order status
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'status',
            'orders' => implode(",", (array)$order_ids)
        )));
    }

    public function services() { // get services
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'services',
        )));
    }

    public function balance() { // get balance
        return json_decode($this->connect(array(
            'key' => $this->api_key,
            'action' => 'balance',
        )));
    }


    private function connect($post) {
        $_post = Array();
        if (is_array($post)) {
            foreach ($post as $name => $value) {
                $_post[] = $name.'='.urlencode($value);
            }
        }

        $ch = curl_init($this->api_url);
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
}

// Examples

$api = new Api();

$services = $api->services(); # return all services

$balance = $api->balance(); # return user balance

// add order

$order = $api->order(array('service' => 1, 'link' => 'http://example.com/test', 'quantity' => 100)); # Default

$order = $api->order(array('service' => 1, 'link' => 'http://example.com/test', 'comments' => "good pic\ngreat photo\n:)\n;)")); # Custom Comments

$order = $api->order(array('service' => 1, 'link' => 'http://example.com/test')); # Package

$order = $api->order(array('service' => 1, 'link' => 'http://example.com/test', 'quantity' => 100, 'runs' => 10, 'interval' => 60)); # Drip-feed

$order = $api->order(array('service' => 1, 'username' => 'username', 'min' => 100, 'max' => 110, 'posts' => 0,'delay' => 30)); # Subscriptions

    $order = $api->order(array('service' => 1, 'link' => 'http://example.com/test', 'quantity' => 100, 'username' => "test")); # Comment Likes

$status = $api->status($order->order); # return status, charge, remains, start count, currency

$statuses = $api->multiStatus([1, 2, 3]); # return orders status, charge, remains, start count, currency
*/

?>
