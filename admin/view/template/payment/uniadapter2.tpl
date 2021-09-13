<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#
?>

<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cod" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cod" class="form-horizontal">

		<?php
		// dynamicka konfiguracni pole
		foreach ($configInfo->configFields as $configField) {
			//var_dump($configField);
			if ($configField->type != ConfigFieldType::$subMethodsSelection) {
				?>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $configField->label; ?></label>
            <div class="col-sm-10">
				  <?php
				  if ($configField->type == ConfigFieldType::$text) {
					  ?>
					  <input type="text" name="uniadapter_<?php echo $configField->name ?>" value="<?php echo ${'uniadapter_'.$configField->name} ?>"  class="form-control" />
					  <?php
				  } else if ($configField->type == ConfigFieldType::$choice) {
					  ?>
					  <select name="uniadapter_<?php echo $configField->name ?>"  class="form-control" >
						  <?php
							foreach ($configField->choiceItems as $val=>$lab) {
								?>
									<option value="<?php echo $val ?>" <?php if ($val !== null && $val == ${'uniadapter_'.$configField->name}) { ?>selected="selected"<?php } ?>><?php echo $lab ?></option>
								<?php
						   }
						  ?>
						</select>
					  <?php
				  } else if ($configField->type == ConfigFieldType::$orderStatus) {
					?>
					<select name="uniadapter_<?php echo $configField->name ?>" class="form-control" >
					  <?php foreach ($order_statuses as $order_status) { ?>
						  <?php if ($order_status['order_status_id'] == ${'uniadapter_'.$configField->name}) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						  <?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						  <?php } ?>
					  <?php } ?>
					</select>
					<?php
				  }	  ?>
				  
            </div>
          </div>
			<?php
			}
			
		}
		
		?>


		  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="uniadapter_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $uniadapter_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

         <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="uniadapter_status" id="input-status" class="form-control">
                <?php if ($uniadapter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

		  
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="uniadapter_sort_order" value="<?php echo $uniadapter_sort_order; ?>" placeholder="<?php echo $uniadapter_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>		

		 
    </form>
  </div>
</div>
</div>
</div>

<?php echo $footer; ?>