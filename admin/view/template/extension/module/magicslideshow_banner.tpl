<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-module" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
        
        <ul class="nav nav-tabs">
            <li class="active" >
                <a href="#tab-general" data-toggle="tab">General</a>
            </li>
            <li>
                <a href="#tab-images" data-toggle="tab">Slideshow images</a>
            </li>
        </ul>
        
        <div class="tab-content">
          <div class=" tab-pane  active" id="tab-general">
          <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i>Main</h3>
            </div>
            <div class="panel-body params">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name">Name</label>
                <div class="col-sm-10">
                <input type="text" name="name" value="<?php echo (isset($name)) ? $name : '' ; ?>" placeholder="Slideshow box name" id="input-name" class="form-control" />
                <input type="hidden" name="module_id" value="<?php echo $module_id; ?>" />
                <?php if (isset($error_name)) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                <?php } ?>
                </div>
                
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                <select name="status" id="input-status" class="form-control">
                    <?php if (isset($status)) { ?>
                      <option value="1" selected="selected">Enabled</option>
                      <option value="0">Disabled</option>
                    <?php } else { ?>
                      <option value="1">Enabled</option>
                      <option value="0" selected="selected">Disabled</option>
                    <?php } ?>
                </select>
                </div>
                </div>
            </div>
            </div>
            <?php echo $settings; ?>
           </div> 
           <div  class="tab-pane" id="tab-images">
              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">Image</td>
                      <td class="text-right"></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                  
                  
                  <?php 
                  $image_row = 0;
                  foreach ($slideshow_images as $image) { ?>
                  
                  <tr id="image-row<?php echo $image_row; ?>">
                    <td class="text-left" width="100px"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                      <input type="hidden" name="slideshow_image[<?php echo $image_row; ?>][image]" value="<?php echo $image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                    <td class="text-right">
                    
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="link">Link</label>
                      <div class="col-sm-10">
                        <input type="text" name="slideshow_image[<?php echo $image_row; ?>][link]" placeholder="Link" id="link" value="<?php echo $image['link']; ?>" class="form-control" />
                      </div>
                    </div>
                    
                    <div class="form-group" style="border-top: none;">  
                      <label class="col-sm-2 control-label" for="title">Title</label>
                      <div class="col-sm-10">
                        <input type="text" name="slideshow_image[<?php echo $image_row; ?>][title]" placeholder="Image title" id="title" value="<?php echo $image['title']; ?>" class="form-control" />
                      </div>
                    </div>
                    
                    <div class="form-group" style="border-top: none;">  
                      <label class="col-sm-2 control-label" for="description">Description</label>
                      <div class="col-sm-10">
                        <textarea name="slideshow_image[<?php echo $image_row; ?>][description]" rows="5" placeholder="Description" id="description" class="form-control"><?php echo $image['description']; ?></textarea>
                      </div>
                    </div>
                    
                    </div>
                    </td>
                    <td class="text-left" width="20px">
                        <button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="Remove image" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                    </td>
                  </tr>
                  
                  <?php 
                  $image_row++;
                } ?>
                    </tbody>
                  
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="Add image" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
           </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
        html  = '<tr id="image-row' + image_row + '">';
        html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="slideshow_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
        html += '  <td class="text-right"><div class="form-group"><label class="col-sm-2 control-label" for="link">Link</label><div class="col-sm-10"><input type="text" name="slideshow_image[' + image_row + '][link]" placeholder="Link" id="link" value="" class="form-control" /></div></div><div class="form-group" style="border-top: none;"><label class="col-sm-2 control-label" for="title">Title</label><div class="col-sm-10"><input type="text" name="slideshow_image[' + image_row + '][title]" placeholder="Image title" id="title" value="" class="form-control" /></div></div><div class="form-group" style="border-top: none;"><label class="col-sm-2 control-label" for="description">Description</label><div class="col-sm-10"><textarea name="slideshow_image[' + image_row + '][description]" rows="5" placeholder="Description" id="description" class="form-control"></textarea></div></div></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#images tbody').append(html);

        image_row++;
}
//--></script> 