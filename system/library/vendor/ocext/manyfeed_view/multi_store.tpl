<div class="scrollbox" style="max-height: 350px; overflow-y: auto; width: 100%">

    <div class="checkbox">
    <label>
      <?php if (in_array(0, $ym_multi_store)) { ?>
      <input type="checkbox" name="ocext_feed_generator_google_ym_multi_store[]" value="0" checked="checked" />
      <?php echo $text_default; ?>
      <?php } else { ?>
      <input type="checkbox" name="ocext_feed_generator_google_ym_multi_store[]" value="0" />
      <?php echo $text_default; ?>
      <?php } ?>
    </label>
    </div>
    
<?php foreach ($stores as $store) { ?>
<div class="checkbox">
  <label>
    <?php if (in_array($store['store_id'], $ym_multi_store)) { ?>
    <input type="checkbox" name="ocext_feed_generator_google_ym_multi_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
    <?php echo $store['name']; ?>
    <?php } else { ?>
    <input type="checkbox" name="ocext_feed_generator_google_ym_multi_store[]" value="<?php echo $store['store_id']; ?>" />
    <?php echo $store['name']; ?>
    <?php } ?>
  </label>
</div>
<?php } ?>
    
</div>

