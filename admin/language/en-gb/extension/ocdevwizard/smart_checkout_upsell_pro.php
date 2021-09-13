<?php
##==================================================================##
## @author    : OCdevWizard                                         ##
## @contact   : ocdevwizard@gmail.com                               ##
## @support   : http://help.ocdevwizard.com                         ##
## @license   : http://license.ocdevwizard.com/Licensing_Policy.pdf ##
## @copyright : (c) OCdevWizard. Smart Checkout Upsell Pro, 2018    ##
##==================================================================##

// Module info
$_['compatible_version']                               = '2.0.0.0|2.0.1.0|2.0.1.1|2.0.2.0|2.0.3.1|2.1.0.1|2.1.0.2|2.2.0.0|2.3.0.2|3.0.0.0|3.0.1.1|3.0.1.2|3.0.2.0'; # OpenCart version
$_['compatible_version']                              .= '|2.1.0.1.1|2.1.0.2.1|2.3.0.2.1|2.3.0.2.2|2.3.0.2.3'; # ocStore version

// Main
$_['heading_title']                                    = 'Smart Checkout Upsell Pro';
$_['button_save']                                      = 'Save';
$_['button_save_and_stay']                             = 'Save and stay';
$_['button_uninstall']                                 = 'Uninstall module';
$_['button_uninstall_and_remove']                      = 'Uninstall module and remove his files';
$_['button_restore']                                   = 'Restore to default settings';
$_['button_cache']                                     = 'Clear module cache';
$_['button_cache_backup']                              = 'Clear local backup files';
$_['button_cancel']                                    = 'Cancel';
$_['tab_control_panel']                                = 'Control panel';
$_['tab_layout_setting']                               = 'Layout setting';
$_['tab_language_setting']                             = 'Language setting';
$_['tab_marketing_tools_setting']                      = 'Marketing Tools';
$_['tab_support_setting']                              = 'Support';
$_['text_setting_left_menu']                           = 'Main settings';
$_['text_select_store']                                = 'Select store config';
$_['text_available_in_pro_version']                    = 'Available in pro plus version';
$_['text_exclusive']                                   = 'Exclusive';

// Assistance
$_['text_make_a_choice']                               = '-- Make a choice --';
$_['text_yes']                                         = 'Yes';
$_['text_no']                                          = 'No';
$_['text_select_all']                                  = 'Select all';
$_['text_unselect_all']                                = 'Unselect all';
$_['text_randomize']                                   = 'Randomize products in module block:';
$_['text_enter_product']                               = 'Products';
$_['text_enter_product_faq']                           = 'Type <b>ALL</b> if you need to use all products from your store.';
$_['text_dementions_of_main_image']                    = 'Dementions of products image:';
$_['text_warning_dementions_of_main_image']            = 'If you have changed the size of the image then you need to adjust settings in the stylesheet module.';
$_['text_image_width_ph']                              = 'Width, (px)';
$_['text_image_height_ph']                             = 'Height, (px)';
$_['text_all_products']                                = '-- All products --';
$_['text_are_you_sure']                                = 'Are you sure?';
$_['button_close']                                     = 'Close';
$_['button_open_image_in_original_size']               = 'Open image in original size';
$_['text_open_example']                                = 'Open example';
$_['button_loading']                                   = 'Loading...';
$_['text_px']                                          = 'px';
$_['text_preview_image']                               = 'Preview image';
$_['text_open_texteditor']                             = 'Open WYSIWYG editor';
$_['text_save_texteditor']                             = 'Save changes';
$_['text_css_id_indicator']                            = 'id=';
$_['text_css_class_indicator']                         = 'class=';
$_['text_onclick_indicator']                           = 'onclick=';
$_['text_route_indicator']                             = '?route=';
$_['text_js_indicator']                                = 'JS';
$_['text_width_indicator']                             = 'width=';
$_['text_height_indicator']                            = 'height=';
$_['button_view_shortcodes']                           = 'Available short-codes';

// Tab - General setting
$_['tab_general_setting']                              = 'General setting';
$_['text_activate_module']                             = 'Activate module:';
$_['text_main_product_id_selector']                    = 'Product ID selector on the product page:';
$_['text_main_product_id_selector_faq']                = 'This selector helps to find Product ID value on the product page.';
$_['text_route_to_system_add_method']                  = 'System URL to Add product function:';
$_['text_route_to_system_add_method_faq']              = 'This selector helps to get system route to Add Product function.';
$_['text_customer_groups']                             = 'Customer groups:';
$_['text_customer_groups_faq']                         = 'Select the customer groups with which the module should work.';

// Tab - Basic setting
$_['tab_basic_setting']                                = 'Basic setting';
$_['text_direction_type']                              = 'Select text direction type:';
$_['text_direction_type_1']                            = 'LTR (left to right)';
$_['text_direction_type_2']                            = 'RTL (right to left)';
$_['text_minify_main_js']                              = 'Minify module JS file:';
$_['text_minify_main_js_1']                            = 'Low level';
$_['text_minify_main_js_2']                            = 'Medium level';

// Tab - Product widget setting
$_['tab_product_widget_setting']                       = 'Product widget setting';
$_['text_activate_product_widget']                     = 'Activate widget:';
$_['text_activate_product_widget_faq_1']               = 'This widget based on default opencart template styles and markup, so if you have third party template using only own styles and markup, then you must be ready to customize this widget for your template (this additional task is not free). More info on the tab <a style="cursor:pointer;" onclick="$(\'[href=#support-general-block]\').click();"><b>Support information</b></a>.';
$_['text_activate_product_widget_faq_2']               = 'This widget is intended for product page only (route product/product). Widget is displaying upsell products for current product page. If you intended widget outside of product page then widget is displaying upsell products for each product of the shopping cart.';
$_['text_insert_widget']                               = 'Selector for widget:';
$_['text_insert_widget_faq']                           = 'This selector(s) helps to insert widget on the product page. You can write more than one value, in this case they should be separated with a new line.';
$_['text_product_widget_show_product_image']           = 'Show product image:';
$_['text_product_widget_show_product_price']           = 'Show product price:';
$_['text_product_widget_show_product_name']            = 'Show product name:';
$_['text_product_widget_show_product_description']     = 'Show product description:';
$_['text_product_widget_product_description_limit']    = 'Description limit:';
$_['text_product_widget_show_product_rating']          = 'Show product rating:';
$_['text_product_widget_show_product_cart_button']     = 'Show product add to cart button:';
$_['text_product_widget_show_product_wishlist_button'] = 'Show product add to wishlist button:';
$_['text_product_widget_show_product_compare_button']  = 'Show product add to compare button:';
$_['text_product_widget_randomize']                    = 'Randomize products:';
$_['text_product_widget_product_limit']                = 'Product limit:';

// Tab - Cart widget setting
$_['tab_cart_widget_setting']                          = 'Cart widget setting';
$_['text_activate_cart_widget']                        = 'Activate widget:';
$_['text_activate_cart_widget_faq_1']                  = 'This widget based on default opencart template styles and markup, so if you have third party template using only own styles and markup, then you must be ready to customize this widget for your template (this additional task is not free). More info on the tab <a style="cursor:pointer;" onclick="$(\'[href=#support-general-block]\').click();"><b>Support information</b></a>.';
$_['text_activate_cart_widget_faq_2']                  = 'This widget is intended for cart page only (route checkout/cart) and not working on another pages. Widget is displaying upsell products for each product of the shopping cart.';
$_['text_cart_widget_show_product_image']              = 'Show product image:';
$_['text_cart_widget_show_product_price']              = 'Show product price:';
$_['text_cart_widget_show_product_name']               = 'Show product name:';
$_['text_cart_widget_show_product_description']        = 'Show product description:';
$_['text_cart_widget_product_description_limit']       = 'Description limit:';
$_['text_cart_widget_show_product_rating']             = 'Show product rating:';
$_['text_cart_widget_show_product_cart_button']        = 'Show product add to cart button:';
$_['text_cart_widget_show_product_wishlist_button']    = 'Show product add to wishlist button:';
$_['text_cart_widget_show_product_compare_button']     = 'Show product add to compare button:';
$_['text_cart_widget_randomize']                       = 'Randomize products:';
$_['text_cart_widget_product_limit']                   = 'Product limit:';

// Tab - Popup widget setting
$_['tab_popup_widget_setting']                         = 'Popup widget setting';
$_['text_activate_popup_widget']                       = 'Activate widget:';
$_['text_activate_popup_widget_faq']                   = 'This widget is intended for popup module only. Widget is displaying upsell products in a moment when product added to cart. If you using "Smart Popup Cart Pro\Plus" or "Smart Popup Abandoned Cart Pro\Plus" then widget will not working.';
$_['text_popup_widget_show_product_image']             = 'Show product image:';
$_['text_popup_widget_show_product_price']             = 'Show product price:';
$_['text_popup_widget_show_product_name']              = 'Show product name:';
$_['text_popup_widget_show_product_description']       = 'Show product description:';
$_['text_popup_widget_product_description_limit']      = 'Description limit:';
$_['text_popup_widget_show_product_rating']            = 'Show product rating:';
$_['text_popup_widget_show_product_cart_button']       = 'Show product add to cart button:';
$_['text_popup_widget_show_product_wishlist_button']   = 'Show product add to wishlist button:';
$_['text_popup_widget_show_product_compare_button']    = 'Show product add to compare button:';
$_['text_popup_widget_randomize']                      = 'Randomize products:';
$_['text_popup_widget_product_limit']                  = 'Product limit:';
$_['text_route_to_chekout_page']                       = 'System URL to Checkout page:';

// Tab - Static widget setting
$_['tab_static_widget_setting']                        = 'Static widget setting';
$_['text_activate_static_widget']                      = 'Activate widget:';
$_['text_activate_static_widget_faq_1']                = 'This widget based on default opencart template styles and markup, so if you have third party template using only own styles and markup, then you must be ready to customize this widget for your template (this additional task is not free). More info on the tab <a style="cursor:pointer;" onclick="$(\'[href=#support-general-block]\').click();"><b>Support information</b></a>.';
$_['text_activate_static_widget_faq_2']                = 'This widget is intended static module only. Widget is displaying upsell products for current product page. If you intended widget outside of product page then widget is displaying upsell products for each product of the shopping cart. Widget is displaying upsell products for each product of the shopping cart.';
$_['text_static_widget_show_product_image']            = 'Show product image:';
$_['text_static_widget_show_product_price']            = 'Show product price:';
$_['text_static_widget_show_product_name']             = 'Show product name:';
$_['text_static_widget_show_product_description']      = 'Show product description:';
$_['text_static_widget_product_description_limit']     = 'Description limit:';
$_['text_static_widget_show_product_rating']           = 'Show product rating:';
$_['text_static_widget_show_product_cart_button']      = 'Show product add to static button:';
$_['text_static_widget_show_product_wishlist_button']  = 'Show product add to wishlist button:';
$_['text_static_widget_show_product_compare_button']   = 'Show product add to compare button:';
$_['text_static_widget_randomize']                     = 'Randomize products:';
$_['text_static_widget_product_limit']                 = 'Product limit:';
$_['text_static_widget_position']                      = 'Layout position:';
$_['text_static_widget_position_1']                    = 'Column left';
$_['text_static_widget_position_2']                    = 'Column right';
$_['text_static_widget_position_3']                    = 'Content top';
$_['text_static_widget_position_4']                    = 'Content bottom';
$_['text_static_widget_position_faq']                  = 'You can select layout position where module will be displayed. This ability designed for static module and it works with the OpenCart v3.x and higher only.';
$_['text_static_widget_sort_order']                    = 'Sort order:';
$_['text_static_widget_sort_order_faq']                = 'You can select sort order for module. This ability designed for static module and it works with the OpenCart v3.x and higher only.';

// Tab - Layout setting
$_['tab_layout_setting']                               = 'Layout setting';
$_['text_hide_info_message']                           = 'Show additional information:';
$_['text_before_products_list']                        = 'Before products list';
$_['text_after_products_list']                         = 'After products list';
$_['text_hide_info_message_faq']                       = 'This additional information is displaying in the popup module only.';

// Tab - Css setting
$_['tab_css_setting']                                  = 'CSS setting';
$_['text_edit_css']                                    = 'Edit main stylesheet:';
$_['text_edit_css_rtl']                                = 'Edit stylesheet for RTL:';
$_['button_save_css']                                  = 'Save CSS';
$_['button_restore_css']                               = 'Restore default CSS';
$_['text_success_css_saved']                           = 'Success! CSS successfully saved!';
$_['text_success_css_restored']                        = 'Success! CSS successfully restored!';

// Tab - Popup setting
$_['tab_popup_setting']                                = 'Popup setting';
$_['text_background_images']                           = 'Background image for overlay:';
$_['button_select_image']                              = 'Select image';
$_['text_background_images_faq']                       = 'You can add your own images. How to do that? - You can find answer on the tab <a style="cursor:pointer;" onclick="$(\'[href=#support-faq-block]\').click();"><b>Frequently Asked Questions</b></a>.';
$_['text_popup_animation_type']                        = 'Animation effect:';
$_['text_popup_animation_type_1']                      = 'No effect';
$_['text_popup_animation_type_2']                      = 'Zoom-in';
$_['text_popup_animation_type_3']                      = 'Zoom-out';
$_['text_popup_animation_type_4']                      = 'Move from left';
$_['text_popup_animation_type_5']                      = 'Move from top';
$_['text_popup_animation_type_6']                      = '3d flip';
$_['text_popup_animation_type_7']                      = 'Newspaper';
$_['text_popup_animation_type_faq']                    = 'Animation effect for popup window.';
$_['text_background_opacity']                          = 'Background opacity:';
$_['text_background_opacity_faq']                      = '0 - transparent 100%, 10 - visible 100%.';

// Tab - Import/Export module setting
$_['tab_module_import_export_setting']                 = 'Import/Export module setting';
$_['button_export']                                    = 'Export';
$_['button_import']                                    = 'Import';
$_['text_restore_from_external_file']                  = 'Restore from external file:';
$_['text_restore_from_local_file']                     = 'Restore from local file:';
$_['text_export']                                      = 'Export module settings:';
$_['text_select_file']                                 = 'Select file';

// Tab - Language product widget setting
$_['tab_product_widget_language_setting']              = 'Product widget language setting';
$_['text_heading_product_widget']                      = 'The heading of the product widget:';
$_['text_heading_product_widget_faq']                  = 'Enter the heading of the widget.';
$_['text_heading_product_widget_sub_h1']               = 'Product short-codes';
$_['text_heading_product_widget_sub_c1']               = '<kbd>{product_name}</kbd> - for Product name.';
$_['default_heading_product_widget']                   = 'Accessories for {product_name}';
$_['text_alternative_heading_product_widget']          = 'The alternative heading of the product widget:';
$_['text_alternative_heading_product_widget_faq']      = 'Enter the alternative heading of the widget when the product data is not available.';
$_['default_alternative_heading_product_widget']       = 'Accessories';

// Tab - Language cart widget setting
$_['tab_cart_widget_language_setting']                 = 'Cart widget language setting';
$_['text_heading_cart_widget']                         = 'The heading of the cart widget:';
$_['text_heading_cart_widget_faq']                     = 'Enter the heading of the widget.';
$_['text_heading_cart_widget_sub_h1']                  = 'Product short-codes';
$_['text_heading_cart_widget_sub_c1']                  = '<kbd>{product_name}</kbd> - for Product name.';
$_['default_heading_cart_widget']                      = 'Accessories for {product_name}';

// Tab - Language popup widget setting
$_['tab_popup_widget_language_setting']                = 'Popup widget language setting';
$_['text_heading_popup_widget']                        = 'The heading of the popup widget:';
$_['text_heading_popup_widget_faq']                    = 'Enter the heading of the widget.';
$_['text_heading_popup_widget_sub_h1']                 = 'Product short-codes';
$_['text_heading_popup_widget_sub_c1']                 = '<kbd>{product_name}</kbd> - for Product name.';
$_['default_heading_popup_widget']                     = 'Accessories for {product_name}';
$_['text_alternative_heading_popup_widget']            = 'The alternative heading of the popup widget:';
$_['text_alternative_heading_popup_widget_faq']        = 'Enter the alternative heading of the widget when the product data is not available.';
$_['default_alternative_heading_popup_widget']         = 'Accessories';
$_['text_go_to_cart_button']                           = 'The text on "Go to Cart" button:';
$_['text_go_to_cart_button_faq']                       = 'Enter the text for Go to Cart button. This button displayed on the popup module.';
$_['default_go_to_cart_button']                        = 'Go to Cart';
$_['text_continue_shopping_button']                    = 'The text on "Continue Shopping" button:';
$_['text_continue_shopping_button_faq']                = 'Enter the text for Continue Shopping button. This button displayed on the popup module.';
$_['default_continue_shopping_button']                 = 'Continue shopping';
$_['text_info_message_popup_widget']                   = 'Additional information for customer:';
$_['text_info_message_popup_widget_faq']               = 'Enter some special offer or useful information for customer. This info will be display in the popup module.';
$_['text_info_message_popup_widget_sub_h1']            = 'Product short-codes';
$_['text_info_message_popup_widget_sub_c1']            = '<kbd>{product_name}</kbd> - for Product name.';
$_['default_info_message_popup_widget']                = 'Greetings.<br/>We are glad to offer you some useful stuff for product {product_name}.';
$_['text_alternative_info_message_popup_widget']       = 'Alternative additional information for customer:';
$_['text_alternative_info_message_popup_widget_faq']   = 'Enter some alternative special offer or useful information for customer when the product data is not available. This info will be display in the popup module.';
$_['default_alternative_info_message_popup_widget']    = 'Greetings.<br/>We are glad to offer you some useful stuff.';

// Tab - Language static widget setting
$_['tab_static_widget_language_setting']               = 'Static widget language setting';
$_['text_heading_static_widget']                       = 'The heading of the static widget:';
$_['text_heading_static_widget_faq']                   = 'Enter the heading of the widget.';
$_['text_heading_static_widget_sub_h1']                = 'Product short-codes';
$_['text_heading_static_widget_sub_c1']                = '<kbd>{product_name}</kbd> - for Product name.';
$_['default_heading_static_widget']                    = 'Accessories for {product_name}';
$_['text_alternative_heading_static_widget']           = 'The alternative heading of the static widget:';
$_['text_alternative_heading_static_widget_faq']       = 'Enter the alternative heading of the widget when the product data is not available.';
$_['default_alternative_heading_static_widget']        = 'Accessories';

// Tab - Up-sell products
$_['tab_up_sell_products_setting']                     = 'Up-sell products';
$_['text_up_sell_show_sub_images']                     = 'Show up-sell poducts image:';
$_['text_up_sell_dementions_of_sub_images']            = 'Dementions of up-sell prducts image:';
$_['text_up_sell_limit_cross_sell_products']           = 'Limit for up-sell poducts:';
$_['text_up_sell_show_product_addto_cart_button']      = 'Show AddToCart button in up-sell product:';
$_['text_up_sell_show_product_addto_wishlist_button']  = 'Show AddToWishlist button in up-sell product:';
$_['text_up_sell_show_product_addto_compare_button']   = 'Show AddToCompare button in up-sell product:';
$_['text_up_sell_show_product_price']                  = 'Show Price in up-sell product:';
$_['column_products_in_cart']                          = 'Products in shopping cart';
$_['column_recommended_products']                      = 'Recommended products';
$_['text_get_from_related_products']                   = 'Get from Related Products';
$_['text_select_products']                             = 'Select Products';
$_['text_combo_products']                              = 'Merge Related and Selected products';
$_['column_action']                                    = 'Action';
$_['button_add_upsell']                                = 'Add Upsell block';

// Tab - Support information
$_['tab_support_general_setting']                      = 'Support information';

// Tab - Extension information
$_['tab_support_extension_setting']                    = 'Extension information';
$_['text_license_key']                                 = 'Extension license code:';
$_['text_license_text']                                = 'License type:';
$_['text_license_text_faq']                            = 'You can upgrade your license, read more about license types on the <a style="cursor:pointer;" onclick="$(\'[href=#support-terms-block]\').click();"><b>Terms & Conditions</b></a>.';
$_['text_license_holder']                              = 'License holder:';
$_['text_license_expires']                             = 'License expires on:';
$_['text_license_date_end']                            = '%s (%s remaining)';
$_['text_license_expire_day_1']                        = 'day';
$_['text_license_expire_day_2']                        = 'days';
$_['text_license_expire_forever']                      = 'No time limit';
$_['text_license_end']                                 = 'Expired';
$_['button_renew_license']                             = 'Renew a license';
$_['text_license_expires_faq_0']                       = 'Limitation for a non-licensed extension:<br/><i class="fa fa-times"></i> Free technical support.<br/><i class="fa fa-times"></i> Using fresh extension updates.<br/><i class="fa fa-times"></i> Discount on additional work.<br/><i class="fa fa-times"></i> Response time on ticket up to 72 hours.';
$_['text_license_expires_faq_1']                       = 'Benefits of licensed extension:<br/><i class="fa fa-check"></i> Free technical support according with <a style="cursor:pointer;" onclick="$(\'[href=#support-general-block]\').click();"><b>Support information</b></a>.<br/><i class="fa fa-check"></i> Using fresh extension updates.<br/><i class="fa fa-check"></i> Discount on additional work (-30%).<br/><i class="fa fa-check"></i> Response time on ticket is less than 42 hours.';
$_['text_license_expires_faq_2']                       = 'After this period, you can use this module in accordance with the <a style="cursor:pointer;" onclick="$(\'[href=#support-terms-block]\').click();"><b>Terms & Conditions</b></a>, but you will not be able to use free technical support and fresh extension updates. Please update the license in a timely manner.';
$_['text_license_expire_ended']                        = 'You are running an unlicensed version of this extension! You can not get free technical support and using fresh updates until you activate this license!';
$_['text_installed_module_name']                       = 'Extension name:';
$_['text_installed_module_version']                    = 'Extension version:';
$_['text_new_module_version']                          = 'New version available:';
$_['text_opencart_version']                            = 'Current OpenCart version:';

// Tab - Terms & Conditions
$_['tab_support_terms_setting']                        = 'Terms & Conditions';

// Tab - Frequently Asked Questions
$_['tab_support_faq_setting']                          = 'Frequently Asked Questions';

// Tab - OCdev Products
$_['tab_promo_setting']                                = 'OCdev Products';
$_['tab_modal_info']                                   = 'Info';
$_['tab_modal_for_opencart']                           = 'For OpenCart';
$_['tab_modal_features']                               = 'Features';
$_['text_modal_price']                                 = 'Price:';
$_['text_modal_date_added']                            = 'Release:';
$_['text_modal_latest_version']                        = 'Latest version:';
$_['button_read_more']                                 = 'Click to read more...';
$_['button_visit_sales_page']                          = 'Visit sale page';

// Success
$_['text_success']                                     = 'Success! Settings of module '.$_['heading_title'].' is updated!';
$_['text_success_install']                             = 'Success! The module '.$_['heading_title'].' is successfully installed!';
$_['text_success_uninstall']                           = 'Success! The module '.$_['heading_title'].' is successfully uninstalled!';
$_['text_success_module_restored']                     = 'Success! Settings of module '.$_['heading_title'].' is restored!';
$_['text_success_cache']                               = 'Success! Module cache is successfully removed!';
$_['text_success_cache_backup']                        = 'Success! Backup files from local storage is successfully removed!';

// Error
$_['error_warning']                                    = 'Warning! Module settings will not be saved until you fix the errors. Please check the form carefully for errors!';
$_['error_permission']                                 = 'Warning! You are not authorized to change the module '.$_['heading_title'].'!';
$_['error_access_permission']                          = 'Permission Denied! You do not have permission to access the module '.$_['heading_title'].', please expand user permission on the <a href="%s" style="text-decoration:underline;">User Groups</a> page.';
$_['error_for_all_field']                              = 'Warning! This field must not be empty!';
$_['error_compatible_version']                         = 'Warning! You have installed a incompatible version of module for this opencart shop!';
$_['error_data_load_error']                            = 'Warning! Failed to load information! Probably this is a temporary error that does not affect on your store functionality!';
$_['error_failed_load_stylesheet']                     = 'Warning! Stylesheet file not loaded!';
$_['error_license_key']                                = 'Warning! Enter the license key!';
