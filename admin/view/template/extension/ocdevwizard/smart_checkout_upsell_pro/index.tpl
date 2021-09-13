<?php echo $header; ?>
<?php echo $column_left; ?>

<!--
##==================================================================##
## @author    : OCdevWizard                                         ##
## @contact   : ocdevwizard@gmail.com                               ##
## @support   : http://help.ocdevwizard.com                         ##
## @license   : http://license.ocdevwizard.com/Licensing_Policy.pdf ##
## @copyright : (c) OCdevWizard. Smart Checkout Upsell Pro, 2018    ##
##==================================================================##
-->

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right" id="top-nav-line">
        <button type="submit" formaction="<?php echo $action; ?>" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <button type="submit" formaction="<?php echo $action_plus; ?>" form="form" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-primary"><i class="fa fa-save"></i> + <i class="fa fa-refresh"></i></button>
        <div class="btn-group">
          <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
          <ul class="dropdown-menu dropdown-menu-right">
            <li><a onclick="confirm('<?php echo $text_are_you_sure; ?>') ? href='<?php echo $uninstall; ?>' : false;"><i class="fa fa-trash"></i> <?php echo $button_uninstall; ?></a></li>
            <li><a onclick="confirm('<?php echo $text_are_you_sure; ?>') ? href='<?php echo $uninstall_and_remove; ?>' : false;"><i class="fa fa-trash"></i> <?php echo $button_uninstall_and_remove; ?></a></li>
            <li><a onclick="confirm('<?php echo $text_are_you_sure; ?>') ? href='<?php echo $cache; ?>' : false;"><i class="fa fa-trash"></i> <?php echo $button_cache; ?></a></li>
            <li><a onclick="confirm('<?php echo $text_are_you_sure; ?>') ? href='<?php echo $cache_backup; ?>' : false;"><i class="fa fa-trash"></i> <?php echo $button_cache_backup; ?></a></li>
            <li><a onclick="confirm('<?php echo $text_are_you_sure; ?>') ? href='<?php echo $restore; ?>' : false;"><i class="fa fa-repeat"></i> <?php echo $button_restore; ?></a></li>
          </ul>
        </div>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb-module">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <?php if ($breadcrumb['href']) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } elseif (empty($breadcrumb['href']) && $breadcrumb['dropdown']) { ?>
            <li>
              <div class="btn-group dropdown-on-hover">
                <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $breadcrumb['text']; ?> <span class="caret"></span></button>
                <?php if ($breadcrumb['dropdown']) { ?>
                  <ul class="dropdown-menu">
                    <?php foreach ($breadcrumb['dropdown'] as $dropdown) { ?>
                      <li><a href="<?php echo $dropdown['href']; ?>"><?php if ($dropdown['active']) { ?><i class="fa fa-check-square-o"></i><?php } else { ?><i class="fa fa-square-o"></i><?php } ?> <?php echo $dropdown['text']; ?></a></li>
                    <?php } ?>
                  </ul>
                <?php } ?>
              </div>
            </li>
          <?php } else { ?>
            <li><a><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid" id="top-alerts">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-sm-3 col-md-3 col-lg-2">
        <div class="btn-group" style="width: 100%;margin-bottom: 10px;">
          <button type="button" class="btn btn-default dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> <?php echo $text_select_store; ?> <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <?php foreach ($all_stores as $store) { ?>
              <li><a href="<?php echo $store['href']; ?>"><?php if ($store_id == $store['store_id']) { ?><i class="fa fa-check-square-o"></i><?php } else { ?><i class="fa fa-square-o"></i><?php } ?> <?php echo $store['name']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
        <!-- Nav tabs -->
        <div class="list-group list-group-root well" id="setting-tabs">
          <a class="list-group-item list-group-item-info"><i class="fa fa-cog"></i><?php echo $tab_control_panel; ?></a>
          <div class="list-group">
            <a class="list-group-item" data-toggle="tab" href="#general-block" role="tab"><i class="fa fa-cogs"></i> <?php echo $tab_general_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#basic-block" role="tab"><i class="fa fa-cogs"></i> <?php echo $tab_basic_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#product-widget-block" role="tab"><i class="fa fa-cogs"></i> <?php echo $tab_product_widget_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#cart-widget-block" role="tab"><i class="fa fa-cogs"></i> <?php echo $tab_cart_widget_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#popup-widget-block" role="tab"><i class="fa fa-cogs"></i> <?php echo $tab_popup_widget_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#static-widget-block" role="tab"><i class="fa fa-cogs"></i> <?php echo $tab_static_widget_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#layout-block" role="tab"><i class="fa fa-eye"></i> <?php echo $tab_layout_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#css-block" role="tab"><i class="fa fa-css3"></i> <?php echo $tab_css_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#popup-block" role="tab"><i class="fa fa-desktop"></i> <?php echo $tab_popup_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#module-import-export-block" role="tab"><i class="fa fa-file-archive-o"></i> <?php echo $tab_module_import_export_setting; ?></a>
          </div>
          <a class="list-group-item list-group-item-info"><i class="fa fa-language"></i><?php echo $tab_language_setting; ?></a>
          <div class="list-group">
            <a class="list-group-item" data-toggle="tab" href="#language-product-widget-block" role="tab"><i class="fa fa-flag-o"></i> <?php echo $tab_product_widget_language_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#language-cart-widget-block" role="tab"><i class="fa fa-flag-o"></i> <?php echo $tab_cart_widget_language_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#language-popup-widget-block" role="tab"><i class="fa fa-flag-o"></i> <?php echo $tab_popup_widget_language_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#language-static-widget-block" role="tab"><i class="fa fa-flag-o"></i> <?php echo $tab_static_widget_language_setting; ?></a>
          </div>
          <a class="list-group-item list-group-item-info"><i class="fa fa-thumbs-o-up"></i><?php echo $tab_marketing_tools_setting; ?></a>
          <div class="list-group">
            <a class="list-group-item" data-toggle="tab" href="#up-sell-block" role="tab"><i class="fa fa-sliders"></i> <?php echo $tab_up_sell_products_setting; ?></a>
          </div>
          <a class="list-group-item list-group-item-info"><i class="fa fa-life-ring"></i><?php echo $tab_support_setting; ?></a>
          <div class="list-group">
            <a class="list-group-item" data-toggle="tab" href="#support-extension-block" role="tab"><i class="fa fa-info-circle"></i> <?php echo $tab_support_extension_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#support-general-block" role="tab"><i class="fa fa-info-circle"></i> <?php echo $tab_support_general_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#support-terms-block" role="tab"><i class="fa fa-info-circle"></i> <?php echo $tab_support_terms_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#support-faq-block" role="tab"><i class="fa fa-question-circle"></i> <?php echo $tab_support_faq_setting; ?></a>
            <a class="list-group-item" data-toggle="tab" href="#promo-block" role="tab"><i class="fa fa-briefcase"></i> <?php echo $tab_promo_setting; ?></a>
          </div>
        </div>
      </div>
      <div class="col-sm-9 col-md-9 col-lg-10">
        <div class="panel panel-default">
          <div class="panel-body">
            <form method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
              <div class="tab-content">
                <!-- TAB General block -->
                <div class="tab-pane fade active in" role="tabpanel" id="general-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_activate_module; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group btn-toggle" data-toggle="buttons">
                        <label class="btn <?php echo $form_data['activate'] == 1 ? 'active btn-success' : 'btn-default'; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['activate'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn <?php echo $form_data['activate'] == 0 ? 'active btn-success' : 'btn-default'; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['activate'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-main_product_id_selector"><?php echo $text_main_product_id_selector; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_css_id_indicator; ?></span>
                        <input value="<?php echo $form_data['main_product_id_selector']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[main_product_id_selector]" class="form-control" id="input-main_product_id_selector" />
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button" data-faq-target="faq_3" data-toggle="tooltip" title="<?php echo $text_open_example; ?>"><i class="fa fa-info-circle"></i></button>
                        </span>
                      </div>
                      <?php if ($error_main_product_id_selector) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_main_product_id_selector; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_main_product_id_selector_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-route_to_system_add_method"><?php echo $text_route_to_system_add_method; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_route_indicator; ?></span>
                        <input value="<?php echo $form_data['route_to_system_add_method']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[route_to_system_add_method]" class="form-control" id="input-route_to_system_add_method" />
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button" data-faq-target="faq_4" data-toggle="tooltip" title="<?php echo $text_open_example; ?>"><i class="fa fa-info-circle"></i></button>
                        </span>
                      </div>
                      <?php if ($error_route_to_system_add_method) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_route_to_system_add_method; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_route_to_system_add_method_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_customer_groups; ?></label>
                    <div class="col-sm-10">
                      <?php $row_height = 55; $row = 0; foreach ($all_customer_groups as $customer_group) { ?>
                      <?php if ($row < 5) { $row_height = $row_height*1.26; } $row++; ?>
                      <?php } ?>
                      <div class="well well-sm" style="height: <?php echo $row_height; ?>px; overflow: auto;">
                        <?php $row = 0; foreach ($all_customer_groups as $customer_group) { ?>
                        <div class="checkbox">
                          <label>
                            <input
                              type="checkbox"
                              name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[customer_groups][]"
                              value="<?php echo $customer_group['customer_group_id']; ?>" <?php echo (!empty($form_data['customer_groups']) && in_array($customer_group['customer_group_id'], $form_data['customer_groups'])) ? 'checked' : ''; ?>
                            /> <?php echo $customer_group['name']; ?>
                          </label>
                        </div>
                        <?php $row++; ?>
                        <?php } ?>
                      </div>
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" onclick="$(this).parent().parent().find(':checkbox').trigger('click').attr('checked', true);"><?php echo $text_select_all; ?></button>
                        <button type="button" class="btn btn-default btn-xs" onclick="$(this).parent().parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></button>
                      </div>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_customer_groups_faq; ?></div>
                    </div>
                  </div>
                </div>
                <!-- TAB Basic block -->
                <div class="tab-pane fade" role="tabpanel" id="basic-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_direction_type; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="btn-group-vertical btn-toggle" data-toggle="buttons">
                        <label class="btn <?php echo (isset($form_data['direction_type'][$language['language_id']]) && $form_data['direction_type'][$language['language_id']] == 1) ? 'active btn-success' : 'btn-default'; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[direction_type][<?php echo $language['language_id']; ?>]"
                            value="1"
                            autocomplete="off"
                            <?php echo (isset($form_data['direction_type'][$language['language_id']]) && $form_data['direction_type'][$language['language_id']] == 1) ? 'checked="checked"' : ''; ?>
                          /><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $text_direction_type_1; ?>
                        </label>
                        <label class="btn <?php echo (isset($form_data['direction_type'][$language['language_id']]) && $form_data['direction_type'][$language['language_id']] == 2) ? 'active btn-success' : 'btn-default'; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[direction_type][<?php echo $language['language_id']; ?>]"
                            value="2"
                            autocomplete="off"
                            <?php echo (isset($form_data['direction_type'][$language['language_id']]) && $form_data['direction_type'][$language['language_id']] == 2) ? 'checked="checked"' : ''; ?>
                          /><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $text_direction_type_2; ?>
                        </label>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group pro-block">
                    <label class="col-sm-2 control-label"><?php echo $text_minify_main_js; ?></label>
                    <div class="col-sm-10">
                      <select name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[minify_main_js]" class="form-control">
                        <option value="0" <?php echo $form_data['minify_main_js'] == 0 ? 'selected="selected"' : ''; ?> ><?php echo $text_no; ?></option>
                        <option value="1" <?php echo $form_data['minify_main_js'] == 1 ? 'selected="selected"' : ''; ?> ><?php echo $text_minify_main_js_1; ?></option>
                        <option value="2" <?php echo $form_data['minify_main_js'] == 2 ? 'selected="selected"' : ''; ?> ><?php echo $text_minify_main_js_2; ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <!-- TAB Product widget block -->
                <div class="tab-pane fade" role="tabpanel" id="product-widget-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_activate_product_widget; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['activate_product_widget'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_product_widget]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['activate_product_widget'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['activate_product_widget'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_product_widget]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['activate_product_widget'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                      <div class="alert alert-warning" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_activate_product_widget_faq_1; ?></div>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_activate_product_widget_faq_2; ?></div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="textarea-insert_widget"><?php echo $text_insert_widget; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_js_indicator; ?></span>
                        <textarea style="min-height:70px;" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[insert_widget]" class="form-control" id="textarea-insert_widget"><?php echo $form_data['insert_widget']; ?></textarea>
                      </div>
                      <?php if ($error_insert_widget) { ?>
                        <div class="alert alert-danger text-danger"><?php echo $error_insert_widget; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_insert_widget_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_image; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_image'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_image]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_image'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_image'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_image]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_image'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_dementions_of_main_image; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_width_indicator; ?></span>
                        <input value="<?php echo $form_data['product_widget_main_image_width']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_main_image_width]" class="form-control" placeholder="<?php echo $text_image_width_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <div class="special-margin"></div>
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_height_indicator; ?></span>
                        <input value="<?php echo $form_data['product_widget_main_image_height']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_main_image_height]" class="form-control" placeholder="<?php echo $text_image_height_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <?php if ($error_product_widget_main_image_width) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_product_widget_main_image_width; ?></div>
                      <?php } ?>
                      <?php if ($error_product_widget_main_image_height) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_product_widget_main_image_height; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_warning_dementions_of_main_image; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_price; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_price'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_price]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_price'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_price'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_price]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_price'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_name; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_name'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_name]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_name'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_name'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_name]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_name'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_description; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_description'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_description]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_description'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_description'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_description]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_description'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_product_description_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['product_widget_product_description_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_product_description_limit]" class="form-control" />
                      <?php if ($error_product_widget_product_description_limit) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_product_widget_product_description_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_rating; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_rating'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_rating]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_rating'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_rating'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_rating]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_rating'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_cart_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_cart_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_cart_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_cart_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_cart_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_cart_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_cart_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_wishlist_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_wishlist_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_wishlist_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_wishlist_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_wishlist_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_wishlist_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_wishlist_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_show_product_compare_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_compare_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_compare_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_compare_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_show_product_compare_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_show_product_compare_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_show_product_compare_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_randomize; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['product_widget_randomize'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_randomize]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_randomize'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['product_widget_randomize'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_randomize]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['product_widget_randomize'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_product_widget_product_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['product_widget_product_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[product_widget_product_limit]" class="form-control" />
                      <?php if ($error_product_widget_product_limit) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_product_widget_product_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <!-- TAB Cart widget block -->
                <div class="tab-pane fade" role="tabpanel" id="cart-widget-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_activate_cart_widget; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['activate_cart_widget'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_cart_widget]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['activate_cart_widget'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['activate_cart_widget'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_cart_widget]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['activate_cart_widget'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                      <div class="alert alert-warning" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_activate_cart_widget_faq_1; ?></div>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_activate_cart_widget_faq_2; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_image; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_image'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_image]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_image'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_image'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_image]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_image'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_dementions_of_main_image; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_width_indicator; ?></span>
                        <input value="<?php echo $form_data['cart_widget_main_image_width']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_main_image_width]" class="form-control" placeholder="<?php echo $text_image_width_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <div class="special-margin"></div>
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_height_indicator; ?></span>
                        <input value="<?php echo $form_data['cart_widget_main_image_height']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_main_image_height]" class="form-control" placeholder="<?php echo $text_image_height_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <?php if ($error_cart_widget_main_image_width) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_cart_widget_main_image_width; ?></div>
                      <?php } ?>
                      <?php if ($error_cart_widget_main_image_height) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_cart_widget_main_image_height; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_warning_dementions_of_main_image; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_price; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_price'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_price]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_price'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_price'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_price]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_price'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_name; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_name'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_name]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_name'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_name'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_name]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_name'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_description; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_description'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_description]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_description'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_description'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_description]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_description'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_product_description_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['cart_widget_product_description_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_product_description_limit]" class="form-control" />
                      <?php if ($error_cart_widget_product_description_limit) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_cart_widget_product_description_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_rating; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_rating'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_rating]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_rating'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_rating'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_rating]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_rating'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_cart_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_cart_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_cart_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_cart_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_cart_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_cart_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_cart_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_wishlist_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_wishlist_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_wishlist_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_wishlist_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_wishlist_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_wishlist_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_wishlist_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_show_product_compare_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_compare_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_compare_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_compare_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_show_product_compare_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_show_product_compare_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_show_product_compare_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_randomize; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['cart_widget_randomize'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_randomize]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_randomize'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['cart_widget_randomize'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_randomize]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['cart_widget_randomize'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_cart_widget_product_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['cart_widget_product_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[cart_widget_product_limit]" class="form-control" />
                      <?php if ($error_cart_widget_product_limit) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_cart_widget_product_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <!-- TAB Popup widget block -->
                <div class="tab-pane fade" role="tabpanel" id="popup-widget-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_activate_popup_widget; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['activate_popup_widget'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_popup_widget]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['activate_popup_widget'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['activate_popup_widget'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_popup_widget]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['activate_popup_widget'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_activate_popup_widget_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_image; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_image'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_image]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_image'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_image'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_image]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_image'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_dementions_of_main_image; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_width_indicator; ?></span>
                        <input value="<?php echo $form_data['popup_widget_main_image_width']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_main_image_width]" class="form-control" placeholder="<?php echo $text_image_width_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <div class="special-margin"></div>
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_height_indicator; ?></span>
                        <input value="<?php echo $form_data['popup_widget_main_image_height']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_main_image_height]" class="form-control" placeholder="<?php echo $text_image_height_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <?php if ($error_popup_widget_main_image_width) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_popup_widget_main_image_width; ?></div>
                      <?php } ?>
                      <?php if ($error_popup_widget_main_image_height) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_popup_widget_main_image_height; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_warning_dementions_of_main_image; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_price; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_price'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_price]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_price'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_price'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_price]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_price'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_name; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_name'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_name]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_name'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_name'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_name]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_name'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_description; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_description'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_description]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_description'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_description'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_description]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_description'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_product_description_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['popup_widget_product_description_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_product_description_limit]" class="form-control" />
                      <?php if ($error_popup_widget_product_description_limit) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_popup_widget_product_description_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_rating; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_rating'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_rating]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_rating'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_rating'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_rating]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_rating'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_cart_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_cart_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_cart_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_cart_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_cart_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_cart_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_cart_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_wishlist_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_wishlist_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_wishlist_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_wishlist_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_wishlist_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_wishlist_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_wishlist_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_show_product_compare_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_compare_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_compare_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_compare_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_show_product_compare_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_show_product_compare_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_show_product_compare_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_randomize; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['popup_widget_randomize'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_randomize]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_randomize'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['popup_widget_randomize'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_randomize]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['popup_widget_randomize'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_widget_product_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['popup_widget_product_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_widget_product_limit]" class="form-control" />
                      <?php if ($error_popup_widget_product_limit) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_popup_widget_product_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-route_to_chekout_page"><?php echo $text_route_to_chekout_page; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_route_indicator; ?></span>
                        <input value="<?php echo $form_data['route_to_chekout_page']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[route_to_chekout_page]" class="form-control" id="input-route_to_chekout_page" />
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button" data-faq-target="faq_5" data-toggle="tooltip" title="<?php echo $text_open_example; ?>"><i class="fa fa-info-circle"></i></button>
                        </span>
                      </div>
                      <?php if ($error_route_to_chekout_page) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_route_to_chekout_page; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <!-- TAB Static widget block -->
                <div class="tab-pane fade" role="tabpanel" id="static-widget-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_activate_static_widget; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['activate_static_widget'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_static_widget]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['activate_static_widget'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['activate_static_widget'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[activate_static_widget]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['activate_static_widget'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                      <div class="alert alert-warning" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_activate_static_widget_faq_1; ?></div>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_activate_static_widget_faq_2; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_image; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_image'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_image]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_image'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_image'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_image]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_image'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_dementions_of_main_image; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_width_indicator; ?></span>
                        <input value="<?php echo $form_data['static_widget_main_image_width']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_main_image_width]" class="form-control" placeholder="<?php echo $text_image_width_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <div class="special-margin"></div>
                      <div class="input-group">
                        <span class="input-group-addon"><?php echo $text_height_indicator; ?></span>
                        <input value="<?php echo $form_data['static_widget_main_image_height']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_main_image_height]" class="form-control" placeholder="<?php echo $text_image_height_ph; ?>" />
                        <span class="input-group-addon"><?php echo $text_px; ?></span>
                      </div>
                      <?php if ($error_static_widget_main_image_width) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_static_widget_main_image_width; ?></div>
                      <?php } ?>
                      <?php if ($error_static_widget_main_image_height) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_static_widget_main_image_height; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_warning_dementions_of_main_image; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_price; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_price'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_price]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_price'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_price'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_price]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_price'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_name; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_name'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_name]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_name'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_name'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_name]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_name'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_description; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_description'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_description]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_description'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_description'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_description]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_description'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_product_description_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['static_widget_product_description_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_product_description_limit]" class="form-control" />
                      <?php if ($error_static_widget_product_description_limit) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_static_widget_product_description_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_rating; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_rating'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_rating]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_rating'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_rating'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_rating]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_rating'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_cart_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_cart_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_cart_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_cart_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_cart_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_cart_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_cart_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_wishlist_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_wishlist_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_wishlist_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_wishlist_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_wishlist_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_wishlist_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_wishlist_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_show_product_compare_button; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_compare_button'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_compare_button]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_compare_button'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_show_product_compare_button'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_show_product_compare_button]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_show_product_compare_button'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_randomize; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-success <?php echo $form_data['static_widget_randomize'] == 1 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_randomize]"
                            value="1"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_randomize'] == 1 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-success <?php echo $form_data['static_widget_randomize'] == 0 ? 'active' : ''; ?>">
                          <input type="radio"
                            name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_randomize]"
                            value="0"
                            autocomplete="off"
                            <?php echo $form_data['static_widget_randomize'] == 0 ? 'checked="checked"' : ''; ?>
                          /><?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_product_limit; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['static_widget_product_limit']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_product_limit]" class="form-control" />
                      <?php if ($error_static_widget_product_limit) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_static_widget_product_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_static_widget_position; ?></label>
                    <div class="col-sm-10">
                      <select name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_position]" class="form-control">
                        <?php if ($form_data['static_widget_position'] == 'column_left') { ?>
                          <option value="column_left" selected="selected"><?php echo $text_static_widget_position_1; ?></option>
                          <option value="column_right"><?php echo $text_static_widget_position_2; ?></option>
                          <option value="content_top"><?php echo $text_static_widget_position_3; ?></option>
                          <option value="content_bottom"><?php echo $text_static_widget_position_4; ?></option>
                        <?php } elseif ($form_data['static_widget_position'] == 'column_right') { ?>
                          <option value="column_left"><?php echo $text_static_widget_position_1; ?></option>
                          <option value="column_right" selected="selected"><?php echo $text_static_widget_position_2; ?></option>
                          <option value="content_top"><?php echo $text_static_widget_position_3; ?></option>
                          <option value="content_bottom"><?php echo $text_static_widget_position_4; ?></option>
                        <?php } elseif ($form_data['static_widget_position'] == 'content_top') { ?>
                          <option value="column_left"><?php echo $text_static_widget_position_1; ?></option>
                          <option value="column_right"><?php echo $text_static_widget_position_2; ?></option>
                          <option value="content_top" selected="selected"><?php echo $text_static_widget_position_3; ?></option>
                          <option value="content_bottom"><?php echo $text_static_widget_position_4; ?></option>
                        <?php } else { ?>
                          <option value="column_left"><?php echo $text_static_widget_position_1; ?></option>
                          <option value="column_right"><?php echo $text_static_widget_position_2; ?></option>
                          <option value="content_top"><?php echo $text_static_widget_position_3; ?></option>
                          <option value="content_bottom" selected="selected"><?php echo $text_static_widget_position_4; ?></option>
                        <?php } ?>
                      </select>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_static_widget_position_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-sort_order"><?php echo $text_static_widget_sort_order; ?></label>
                    <div class="col-sm-10">
                      <input value="<?php echo $form_data['static_widget_sort_order']; ?>" type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[static_widget_sort_order]" class="form-control" />
                      <?php if ($error_static_widget_sort_order) { ?>
                        <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_static_widget_sort_order; ?></div>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_static_widget_sort_order_faq; ?></div>
                    </div>
                  </div>
                </div>
                <!-- TAB Layout block -->
                <div class="tab-pane fade" role="tabpanel" id="layout-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_hide_info_message; ?></label>
                    <div class="col-sm-10">
                      <select name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[hide_info_message]" class="form-control">
                        <option value="0" <?php echo $form_data['hide_info_message'] == 0 ? 'selected="selected"' : ''; ?> ><?php echo $text_no; ?></option>
                        <option value="1" <?php echo $form_data['hide_info_message'] == 1 ? 'selected="selected"' : ''; ?> ><?php echo $text_before_products_list; ?></option>
                        <option value="2" <?php echo $form_data['hide_info_message'] == 2 ? 'selected="selected"' : ''; ?> ><?php echo $text_after_products_list; ?></option>
                      </select>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $text_hide_info_message_faq; ?></div>
                    </div>
                  </div>
                </div>
                <!-- TAB CSS block -->
                <div class="tab-pane fade" role="tabpanel" id="css-block">
                  <div class="form-group pro-block">
                    <label class="col-sm-2 control-label"><?php echo $text_edit_css; ?></label>
                    <div class="col-sm-10">
                      <textarea id="edit-css-block-0"><?php echo $stylesheet_code; ?></textarea>
                      <br/>
                      <button type="button" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_save_css; ?>" onclick="save_css('0', 'stylesheet');"><i class="fa fa-save"></i></button>
                      <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_restore_css; ?>" onclick="confirm('<?php echo $text_are_you_sure; ?>') ? restore_css('0', 'stylesheet', 'stylesheet_default') : false;"><i class="fa fa-refresh"></i></button>
                      <br/><br/>
                      <div id="result-css-block-0"></div>
                    </div>
                  </div>
                  <div class="form-group pro-block">
                    <label class="col-sm-2 control-label"><?php echo $text_edit_css_rtl; ?></label>
                    <div class="col-sm-10">
                      <textarea id="edit-css-block-1"><?php echo $stylesheet_code_rtl; ?></textarea>
                      <br/>
                      <button type="button" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_save_css; ?>" onclick="save_css('1', 'stylesheet_rtl');"><i class="fa fa-save"></i></button>
                      <button type="button" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_restore_css; ?>" onclick="confirm('<?php echo $text_are_you_sure; ?>') ? restore_css('1', 'stylesheet_rtl', 'stylesheet_rtl_default') : false;"><i class="fa fa-refresh"></i></button>
                      <br/><br/>
                      <div id="result-css-block-1"></div>
                    </div>
                  </div>
                </div>
                <!-- TAB Popup block -->
                <div class="tab-pane fade" role="tabpanel" id="popup-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_background_images; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group div-background-images">
                        <?php if ($backgrounds) { ?>
                          <?php $key = 1; foreach ($backgrounds as $background) { ?>
                          <input type="radio" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[style_background]" id="label-img-<?php echo $key; ?>" value="<?php echo $background['name']; ?>" <?php echo (!empty($form_data['style_background']) && $form_data['style_background'] == $background['name']) ? 'checked' : ''; ?> />
                          <button type="button" class="background-for-label" data-background-image-id="<?php echo $key; ?>" data-background-image-src="<?php echo $background['src']; ?>" style="background:url(<?php echo $background['src']; ?>);"></button>
                          <?php $key++; } ?>
                        <?php } ?>
                      </div>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_background_images_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group pro-block">
                    <label class="col-sm-2 control-label"><?php echo $text_popup_animation_type; ?></label>
                    <div class="col-sm-10">
                      <select name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[popup_animation_type]" class="form-control">
                        <option value="0" <?php echo $form_data['popup_animation_type'] == 0 ? 'selected="selected"' : ''; ?> ><?php echo $text_popup_animation_type_1; ?></option>
                        <option value="mfp-zoom-in" <?php echo $form_data['popup_animation_type'] == 'mfp-zoom-in' ? 'selected="selected"' : ''; ?> ><?php echo $text_popup_animation_type_2; ?></option>
                        <option value="mfp-zoom-out" <?php echo $form_data['popup_animation_type'] == 'mfp-zoom-out' ? 'selected="selected"' : ''; ?> ><?php echo $text_popup_animation_type_3; ?></option>
                        <option value="mfp-move-from-left" <?php echo $form_data['popup_animation_type'] == 'mfp-move-from-left' ? 'selected="selected"' : ''; ?> ><?php echo $text_popup_animation_type_4; ?></option>
                        <option value="mfp-move-from-top" <?php echo $form_data['popup_animation_type'] == 'mfp-move-from-top' ? 'selected="selected"' : ''; ?> ><?php echo $text_popup_animation_type_5; ?></option>
                        <option value="mfp-3d-flip" <?php echo $form_data['popup_animation_type'] == 'mfp-3d-flip' ? 'selected="selected"' : ''; ?> ><?php echo $text_popup_animation_type_6; ?></option>
                        <option value="mfp-newspaper" <?php echo $form_data['popup_animation_type'] == 'mfp-newspaper' ? 'selected="selected"' : ''; ?> ><?php echo $text_popup_animation_type_7; ?></option>
                      </select>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_popup_animation_type_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_background_opacity; ?> <i class="fa fa-info-sign faq" data-toggle="tooltip" data-placement="right" title="<?php echo $text_background_opacity_faq; ?>"></i></label>
                    <div class="col-xs-10 col-sm-6 col-md-6 col-lg-3">
                      <div class="input-group">
                        <span class="input-group-btn">
                          <button class="btn btn-success" type="button" onclick="if (parseInt($(this).parent().next().val())>=1){$(this).parent().next().val(~~$(this).parent().next().val()-1)}">-</button>
                        </span>
                        <input type="text" value="<?php echo (!empty($form_data['background_opacity'])) ? $form_data['background_opacity'] : 0; ?>" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[background_opacity]" class="form-control" />
                        <span class="input-group-btn">
                          <button class="btn btn-success" type="button" onclick="if (parseInt($(this).parent().prev().val())<=9){$(this).parent().prev().val(~~$(this).parent().prev().val()+1)}">+</button>
                        </span>
                      </div>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_background_opacity_faq; ?></div>
                    </div>
                  </div>
                </div>
                <!-- TAB Import/Export module block -->
                <div class="tab-pane fade" role="tabpanel" id="module-import-export-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_restore_from_external_file; ?></label>
                    <div class="col-sm-5">
                      <input type="file" name="module_import" style="display:none;" id="module-load-file" />
                      <div class="input-group">
                        <span class="input-group-btn">
                          <button class="btn btn-primary" type="button" onclick="$('#module-load-file').click();"><?php echo $text_select_file; ?></button>
                        </span>
                        <input type="text" name="module_load_file_mask" id="module-load-file-mask" class="form-control">
                        <span class="input-group-btn">
                          <button id="module-button-import-file-1" type="submit" formaction="<?php echo $action_plus; ?>" form="form" class="btn btn-success" disabled="disabled"><i class="fa fa-download"></i> <?php echo $button_import; ?></button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_restore_from_local_file; ?></label>
                    <div class="col-sm-5">
                      <div class="input-group">
                        <select name="module_backup_file_name" class="form-control">
                          <option value=""><?php echo $text_make_a_choice; ?></option>
                          <?php if ($module_backup_files) { ?>
                          <?php foreach ($module_backup_files as $module_backup_file) { ?>
                          <option value="<?php echo $module_backup_file['name']; ?>"><?php echo $module_backup_file['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <span class="input-group-btn">
                          <button id="module-button-import-file-2" type="button" class="btn btn-success" disabled="disabled"><i class="fa fa-download"></i> <?php echo $button_import; ?></button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_export; ?></label>
                    <div class="col-sm-5">
                      <a href="<?php echo $export_module_settings_button; ?>" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_export; ?></a>
                    </div>
                  </div>
                </div>
                <!-- TAB Product widget language block -->
                <div class="tab-pane fade" role="tabpanel" id="language-product-widget-block">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_heading_product_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][heading_product_widget]" value="<?php echo (!empty($text_data[$language['language_id']]['heading_product_widget'])) ? $text_data[$language['language_id']]['heading_product_widget'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_heading_product_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_heading_product_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_heading_product_widget_faq; ?></div>
                      <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#collapse-heading-product-widget-sub" aria-expanded="false" aria-controls="collapse-heading-product-widget-sub" style="margin-top:10px;"><?php echo $button_view_shortcodes; ?></button>
                    </div>
                  </div>
                  <div class="collapse" id="collapse-heading-product-widget-sub">
                    <div class="form-group" style="margin-bottom: 0px;">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <div class="well">
                          <?php echo $text_heading_product_widget_sub_h1; ?><br/>
                          <?php echo $text_heading_product_widget_sub_c1; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_alternative_heading_product_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][alternative_heading_product_widget]" value="<?php echo (!empty($text_data[$language['language_id']]['alternative_heading_product_widget'])) ? $text_data[$language['language_id']]['alternative_heading_product_widget'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_alternative_heading_product_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_alternative_heading_product_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_alternative_heading_product_widget_faq; ?></div>
                    </div>
                  </div>
                </div>
                <!-- TAB Cart widget language block -->
                <div class="tab-pane fade" role="tabpanel" id="language-cart-widget-block">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_heading_cart_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][heading_cart_widget]" value="<?php echo (!empty($text_data[$language['language_id']]['heading_cart_widget'])) ? $text_data[$language['language_id']]['heading_cart_widget'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_heading_cart_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_heading_cart_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_heading_cart_widget_faq; ?></div>
                      <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#collapse-heading-cart-widget-sub" aria-expanded="false" aria-controls="collapse-heading-cart-widget-sub" style="margin-top:10px;"><?php echo $button_view_shortcodes; ?></button>
                    </div>
                  </div>
                  <div class="collapse" id="collapse-heading-cart-widget-sub">
                    <div class="form-group" style="margin-bottom: 0px;">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <div class="well">
                          <?php echo $text_heading_cart_widget_sub_h1; ?><br/>
                          <?php echo $text_heading_cart_widget_sub_c1; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- TAB Popup widget language block -->
                <div class="tab-pane fade" role="tabpanel" id="language-popup-widget-block">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_heading_popup_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][heading_popup_widget]" value="<?php echo (!empty($text_data[$language['language_id']]['heading_popup_widget'])) ? $text_data[$language['language_id']]['heading_popup_widget'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_heading_popup_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_heading_popup_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_heading_popup_widget_faq; ?></div>
                      <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#collapse-heading-popup-widget-sub" aria-expanded="false" aria-controls="collapse-heading-popup-widget-sub" style="margin-top:10px;"><?php echo $button_view_shortcodes; ?></button>
                    </div>
                  </div>
                  <div class="collapse" id="collapse-heading-popup-widget-sub">
                    <div class="form-group" style="margin-bottom: 0px;">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <div class="well">
                          <?php echo $text_heading_popup_widget_sub_h1; ?><br/>
                          <?php echo $text_heading_popup_widget_sub_c1; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_alternative_heading_popup_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][alternative_heading_popup_widget]" value="<?php echo (!empty($text_data[$language['language_id']]['alternative_heading_popup_widget'])) ? $text_data[$language['language_id']]['alternative_heading_popup_widget'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_alternative_heading_popup_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_alternative_heading_popup_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_alternative_heading_popup_widget_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_go_to_cart_button; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][go_to_cart_button]" value='<?php echo (!empty($text_data[$language['language_id']]['go_to_cart_button'])) ? $text_data[$language['language_id']]['go_to_cart_button'] : ''; ?>' class="form-control" />
                      </div>
                      <?php if (isset($error_go_to_cart_button[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_go_to_cart_button[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_go_to_cart_button_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_continue_shopping_button; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][continue_shopping_button]" value="<?php echo (!empty($text_data[$language['language_id']]['continue_shopping_button'])) ? $text_data[$language['language_id']]['continue_shopping_button'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_continue_shopping_button[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_continue_shopping_button[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_continue_shopping_button_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_info_message_popup_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <textarea name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][info_message_popup_widget]" id="info_message_popup_widget<?php echo $language['language_id']; ?>" class="form-control"><?php echo (!empty($text_data[$language['language_id']]['info_message_popup_widget'])) ? $text_data[$language['language_id']]['info_message_popup_widget'] : '';?></textarea>
                      </div>
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" onclick="texteditor_action({id: '#info_message_popup_widget<?php echo $language['language_id']; ?>'});"><?php echo $text_open_texteditor; ?></button>
                        <button type="button" class="btn btn-default btn-xs" onclick="texteditor_action({id: '#info_message_popup_widget<?php echo $language['language_id']; ?>', start: false, destroy: true});" style="display: none;"><?php echo $text_save_texteditor; ?></button>
                      </div>
                      <?php if (isset($error_info_message_popup_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger" role="alert"><?php echo $error_info_message_popup_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_info_message_popup_widget_faq; ?></div>
                      <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#collapse-info-message-popup-widget-sub" aria-expanded="false" aria-controls="collapse-info-message-popup-widget-sub" style="margin-top:10px;"><?php echo $button_view_shortcodes; ?></button>
                    </div>
                  </div>
                  <div class="collapse" id="collapse-info-message-popup-widget-sub">
                    <div class="form-group" style="margin-bottom: 0px;">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <div class="well">
                          <?php echo $text_info_message_popup_widget_sub_h1; ?><br/>
                          <?php echo $text_info_message_popup_widget_sub_c1; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_alternative_info_message_popup_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <textarea name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][alternative_info_message_popup_widget]" id="alternative_info_message_popup_widget<?php echo $language['language_id']; ?>" class="form-control"><?php echo (!empty($text_data[$language['language_id']]['alternative_info_message_popup_widget'])) ? $text_data[$language['language_id']]['alternative_info_message_popup_widget'] : '';?></textarea>
                      </div>
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs" onclick="texteditor_action({id: '#alternative_info_message_popup_widget<?php echo $language['language_id']; ?>'});"><?php echo $text_open_texteditor; ?></button>
                        <button type="button" class="btn btn-default btn-xs" onclick="texteditor_action({id: '#alternative_info_message_popup_widget<?php echo $language['language_id']; ?>', start: false, destroy: true});" style="display: none;"><?php echo $text_save_texteditor; ?></button>
                      </div>
                      <?php if (isset($error_alternative_info_message_popup_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger" role="alert"><?php echo $error_alternative_info_message_popup_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_alternative_info_message_popup_widget_faq; ?></div>
                    </div>
                  </div>
                </div>
                <!-- TAB Static widget language block -->
                <div class="tab-pane fade" role="tabpanel" id="language-static-widget-block">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_heading_static_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][heading_static_widget]" value="<?php echo (!empty($text_data[$language['language_id']]['heading_static_widget'])) ? $text_data[$language['language_id']]['heading_static_widget'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_heading_static_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_heading_static_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_heading_static_widget_faq; ?></div>
                      <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#collapse-heading-static-widget-sub" aria-expanded="false" aria-controls="collapse-heading-static-widget-sub" style="margin-top:10px;"><?php echo $button_view_shortcodes; ?></button>
                    </div>
                  </div>
                  <div class="collapse" id="collapse-heading-static-widget-sub">
                    <div class="form-group" style="margin-bottom: 0px;">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10">
                        <div class="well">
                          <?php echo $text_heading_static_widget_sub_h1; ?><br/>
                          <?php echo $text_heading_static_widget_sub_c1; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $text_alternative_heading_static_widget; ?></label>
                    <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group" style="margin-bottom: 5px;">
                        <span class="input-group-addon"><img src="<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3o474x4p464b464q4j5[<?php echo $language['language_id']; ?>][alternative_heading_static_widget]" value="<?php echo (!empty($text_data[$language['language_id']]['alternative_heading_static_widget'])) ? $text_data[$language['language_id']]['alternative_heading_static_widget'] : ''; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_alternative_heading_static_widget[$language['language_id']])) { ?>
                      <div class="alert alert-danger text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_alternative_heading_static_widget[$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_alternative_heading_static_widget_faq; ?></div>
                    </div>
                  </div>
                </div>
                <!-- TAB Up-sell block -->
                <div class="tab-pane fade" role="tabpanel" id="up-sell-block">
                  <table id="upsell-blocks" class="table table-bordered">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_products_in_cart; ?></td>
                        <td class="text-left"><?php echo $column_recommended_products; ?></td>
                        <td class="text-left"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $upsell_row = 0; ?>
                      <?php foreach ($upsells as $upsell) { ?>
                      <tr id="upsell_row<?php echo $upsell_row; ?>">
                        <td class="text-left" style="vertical-align:top">
                          <input type="text" name="product_cart<?php echo $upsell_row; ?>" value="" placeholder="<?php echo $text_enter_product; ?>" class="form-control" onmousedown="cartautocomplete(<?php echo $upsell_row; ?>);"/>
                          <div id="cart-products<?php echo $upsell_row; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                            <?php foreach ($upsell['cart_products'] as $product) { ?>
                            <div id="cart-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> <?php echo $product['name']; ?>
                              <input type="hidden" name="<?php echo $_name; ?>_upsell_data[<?php echo $upsell_row; ?>][cart_products][]" value="<?php echo $product['product_id']; ?>" />
                            </div>
                            <?php } ?>
                          </div>
                          <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_enter_product_faq; ?></div>
                        </td>
                        <td class="text-left" style="vertical-align:top">
                          <select name="<?php echo $_name; ?>_upsell_data[<?php echo $upsell_row; ?>][type]" class="form-control">
                            <option value="0" <?php echo $upsell['type'] == 0 ? 'selected="selected"' : ''; ?> ><?php echo $text_make_a_choice; ?></option>
                            <option value="1" <?php echo $upsell['type'] == 1 ? 'selected="selected"' : ''; ?> ><?php echo $text_get_from_related_products; ?></option>
                            <option value="2" <?php echo $upsell['type'] == 2 ? 'selected="selected"' : ''; ?> ><?php echo $text_select_products; ?></option>
                            <option value="3" <?php echo $upsell['type'] == 3 ? 'selected="selected"' : ''; ?> ><?php echo $text_combo_products; ?></option>
                          </select>
                          <div class="special-margin"></div>
                          <div style="<?php echo ($upsell['type'] == 2 || $upsell['type'] == 3) ? '' : 'display:none;'; ?>">
                            <input type="text" name="product_upsell<?php echo $upsell_row; ?>" value="" placeholder="<?php echo $text_enter_product; ?>" class="form-control" onmousedown="upsellautocomplete(<?php echo $upsell_row; ?>);"/>
                            <div id="upsell-products<?php echo $upsell_row; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                              <?php foreach ($upsell['upsell_products'] as $product) { ?>
                              <div id="upsell-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> <?php echo $product['name']; ?>
                                <input type="hidden" name="<?php echo $_name; ?>_upsell_data[<?php echo $upsell_row; ?>][upsell_products][]" value="<?php echo $product['product_id']; ?>" />
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </td>
                        <td class="text-left">
                          <button type="button" onclick="$('#upsell_row<?php echo $upsell_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                        </td>
                      </tr>
                      <?php $upsell_row++; ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="text-left"><button type="button" onclick="addUpsell();" data-toggle="tooltip" title="<?php echo $button_add_upsell; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <!-- TAB Support General block -->
                <div class="tab-pane fade" role="tabpanel" id="support-general-block">
                  <?php if (isset($support_info['general'])) { ?>
                    <?php echo $support_info['general']; ?>
                  <?php } else { ?>
                    <div class="alert alert-danger" role="alert"><i class="fa fa-info-circle"></i> <?php echo $error_data_load_error; ?></div>
                  <?php } ?>
                </div>
                <!-- TAB Support Extension block -->
                <div class="tab-pane fade" role="tabpanel" id="support-extension-block">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_license_text; ?></label>
                    <div class="col-sm-10">
                      <p style="margin-top: 9px;"><?php echo $license_type; ?></p>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_license_text_faq; ?></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_license_holder; ?></label>
                    <div class="col-sm-10">
                      <p style="margin-top: 9px;"><?php echo $license_holder; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_license_expires; ?></label>
                    <div class="col-sm-10">
                      <p style="margin-top: 9px;"><?php echo $license_expire; ?> <?php if ($license_expire_status == 0) { ?><i class="fa fa-refresh fa-spin fa-fw"></i> <b><a href="http://help.ocdevwizard.com" target="_blank">[<?php echo $text_renew_my_license; ?>]</a></b><?php } ?></p>
                      <?php if ($license_expire_status == 0) { ?>
                      <div class="alert alert-danger" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_license_expires_faq_0; ?></div>
                      <?php } elseif ($license_expire_status == 1) { ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_license_expires_faq_1; ?></div>
                      <?php } elseif ($license_expire_status == 2) { ?>
                      <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_license_expires_faq_2; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php if ($products) { ?>
                  <?php foreach ($products as $product) { ?>
                  <?php if ($product['extension_id'] == 33733) { ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_installed_module_name; ?></label>
                    <div class="col-sm-10">
                      <p style="margin-top: 9px;"><i class="fa fa-external-link"></i> <a href="<?php echo $product['url']; ?>" target="_blank"><?php echo $product['title']; ?></a></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_installed_module_version; ?></label>
                    <div class="col-sm-10">
                      <?php $_tmp_module_version = version_compare($_version, $product['latest_version']); ?>
                      <p style="margin-top: 9px;"><?php echo $_version; ?> <?php if ($_tmp_module_version == "-1") { ?><i class="fa fa-refresh fa-spin fa-fw"></i> <b><a href="<?php echo $product['url']; ?>" target="_blank">[<?php echo $text_new_module_version; ?> <?php echo $product['latest_version']; ?>]</a></b><?php } ?></p>
                    </div>
                  </div>
                  <?php } ?>
                  <?php } ?>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_opencart_version; ?></label>
                    <div class="col-sm-10">
                      <p style="margin-top: 9px;"><?php echo $opencart_version; ?></p>
                    </div>
                  </div>
                </div>
                <!-- TAB Support General block -->
                <div class="tab-pane fade" role="tabpanel" id="support-terms-block">
                  <?php if (isset($support_info['terms'])) { ?>
                    <?php echo $support_info['terms']; ?>
                  <?php } else { ?>
                    <div class="alert alert-danger" role="alert"><i class="fa fa-info-circle"></i> <?php echo $error_data_load_error; ?></div>
                  <?php } ?>
                </div>
                <!-- TAB Support Faq block -->
                <div class="tab-pane fade" role="tabpanel" id="support-faq-block">
                  <?php if (isset($support_info['faq'])) { ?>
                    <?php echo $support_info['faq']; ?>
                  <?php } else { ?>
                    <div class="alert alert-danger" role="alert"><i class="fa fa-info-circle"></i> <?php echo $error_data_load_error; ?></div>
                  <?php } ?>
                </div>
                <!-- TAB OCdev Products -->
                <div class="tab-pane fade" role="tabpanel" id="promo-block">
                  <?php if ($products) { ?>
                  <div class="row">
                    <?php foreach ($products as $product) { ?>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                      <button type="button" class="thumbnail" data-promo-product-id="<?php echo $product['extension_id']; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $button_read_more; ?>" >
                        <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['title']; ?>" width="100%" />
                      </button>
                    </div>
                    <?php } ?>
                  </div>
                  <?php } else { ?>
                  <div class="alert alert-danger" role="alert"><i class="fa fa-info-circle"></i> <?php echo $error_data_load_error; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div>
                <input type="hidden" style="display:none;" name="<?php echo $_name; ?>_license" value="<?php echo $license_key; ?>" />
                <input type="hidden" style="display:none;" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[front_module_name]" value="<?php echo $heading_title; ?>" />
                <input type="hidden" style="display:none;" name="r4k4j5k4q4f584a4j5b4t5j416q464p4w5j484p5q544g4w5u5z3a4h4r4i464b464q4j5[front_module_version]" value="<?php echo $_version; ?>" />
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- start: code for tab OCdev products -->
<script type="text/javascript">
$(document).delegate('button[data-promo-product-id]', 'click', function(e) {
  e.preventDefault();

  $('#modal-promo-product').remove();

  var element = this;

  $(element).tooltip('hide');

  $.ajax({
    url: 'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>/get_promo_products&<?php echo $token; ?>&extension_id='+$(element).attr('data-promo-product-id'),
    type: 'get',
    dataType: 'json',
    success: function(json) {
      html = '';
      if (json['product']) {
        html += '<div id="modal-promo-product" class="modal fade">';
        html += '  <div class="modal-dialog modal-mf" style="max-width:450px;">';
        html += '    <div class="modal-content">';
        html += '      <div class="modal-header">';
        html += '        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>';
        html += '        <h4 class="modal-title" id="myModalLabel">'+json['product']['title']+'</h4>';
        html += '      </div>';
        html += '      <div class="modal-body">';
        html += '        <div role="tabpanel">';
        html += '          <ul class="nav nav-tabs" role="tablist">';
        html += '            <li class="active"><a href="#modal-info" data-toggle="tab"><i class="fa fa-info-circle"></i> <?php echo $tab_modal_info; ?></a></li>';
        html += '            <li><a href="#modal-opencart-version" data-toggle="tab"><i class="fa fa-check-circle"></i> <?php echo $tab_modal_for_opencart; ?></a></li>';
        html += '            <li><a href="#modal-features" data-toggle="tab"><i class="fa fa-star"></i> <?php echo $tab_modal_features; ?></a></li>';
        html += '          </ul>';
        html += '          <div class="tab-content">';
        html += '            <div class="tab-pane active" id="modal-info">';
        html += '              <ul class="list-group">';
        html += '                <li class="list-group-item"><?php echo $text_modal_price; ?> <b class="pull-right">'+json['product']['price']+'</b></li>';
        html += '                <li class="list-group-item"><?php echo $text_modal_date_added; ?> <b class="pull-right">'+json['product']['date_added']+'</b></li>';
        html += '                <li class="list-group-item"><?php echo $text_modal_latest_version; ?> <b class="pull-right">'+json['product']['latest_version']+'</b></li>';
        html += '              </ul>';
        html += '            </div>';
        html += '            <div class="tab-pane" id="modal-opencart-version">';
        html += '              <ul class="list-group">';
        html += '                <li class="list-group-item">';
        html += '                  <div class="row">';
                                   $.each(json['opencart_version_array'], function(i,value) {
        html += '                    <div class="col-xs-6 col-md-2 col-sm-3">'+value+'</div>';
                                   });
        html += '                  </div>';
        html += '                </li>';
        html += '              </ul>';
        html += '            </div>';
        html += '            <div class="tab-pane" id="modal-features">';
        html += '              <ul class="list-group">';
        html += '                <li class="list-group-item">';
        html += '                  <div class="row">';
                                   $.each(json['opencart_features_array'], function(i,value) {
        html += '                    <div class="col-xs-12 col-md-12 col-sm-12">'+value+'</div>';
                                   });
        html += '                  </div>';
        html += '                </li>';
        html += '              </ul>';
        html += '            </div>';
        html += '          </div>';
        html += '        </div>';
        html += '        <a href="'+json['product']['url']+'" target="_blank" class="btn btn-primary" style="width:100%;"><i class="fa fa-external-link"></i> <?php echo $button_visit_sales_page; ?></a>';
        html += '      </div>  ';
        html += '    </div';
        html += '  </div>';
        html += '</div>';
      }

      $('body').append(html);

      $('#modal-promo-product').modal('show');
    }
  });
});
</script>
<!-- end: code for tab OCdev products -->
<!-- start: code for tab Popup setting -->
<script type="text/javascript">
$(document).delegate('button[data-background-image-id]', 'click', function(e) {
  e.preventDefault();

  $('#modal-background-image').remove();

  var element = this;

  html  = '<div id="modal-background-image" class="modal fade">';
  html += '  <div class="modal-dialog modal-mf">';
  html += '    <div class="modal-content">';
  html += '      <div class="modal-header">';
  html += '        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>';
  html += '        <h4 class="modal-title" id="myModalLabel"><?php echo $text_preview_image; ?></h4>';
  html += '      </div>';
  html += '      <div class="modal-body">';
  html += '        <div style="background:url('+$(element).attr('data-background-image-src')+');width: 100%;height: 500px;"></div>';
  html += '      </div>';
  html += '      <div class="modal-footer">';
  html += '        <button type="button" class="btn btn-info" onclick="button_apply_image(\''+$(element).attr('data-background-image-id')+'\');"><?php echo $button_select_image; ?></button>';
  html += '      </div>';
  html += '    </div';
  html += '  </div>';
  html += '</div>';

  $('body').append(html);

  $('#modal-background-image').modal('show');
});

function button_apply_image(id) {
  $('.div-background-images input[type=\'checkbox\']').attr('checked', false);
  $('#label-img-'+id).attr('checked', true);
  $('#modal-background-image').modal('hide');
}
</script>
<!-- end: code for tab Popup setting -->
<!-- start: code for tab CSS setting -->
<script type="text/javascript">
var codemirror = CodeMirror.fromTextArea(document.querySelector('#edit-css-block-0'), {
  mode : "css",
  height: '500px',
  lineNumbers: true,
  autofocus: true,
  theme: 'monokai',
  lineWrapping: true
});

var codemirror2 = CodeMirror.fromTextArea(document.querySelector('#edit-css-block-1'), {
  mode : "css",
  height: '500px',
  lineNumbers: true,
  autofocus: true,
  theme: 'monokai',
  lineWrapping: true
});

$('a[href=\'#css-block\']').on('click', function() {
  setTimeout(function() {
    $(this).click();
    codemirror.refresh();
    codemirror2.refresh();
  }, 500);
});

function save_css(id, stylesheet) {
  if (id == '0') {
    var codemirror_code = codemirror;
  } else {
    var codemirror_code = codemirror2;
  }
  $.ajax({
    type: 'post',
    url:  'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>/save_css&<?php echo $token; ?>',
    data: 'code='+encodeURIComponent(codemirror_code.getValue())+'&stylesheet='+stylesheet,
    dataType: 'json',
    success: function(json) {
      if (json['error']) {
        $('#result-css-block-'+id).html('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> '+json['error']+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
      if (json['success']) {
        $('#result-css-block-'+id).html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+json['success']+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    }
  });
}

function restore_css(id, stylesheet, stylesheet_default) {
  $.ajax({
    type: 'post',
    url:  'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>/restore_css&<?php echo $token; ?>',
    data: 'stylesheet='+stylesheet+'&stylesheet_default='+stylesheet_default,
    dataType: 'json',
    success: function(json) {
      if (json['error']) {
        $('#result-css-block-'+id).html('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> '+json['error']+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
      if (json['success']) {
        $('#result-css-block-'+id).html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+json['success']+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        setTimeout(function() {
          location.reload();
        }, 2000);
      }
    }
  });
}
</script>
<!-- end: code for tab CSS setting -->
<!-- start: code for tab Marketing Tools -->
<script type="text/javascript">
var upsell_row = <?php echo $upsell_row; ?>;

function addUpsell() {
  html  = '<tr id="upsell_row'+upsell_row+'">';
  html += '   <td class="text-left" style="vertical-align:top">';
  html += '   <input type="text" name="product_cart'+upsell_row+'" value="" placeholder="<?php echo $text_enter_product; ?>" class="form-control" />';
  html += '   <div id="cart-products'+upsell_row+'" class="well well-sm" style="height: 150px; overflow: auto;"></div>';
  html += '   <div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i> <?php echo $text_enter_product_faq; ?></div>';
  html += '   </td>';
  html += '   <td class="text-left" style="vertical-align:top">';
  html += '     <select name="<?php echo $_name; ?>_upsell_data['+upsell_row+'][type]" class="form-control">';
  html += '       <option value="0" selected="selected"><?php echo $text_make_a_choice; ?></option>';
  html += '       <option value="1"><?php echo $text_get_from_related_products; ?></option>';
  html += '       <option value="2"><?php echo $text_select_products; ?></option>';
  html += '       <option value="3"><?php echo $text_combo_products; ?></option>';
  html += '     </select>';
  html += '   <div class="special-margin"></div>';
  html += '   <div style="display:none;">';
  html += '     <input type="text" name="product_upsell'+upsell_row+'" value="" placeholder="<?php echo $text_enter_product; ?>" class="form-control" />';
  html += '     <div id="upsell-products'+upsell_row+'" class="well well-sm" style="height: 150px; overflow: auto;"></div>';
  html += '   </div>';
  html += '   </td>';
  html += '   <td class="text-left"><button type="button" onclick="$(\'#upsell_row'+upsell_row+'\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#upsell-blocks tbody').append(html);

  cartautocomplete(upsell_row);
  upsellautocomplete(upsell_row);

  $('select[name*=type]').change(function() {
    var val = $(this).val();
    if (val == 2 || val == 3) {
      $(this).next().next().show();
    } else {
      $(this).next().next().hide();
    }
  });

  upsell_row++;
}

$('select[name*=type]').change(function() {
  var val = $(this).val();
  if (val == 2 || val == 3) {
    $(this).next().next().show();
  } else {
    $(this).next().next().hide();
  }
});

function cartautocomplete(upsell_row) {
  $('input[name=product_cart'+upsell_row+']').autocomplete({
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>/autocomplete_product&<?php echo $token; ?>&filter_type=1&filter_name='+ encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['name'],
              value: item['product_id']
            }
          }));

        }
      });
    },
    select: function(item) {
      $('input[name=product_cart'+upsell_row+']').val('');
      $('#cart-product'+item['value']).remove();
      $('#cart-products'+upsell_row).append('<div id="cart-product'+item['value']+'"><i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> '+item['label']+'<input type="hidden" name="<?php echo $_name; ?>_upsell_data['+upsell_row+'][cart_products][]" value="'+item['value']+'" /></div>');
    }
  });
}

function upsellautocomplete(upsell_row) {
  $('input[name=product_upsell'+upsell_row+']').autocomplete({
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>/autocomplete_product&<?php echo $token; ?>&filter_type=2&filter_name='+ encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['name'],
              value: item['product_id']
            }
          }));

        }
      });
    },
    select: function(item) {
      $('input[name=product_upsell'+upsell_row+']').val('');
      $('#upsell-products'+upsell_row+' #upsell-product'+item['value']).remove();
      $('#upsell-products'+upsell_row).append('<div id="upsell-product'+item['value']+'"><i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> '+item['label']+'<input type="hidden" name="<?php echo $_name; ?>_upsell_data['+upsell_row+'][upsell_products][]" value="'+item['value']+'" /></div>');
    }
  });
}
</script>
<!-- end: code for tab Marketing Tools -->
<!-- start: code for tab Language setting -->
<script type="text/javascript">
function texteditor_action({id = '', destroy = false, start = true} = {}) {
  if (start) {
    $(id).summernote({focus: true});

    $(id).parent().next().find('button:eq(1)').show();

    if ($(id).summernote('isEmpty')) {
      $(id).val('');
    }
  }

  if (destroy) {
    $(id).summernote('destroy');
    $(id).parent().next().find('button:eq(1)').hide();
  }
}
</script>
<!-- end: code for tab Language setting -->
<!-- start: code for tab Import/Export module setting -->
<script type="text/javascript">
$('#module-load-file').change(function(){
  $('#module-load-file-mask').val($(this).val());
  $('#module-button-import-file-1').attr('disabled', false);
});

$('select[name=\'module_backup_file_name\']').change(function(){
  if ($(this).val()) {
    $('#module-button-import-file-2').attr('disabled', false);
  } else {
    $('#module-button-import-file-2').attr('disabled', true);
  }
});

$('#module-button-import-file-2').on('click', function(){
  $.ajax({
    type: 'post',
    url:  'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>/import_module_settings&<?php echo $token; ?>&store_id=<?php echo $store_id; ?>',
    data: 'file_name='+$('select[name=\'module_backup_file_name\']').val(),
    dataType: 'json',
    success: function(json) {
      if (json['success']) {
        $('#top-alerts').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+json['success']+' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        setTimeout(function() {
          location.reload();
        }, 2000);
      }
    }
  });
});
</script>
<!-- end: code for tab Import/Export module setting -->
<!-- start: code for module usability -->
<script type="text/javascript">
if (window.localStorage && window.localStorage['last_active_tab']) {
  $('#setting-tabs a[href='+window.localStorage['last_active_tab']+']').trigger('click').addClass('list-group-item-warning').prepend('<i class="fa fa-chevron-right"></i>');
  $('body,html').animate({
    scrollTop: 0
  }, 800);
}

$('#setting-tabs a[data-toggle="tab"]').click(function() {
  if (window.localStorage) {
    window.localStorage['last_active_tab'] = $(this).attr('href');
  }
  $('#setting-tabs a[data-toggle="tab"]').removeClass('list-group-item-warning').find('i[class=\'fa fa-chevron-right\']').remove();
  $(this).addClass('list-group-item-warning').prepend('<i class="fa fa-chevron-right"></i>');
  $('body, html').animate({
    scrollTop: 0
  }, 800);
});

$('.btn-toggle').on('click', '.btn', function() {
  if(!$(this).hasClass('disabled')){
    $(this).addClass('btn-success').siblings().removeClass('btn-success').addClass('btn-default');
  }
});

$('.btn-toggle').on('click', '.disabled', function() {
  return false;
});

$('body').on('hidden.bs.modal', function () {
  if ($('.modal.in').length > 0) {
    $('body').addClass('modal-open');
  }
});

$(document).delegate('button[data-faq-target]', 'click', function(e) {
  e.preventDefault();

  $('#modal-faq').remove();

  var element = this;

  html  = '<div id="modal-faq" class="modal fade bs-example-modal-lg">';
  html += '  <div class="modal-dialog modal-lg">';
  html += '    <div class="modal-content">';
  html += '      <div class="modal-header">';
  html += '        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>';
  html += '        <h4 class="modal-title" id="myModalLabel"><?php echo $text_preview_image; ?></h4>';
  html += '      </div>';
  html += '      <div class="modal-body">';
  html += '        <img src="http://images.ocdevwizard.com/<?php echo $_name; ?>/'+$(element).attr('data-faq-target')+'.gif" width="100%" />';
  html += '      </div>';
  html += '      <div class="modal-footer">';
  html += '        <a href="http://images.ocdevwizard.com/<?php echo $_name; ?>/'+$(element).attr('data-faq-target')+'.gif" class="btn btn-info" target="_blank"><?php echo $button_open_image_in_original_size; ?></a>';
  html += '      </div>';
  html += '    </div';
  html += '  </div>';
  html += '</div>';

  $('body').append(html);

  $('#modal-faq').modal('show');
});

$(function(){
  if ($('.pro-block').length) {
    $('.pro-block').each(function(index) {
      $(this).find('.control-label').append('<div class="clear"></div><div class="label label-info" style="text-transform: uppercase;"><?php echo $text_available_in_pro_version; ?></div>');
      $(this).find('input[type=\'radio\'], input[type=\'checkbox\'], select, button').attr('disabled', true);
      $(this).find('input[type=\'text\'], textarea').on('focus', function(){ alert('<?php echo $text_available_in_pro_version; ?>'); });
      $(this).find('label').addClass('disabled');
      $(this).addClass('pro-version-only');
    });
  }
});
</script>
<!-- end: code for module usability -->
<?php echo $footer; ?>
