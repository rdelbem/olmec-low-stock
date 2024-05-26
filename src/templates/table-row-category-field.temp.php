<tr class="form-field">
  <th scope="row" valign="top"><label
      for="cat_low_stock_notification"><?php _e('Low stock notification', OLMEC_LOW_STOCK_TEXT_DOMAIN); ?></label></th>
  <td>
    <input type="checkbox" name="cat_low_stock_notification" id="cat_low_stock_notification"
      <?php checked($checked, 1); ?>>
    <p class="description">
      <?php _e('Enable low stock notification for this category.', OLMEC_LOW_STOCK_TEXT_DOMAIN); ?></p>
  </td>
</tr>