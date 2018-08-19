<?php
function instatwister_orders(){
  if( isset($_POST['fire'])){
    wp_clear_scheduled_hook('instatwister_report_crons_event');
    instatwister_report_crons();
    wp_schedule_event(time(), 'seven', 'instatwister_report_crons_event');
  }
  if( isset($_POST['reset'])){
    wp_clear_scheduled_hook('instatwister_report_crons_event');
    wp_schedule_event(time(), 'seven', 'instatwister_report_crons_event');
  }
  if( isset($_POST['clear'])){
    wp_clear_scheduled_hook('instatwister_report_crons_event');
  }
  global $wpdb;
  $orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}instatwister", OBJECT );
  ?>
  <br><br>




  <form method="POST">
    <input type="Submit" value="اجرا" name="fire" class="button button-primary button-large">
    <input type="Submit" value="زمان بندی" name="reset" class="button button-primary button-large">
    <input type="Submit" value="حذف کرون" name="clear" class="button button-primary button-large">
    <?php if (wp_next_scheduled('instatwister_report_crons_event')): ?>
      <?= wp_next_scheduled('instatwister_report_crons_event') - time() ?> ثانیه مانده به ثبت بعدی
    <?php else: ?>
      زمانبندی نشده

    <?php endif; ?>
  </form>
  <br><br>
  <table class="wp-list-table widefat fixed striped ">
    <tr>
      <th>سفارش ووکامرس</th>
      <th>سرور و توکن</th>
      <th>ای پی آی</th>
      <th>تعداد</th>
      <th>وضعیت</th>
    </tr>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td>
          <a href="/wp-admin/post.php?post=<?= $order->order_id ?>&action=edit">( <?= $order->order_id ?> )</a>
          -
          <a href="<?= get_permalink($order->product_id) ?>"><?= get_the_title($order->product_id) ?></a>
        </td>
        <td>
          سرور: <?= $order->server? : '---' ?>
          <br>
          توکن: <?= $order->token? : '---' ?>
        </td>
        <td>
          api: <?= $order->api? : '---' ?>
          <br>
          سرویس: <?= $order->service_id? : '---' ?>
        </td>
        <td>
          تعداد: <?= $order->count? : '---' ?>
          <br>
          لینک: <?= $order->link? : '---' ?>
        </td>
        <td>
          سفارش: <?= $order->server_order_id? : '---' ?>
          <br>
          وضعیت: <?= $order->status? : '---' ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  <?php
}

function instatwister_order_menu() {
  add_submenu_page( 'woocommerce', 'instatwister setting', 'instatwister orders', 'edit_posts', 'instatwister_orders', 'instatwister_orders' );
}
add_action('admin_menu', 'instatwister_order_menu');
?>
