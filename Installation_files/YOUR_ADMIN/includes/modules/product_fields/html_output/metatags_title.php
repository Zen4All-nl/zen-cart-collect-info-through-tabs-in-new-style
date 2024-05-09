<?php
if (zen_not_null($_POST)) {
  $metatags_title = (isset($_POST['metatags_title']) ? $_POST['metatags_title'] : '');
}
?>
<p class="col-sm-3 control-label"><?php echo TEXT_META_TAGS_TITLE; ?></p>
<div class="col-sm-9 col-md-6">
  <?php for ($i = 0, $n = count($languages); $i < $n; $i++) { ?>
    <div class="input-group">
      <span class="input-group-addon">
        <?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
      </span>
      <?php echo zen_draw_input_field('metatags_title[' . $languages[$i]['id'] . ']', htmlspecialchars(isset($metatags_title[$languages[$i]['id']]) ? stripslashes($metatags_title[$languages[$i]['id']]) : zen_get_product_metatag_fields($productId, $languages[$i]['id'], 'metatags_title'), ENT_COMPAT, CHARSET, TRUE), zen_set_field_length(TABLE_META_TAGS_PRODUCTS_DESCRIPTION, 'metatags_title', '150', false) . ' class="form-control"'); ?>
    </div>
    <br>
  <?php } ?>
</div>