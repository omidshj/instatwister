<?php
function instatwister_orders(){
  if( isset($_POST['register'])){
    if (wp_next_scheduled('instatwister_report_crons_event')){
      wp_clear_scheduled_hook('instatwister_report_crons_event');
      wp_schedule_event(time(), 'seven', 'instatwister_report_crons_event');
    }
    instatwister_report_crons();
  }
  if( isset($_POST['resetregister'])){
    wp_clear_scheduled_hook('instatwister_report_crons_event');
    wp_schedule_event(time(), 'seven', 'instatwister_report_crons_event');
  }
  if( isset($_POST['clearregister'])){
    wp_clear_scheduled_hook('instatwister_report_crons_event');
  }
  if( isset($_POST['stat'])){
    if (wp_next_scheduled('instatwister_stat_crons_event')){
      wp_clear_scheduled_hook('instatwister_stat_crons_event');
      wp_schedule_event(time(), 'seven', 'instatwister_stat_crons_event');
    }
    instatwister_stat_crons();
  }
  if( isset($_POST['resetstat'])){
    wp_clear_scheduled_hook('instatwister_stat_crons_event');
    wp_schedule_event(time(), 'seven', 'instatwister_stat_crons_event');
  }
  if( isset($_POST['clearstat'])){
    wp_clear_scheduled_hook('instatwister_stat_crons_event');
  }

  global $wpdb;
  $posts_per_page = 20;
  $paged = isset($_GET['paged'])? $_GET['paged']: 1;
  $start = ($paged-1)*$posts_per_page;
  $orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}instatwister", OBJECT );
  $total_order = ceil( $wpdb->num_rows / $posts_per_page);
  $orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}instatwister limit $start, $posts_per_page", OBJECT );
  ?>
  <style media="screen">
    .pend, .danger, .success{ padding: .2em .5em; border-radius: 3px; border: 1px solid #bbb; }
    .pend{background-color: #fff176;}
    .danger{background-color: #E57373;}
    .success{background-color: #81C784;}
  </style>
  <div class="wrap">
    <div class="tablenav top">
      <div class="alignleft actions">
        <form method="POST">
          <?php if (wp_next_scheduled('instatwister_report_crons_event')):
            $t = wp_next_scheduled('instatwister_report_crons_event') - time();
            if ($t < 1) $t = 427; else $t += 7;
            ?>
            <input type="Submit" value="اجرا ثبت (<?= $t ?>)" name="register" class="button button-primary button-large" time="<?= $t ?>">
            <input type="Submit" value="حذف کرون ثبت" name="clearregister" class="button button-primary button-large">
          <?php else: ?>
            <input type="Submit" value="اجرا ثبت" name="register" class="button button-primary button-large">
            <input type="Submit" value="زمان بندی ثبت" name="resetregister" class="button button-primary button-large">
          <?php endif; ?>
          <?php if (wp_next_scheduled('instatwister_stat_crons_event')):
            $t = wp_next_scheduled('instatwister_stat_crons_event') - time();
            if ($t < 1) $t = 427; else $t += 7;
            ?>
            <input type="Submit" value = "اجرا گزارش گیری (<?= $t ?>)" name="stat" class="button button-primary button-large" time="<?= $t ?>">
            <input type="Submit" value = "حذف کرون گزارش گیری" name="clearstat" class="button button-primary button-large">
          <?php else: ?>
            <input type="Submit" value = "اجرا گزارش گیری" name="stat" class="button button-primary button-large">
            <input type="Submit" value = "زمان بندی گزارش گیری" name="resetstat" class="button button-primary button-large">
          <?php endif; ?>
        </form>
      </div>
      <div class="tablenav-pages">
        <?php
        if( $total_order > 1 )  {
          $format = get_option('permalink_structure')? 'page/%#%/': '&paged=%#%';
          echo paginate_links(array(
            'base'          => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'        => $format,
            'current'       => $paged,
            'total'         => $total_order,
            'mid_size'      => 2,
            'prev_text'     => is_rtl()? '&rarr;': '&larr;',
            'next_text'     => is_rtl()? '&larr;': '&rarr;',
           ));
        }
        ?>
      </div>
    </div>
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
            <?= $order->id ?> -
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
            ای پی آی: <?= $order->api? : '---' ?>
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
            وضعیت: <?= array(
                      '<span class="danger">ارسال نشده</span>',
                      '<span class="pend">ثبت شده</span>',
                      '<span class="success">موفق</span>'
                    )[$order->status] ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
  <script type="text/javascript">
    jQuery(document).ready(function(){
      var times = jQuery("input[time]");
      if (times.length) {
        setInterval(function(){
          times.each(function(){
            // var t = parseInt(jQuery(this).attr('time')) - 1;
            jQuery(this).attr('time', parseInt(jQuery(this).attr('time')) - 1 )
            // console.log(t);
            if (jQuery(this).attr('time') < 0) location.reload();
            // jQuery(this).attr('time', t)
            jQuery(this).attr('value', 'اجرا ثبت (' + jQuery(this).attr('time') + ')');
          });
          // alert(register.attr('t'));
        }, 1000)
      }
    });
  </script>
  <?php
}

function instatwister_order_menu() {
  add_submenu_page( 'woocommerce', 'سفارشات instatwister', 'سفارشات instatwister', 'edit_posts', 'instatwister_orders', 'instatwister_orders' );
}
add_action('admin_menu', 'instatwister_order_menu');
?>
