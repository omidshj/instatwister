<?php
function instatwister_order_menu() {
  add_submenu_page( 'woocommerce', 'instatwister setting', 'instatwister orders', 'edit_posts', 'instatwister_orders', 'instatwister_orders' );
}
add_action('admin_menu', 'instatwister_order_menu');
function instatwister_orders(){
  global $wpdb;
  $orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}instatwister", OBJECT );
  // print_r ($orders);
  // echo 'ccccccccc';
  ?>
  <br><br>
  <table class="wp-list-table widefat fixed striped ">
    <tr>
      <th>سفارش ووکامرس</th>
      <th>محصول</th>
      <th>لینک</th>
      <th>سرور</th>
      <th>ای پی آی</th>
      <th>سرویس</th>
      <th>تعداد</th>
      <th>وضعیت</th>
      <th>سفارش سرور</th>
    </tr>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td><a href="/wp-admin/post.php?post=<?= $order->order_id ?>&action=edit"><?= $order->order_id ?></a></td>
        <td><a href="<?= get_permalink($order->product_id) ?>"><?= get_the_title($order->product_id) ?></a></td>
        <td><?= $order->link ?></td>
        <td><?= $order->server ?></td>
        <td><?= $order->api ?></td>
        <td><?= $order->service_id ?></td>
        <td><?= $order->count ?></td>
        <td><?= $order->status ?></td>
        <td><?= $order->server_order_id ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <?php
}
?>
