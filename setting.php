<?php
function instatwister_setting_menu() {
  add_submenu_page( 'woocommerce', 'instatwister setting', 'instatwister setting', 'edit_posts', 'instatwister_setting', 'instatwister_setting' );
}
add_action('admin_menu', 'instatwister_setting_menu');

function instatwister_setting(){

  if($_POST['instatwister_setting']){
    // print_r($_POST['instatwister_setting']);
    // update_option('dw_quotes', serialize($quotes));
    // $post_types = [];
    // foreach ($_POST['post_types'] as $key => $post_type)
    //   if ($post_type['slug']){
    //     $post_type['slug'] = sanitize_title($post_type['slug']);
    //     $post_types[$post_type['slug']] = $post_type;
    //   }
    update_option('instatwister_setting', $_POST['instatwister_setting'] );
  }
  $setting = get_option('instatwister_setting');
  print_r($setting);
  $products = wc_get_products();

  ?>
  <form method="POST">
    <!-- <input type="hidden" name="page" value="hooramat_setting"> -->
    <br>
    <label for="">فیلد لینک در چک اوت</label>
    <select class="" name="instatwister_setting[global][link_field]">
      <option value="first_name" <?= ($setting['global']['link_field'] == 'first_name' )? 'selected': '' ?> >first_name</option>
      <option value="last_name" <?= ($setting['global']['link_field'] == 'last_name' )? 'selected': '' ?> >last_name</option>
      <option value="company" <?= ($setting['global']['link_field'] == 'company' )? 'selected': '' ?> >company</option>
      <option value="address_2" <?= ($setting['global']['link_field'] == 'address_2' )? 'selected': '' ?> >address_2</option>
    </select>
    <input type="Submit" value="ذخیره" name="action" class="button button-primary button-large">
    <br><br>
    <table class="wp-list-table widefat fixed striped ">
      <tr>
        <th>محصول</th>
        <th>وضعیت</th>
        <th>سرور</th>
        <th>ای پی آی</th>
        <th>شماره سرویس</th>
        <th>تعداد</th>
      </tr>
      <?php foreach ($products as $product): $product_data = $product->get_data(); $product_id = $product_data['id']; ?>
        <tr>
          <td><a href="<?= get_permalink( $product_id ) ?>"><?= $product_data['name'] ?></a></td>
          <td><input type="checkbox" name="instatwister_setting[<?= $product_id ?>][active]" <?= isset($setting[$product_id]['active'])? 'checked': 'dd' ?>  ></td>
          <td><input type="text" name="instatwister_setting[<?= $product_id ?>][server]" value="<?= $setting[$product_id]['server'] ?>" placeholder="سرور"></td>
          <td>
            <select class="" name="instatwister_setting[<?= $product_id ?>][api]">
              <option value="jap" <?= (isset($setting[$product_id]['api']) && $setting[$product_id]['api'] == "jap" )? 'selected': ''  ?> >jap</option>
              <option value="ccc" <?= (isset($setting[$product_id]['api']) && $setting[$product_id]['api'] == "ccc" )? 'selected': ''  ?> >ccc</option>
            </select>
          </td>
          <td><input type="text" name="instatwister_setting[<?= $product_id ?>][service_id]" value="<?= $setting[$product_id]['service_id'] ?>" placeholder="شماره سرویس"></td>
          <td><input type="text" name="instatwister_setting[<?= $product_id ?>][count]" value="<?= $setting[$product_id]['count'] ?>" placeholder="تعداد"></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </form>
  <?php
  //
  // $args = array(
  //       'post_type'      => 'product',
  //       'posts_per_page' => 10,
  //       'product_cat'    => 'hoodies'
  //   );
  //
  //   $loop = new WP_Query( $args );
  //
  //   while ( $loop->have_posts() ) : $loop->the_post();
  //       global $product;
  //       echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
  //   endwhile;
  //
  //   wp_reset_query();
  //
  //
  //
  // echo 'zzzzzzzzzzzzz';
}



?>
