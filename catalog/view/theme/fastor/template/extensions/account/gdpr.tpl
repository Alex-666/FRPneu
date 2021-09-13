<?php echo $header;
$theme_options = $registry->get('theme_options');
$config = $registry->get('config');
include('catalog/view/theme/' . $config->get('theme_' . $config->get('config_theme') . '_directory') . '/template/new_elements/wrapper_top.tpl'); ?>


  <h3><?php echo $text_right_to_data_rectification; ?></h3>
  <p><?php echo $text_data_rectification_help; ?></p>
  <ul>
    <li><a href="<?php echo $edit_account; ?>" id="edit-account"><?php echo $text_edit_account;; ?></a></li>
    <li><a href="<?php echo $edit_address; ?>" id="edit-address"><?php echo $text_edit_address; ?></a></li>
    <li><a href="<?php echo $edit_password; ?>" id="edit-password"><?php echo $text_edit_password; ?></a></li>
    <li><a href="<?php echo $edit_newsletter; ?>" id="edit-newsletter"><?php echo $text_edit_newsletter; ?></a></li>
  </ul>

  <h3><?php echo $text_right_to_data_portability; ?></h3>
  <p><?php echo $text_data_portablity_help; ?></p>
  <ul>
    <li><a href="<?php echo $download_account_data; ?>" id="download-account"><?php echo $text_download_account_data; ?></a></li>
    <li><a href="<?php echo $download_address_data; ?>" id="download-addresses"><?php echo $text_download_address_data; ?></a></li>
    <li><a href="<?php echo $download_order_data; ?>" id="download-orders"><?php echo $text_download_order_data; ?></a></li>
    <li><a href="<?php echo $download_gdpr_requests_data; ?>" id="download-gdpr-requests"><?php echo $text_download_gdpr_requests_data; ?></a></li>
  <ul>

  <h3><?php echo $text_right_to_restriction_of_processing; ?></h3>
  <p><a href="<?php echo $gdpr_restrict_processing; ?>"><?php echo $text_right_to_restriction_of_processing; ?></a></p>

  <h3><?php echo $text_right_to_data_access; ?></h3>
  <p><?php echo $text_gdpr_request_help; ?></p>
  <p><a href="<?php echo $gdpr_request; ?>"><?php echo $text_gdpr_request; ?></a></p>

  <?php if($gdpr_settings['gdpr_right_to_be_forgotten']) { ?>
    <h3><?php echo $text_right_to_be_forgotten; ?></h3>
    <p><?php echo $text_gdpr_forget_me_help; ?> <br><strong><?php echo $text_gdpr_forget_me_warning; ?></strong></p>
    <p><a href="<?php echo $gdpr_forget_me; ?>"><?php echo $text_gdpr_forget_me; ?></a></p>
  <?php } ?>

  <div class="buttons clearfix">
    <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
  </div>

<?php include('catalog/view/theme/' . $config->get('theme_' . $config->get('config_theme') . '_directory') . '/template/new_elements/wrapper_bottom.tpl'); ?>
<?php echo $footer; ?>
