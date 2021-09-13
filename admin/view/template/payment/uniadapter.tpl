<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#
?>

<?php echo $header; ?>
<?php if(isset($column_left)) echo $column_left; ?>

<?php if (VERSION >= '1.5') { ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php } ?>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">

<?php if (VERSION < '1.5') { ?>
	<h1 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h1>
<?php } else { ?>
    <h1><img src="view/image/payment.png" alt="" /><?php echo $heading_title; ?></h1>
<?php } ?>

    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">


		<?php
		// dynamicka konfiguracni pole
		foreach ($configInfo->configFields as $configField) {
			//var_dump($configField);
			if ($configField->type != ConfigFieldType::$subMethodsSelection) {
				?>
				<tr>
				  <td><?php echo $configField->label ?></td>
				  <?php
				  if ($configField->type == ConfigFieldType::$text) {
					  ?>
					  <td><input type="text" name="uniadapter_<?php echo $configField->name ?>" value="<?php echo ${'uniadapter_'.$configField->name} ?>" />
					  <?php
				  } else if ($configField->type == ConfigFieldType::$choice) {
					  ?>
					  <td><select name="uniadapter_<?php echo $configField->name ?>">
						  <?php
							foreach ($configField->choiceItems as $val=>$lab) {
								?>
									<option value="<?php echo $val ?>" <?php if ($val !== null && $val == ${'uniadapter_'.$configField->name}) { ?>selected="selected"<?php } ?>><?php echo $lab ?></option>
								<?php
						   }
						  ?>
						</select></td>
					  <?php
				  } else if ($configField->type == ConfigFieldType::$orderStatus) {
					?>
					<td><select name="uniadapter_<?php echo $configField->name ?>">
					  <?php foreach ($order_statuses as $order_status) { ?>
						  <?php if ($order_status['order_status_id'] == ${'uniadapter_'.$configField->name}) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						  <?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						  <?php } ?>
					  <?php } ?>
					</select></td>
					<?php
				  }	  ?>
				  
				</tr>
			<?php
			}
			
		}
		
		?>
		
		
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="uniadapter_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $uniadapter_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="uniadapter_status">
              <?php if ($uniadapter_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="uniadapter_sort_order" value="<?php echo $uniadapter_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php if (VERSION >= '1.5') { ?>
</div>
<?php } ?>

<?php echo $footer; ?>