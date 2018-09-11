<?php
function instatwister_setting(){
  if($_POST['instatwister_setting'])
    update_option('instatwister_setting', $_POST['instatwister_setting'] );
  $setting = get_option('instatwister_setting');
  $products = wc_get_products([]);
  ?>
  <form method="POST">
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
        <th>سرور و توکن</th>
        <th>ای پی آی</th>
        <th>تعداد</th>
      </tr>
      <?php foreach ($products as $product): $product_data = $product->get_data(); $product_id = $product_data['id']; ?>
        <tr>
          <td>
            <input type="checkbox" name="instatwister_setting[<?= $product_id ?>][active]" <?= isset($setting[$product_id]['active'])? 'checked': 'dd' ?>  >
            <a href="<?= get_permalink( $product_id ) ?>"><?= $product_data['name'] ?></a>
          </td>
          <td>
            سرور: <input type="text" name="instatwister_setting[<?= $product_id ?>][server]" value="<?= $setting[$product_id]['server'] ?>" placeholder="سرور">
            <br>
            توکن: <input type="text" name="instatwister_setting[<?= $product_id ?>][token]" value="<?= $setting[$product_id]['token'] ?>" placeholder="توکن ای پی آی">
          </td>
          <td>
            سرویس:‌<input type="text" name="instatwister_setting[<?= $product_id ?>][service_id]" value="<?= $setting[$product_id]['service_id'] ?>" placeholder="شماره سرویس">
            <br>
            api:
            <select class="" name="instatwister_setting[<?= $product_id ?>][api]">
              <option value="jap" <?= (isset($setting[$product_id]['api']) && $setting[$product_id]['api'] == "jap" )? 'selected': ''  ?> >jap</option>
              <option value="ccc" <?= (isset($setting[$product_id]['api']) && $setting[$product_id]['api'] == "ccc" )? 'selected': ''  ?> >ccc</option>
            </select>
          </td>
          <td>
            تعداد: <input type="text" name="instatwister_setting[<?= $product_id ?>][count]" value="<?= $setting[$product_id]['count'] ?>" placeholder="تعداد">
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </form>
  <?php
}

function instatwister_setting_menu() {
  add_submenu_page( 'woocommerce', 'تنظیمات instatwister', 'تنظیمات instatwister', 'edit_posts', 'instatwister_setting', 'instatwister_setting' );
}
add_action('admin_menu', 'instatwister_setting_menu');
?>
