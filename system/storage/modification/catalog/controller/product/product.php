<?php

                        
    global $aFolder;
    global $modulesPath;
    
    
    
    if (!defined('HTTP_ADMIN')) {
        $root_dir = DIR_APPLICATION.'../';
        $folder_contents = scandir($root_dir);
                if (!(in_array('admin', $folder_contents) && file_exists($root_dir.'admin/config.php'))) {
                        foreach ($folder_contents as $value) {
                                if (is_dir($root_dir.$value) && $value != '.' && $value != '..'){
                                        if (file_exists($root_dir.$value.'/config.php')) {
                                                $admin_folder_name = $value;
                                                continue;
                                        }
                                }
                        }
                }
        if (isset($admin_folder_name)) {
                define('HTTP_ADMIN',$admin_folder_name);
        } else {
                define('HTTP_ADMIN','admin');
        }
        
    }
    
    $aFolder = preg_replace('/.*\/([^\/].*)\//is','$1',HTTP_ADMIN);
    
    if (version_compare(VERSION,'2.3','>=')) { //newer than 2.2.x
        $modulesPath = 'extension/module';
    } else {
        $modulesPath = 'module';
    }

    include (preg_match("/components\/com_(ayelshop|aceshop|mijoshop)\/opencart\//ims",__FILE__,$matches)?'components/com_'.$matches[1].'/opencart/':'').$aFolder.'/controller/'.$modulesPath.'/magictoolbox-module.inc';

class ControllerProductProduct extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('product/product');
$this->load->model('setting/setting');
$this->load->model('setting/setting');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->load->model('catalog/category');

        if (isset($this->request->get['path'])) {
            $path = '';

            $parts = explode('_', (string)$this->request->get['path']);

            $category_id = (int)array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = $path_id;
                } else {
                    $path .= '_' . $path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);
                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path)
                    );
                }
                if ($path_id == 139 || $path_id == 156){
                    $data['warranty'] = $category_info['name'];
                    $data['main_category_id'] = $category_info['category_id'];

                }

                // Для Кнопки в Шинах не грузовых, Дисках и Стеклах
                If (in_array($path_id, array("297", "303", "383")) ) {
                    $data['pneu_not_nakladni'] = "enable";
                }
                If ($path_id =="156" ) {
                    $data['disky'] = "enable";

                }
                If ($category_id =="1287") {
                    $data['sklo'] = "enable";
                }
            }

            // Set the last category breadcrumb
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $url = '';

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->get['limit'])) {
                    $url .= '&limit=' . $this->request->get['limit'];
                }

                $data['breadcrumbs'][] = array(
                    'text' => $category_info['name'],
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
                );
            }
        }

        $data ['text_button_trusko'] = $this->language->get('text_button_trusko');
        $data ['text_button_brna'] = $this->language->get('text_button_brna');
        $data ['text_button_sklo'] = $this->language->get('text_button_sklo');

        $data['text_year'] = $this->language->get('text_year');
        $data['text_includes_recycling'] = $this->language->get('text_includes_recycling');
        $data['text_recycling_used_tires'] = $this->language->get('text_recycling_used_tires');

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->get['manufacturer_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_brand'),
                'href' => $this->url->link('product/manufacturer')
            );

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

            if ($manufacturer_info) {
                $data['breadcrumbs'][] = array(
                    'text' => $manufacturer_info['name'],
                    'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
                );
            }
        }

        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_search'),
                'href' => $this->url->link('product/search', $url)
            );
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('catalog/product');

                $this->load->model('journal2/product');
            

        $product_info = $this->model_catalog_product->getProduct($product_id);
$data['avail_status'] = $this->config->get('avail_status');
                                      $AvailArray = Array(
                                            'quantity' => $product_info['quantity'],
                                            'stock_status_id' => $product_info['stock_status_id'],
                                            'product_id' => $product_info['product_id'],
                                            );

                                         $avail_product_quantity =  $this->load->controller('extension/module/avail/GetProductStatus', $AvailArray);
										$data['avail_product_quantity'] = $avail_product_quantity;
										$data['language_id'] = (int)$this->config->get('config_language_id');
										$avail_text = $this->config->get('avail_text');
										$data['text_button_avail'] = $avail_text[$data['language_id']]['button_avail']?$avail_text[$data['language_id']]['button_avail']:$this->language->get('notify_me');
										$data['avail_button_cart_productpage'] = $this->config->get('avail_button_cart_productpage');//avail
										$data['avail_options_status'] = $this->config->get('avail_options_status')?$this->config->get('avail_options_status'):'0';//avail
										$data['change_buttom'] = $this->config->get('avail_status')?$this->config->get('avail_status'):'0';
										$data['avail_default'] = $this->config->get('avail_default');
			

        if ($product_info) {

                $data['video_status'] = $this->config->get('video_status');
                if ($data['video_status']) {
                    $data['video_fs'] = $this->config->get('video_fs');
                    $data['video_rel'] = $this->config->get('video_rel');
                    $data['video_branding'] = $this->config->get('video_branding');
                    $data['video_loop'] = $this->config->get('video_loop');
                    $data['video_autoplay'] = $this->config->get('video_autoplay');
                    $data['video_btn'] = $this->config->get('video_btn');
                    $data['video_image'] = '/image/'.$this->config->get('video_image');
                }
            
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['store_id'] = $this->config->get('config_store_id');

            $data['breadcrumbs'][] = array(
                'text' => $product_info['name'],
                'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
            );

            $this->document->setTitle($product_info['meta_title']);
            $this->document->setDescription($product_info['meta_description']);
            $this->document->setKeywords($product_info['meta_keyword']);
            $this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
            $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

            $data['heading_title'] = $product_info['name'];

            $data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
            $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

            $this->load->model('catalog/review');

            $data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

            $data['product_id'] = (int)$this->request->get['product_id'];
            $data['manufacturer'] = $product_info['manufacturer'];

			if (strpos($this->config->get('config_template'), 'journal2') === 0) {
			    $this->load->model('catalog/manufacturer');
			    $data['text_manufacturer'] = $this->language->get('text_manufacturer');
                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
                if ($manufacturer_info && $manufacturer_info['image'] && $this->journal2->settings->get('manufacturer_image', '0') == '1') {
                    $this->journal2->settings->set('manufacturer_image', 'on');
                    $data['manufacturer_image_width'] = $this->journal2->settings->get('manufacturer_image_width', 100);
                    $data['manufacturer_image_height'] = $this->journal2->settings->get('manufacturer_image_height', 100);
                    $data['manufacturer_image'] = Journal2Utils::resizeImage($this->model_tool_image, $manufacturer_info['image'], $data['manufacturer_image_width'], $data['manufacturer_image_height']);
                    switch ($this->journal2->settings->get('manufacturer_image_additional_text', 'none')) {
                        case 'brand':
                            $data['manufacturer_image_name'] = $product_info['manufacturer'];
                            break;
                        case 'custom':
                            $data['manufacturer_image_name'] = $this->journal2->settings->get('manufacturer_image_custom_text');
                            break;
                    }
                }
			}
            
            $data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
            $data['model'] = $product_info['model'];
            $data['ean'] = $product_info["ean"];
            $data['reward'] = $product_info['reward'];
            $data['points'] = $product_info['points'];
            $data['quantity'] = ($product_info['quantity'] < 0) ? 0 : $product_info['quantity'];
            $data['sklad_tursko'] = $product_info['sklad_tursko'];
            $data['sklad_brno'] = $product_info['sklad_brno'];
            $data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            $data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
            $data['text_tab_index'] = $this->language->get('text_tab_index');

            if ($data['store_id'] == 0) {
                $data['text_little_signature'] = $this->language->get('text_little_signature');
                $data['little_signature'] = true;
            }

            // Вычисление даты доставки.
            $onedays = ' + 1 days';
            $twodays = ' + 2 days';
            $threedays = ' + 3 days';
            $fourdays = ' + 4 days';
            $fivedays = ' + 5 days';
            $sixdays = ' + 6 days';
            if ($data['store_id'] == 1) {
                $onedays = ' + 2 days';
                $twodays = ' + 3 days';
                $threedays = ' + 4 days';
                $fourdays = ' + 5 days';
                $fivedays = ' + 6 days';
                $sixdays = ' + 7 days';

            }

            if ($product_info['location'] == 'pohoda') {
                $for_date_today = date('d.m.y', strtotime($onedays));
                if (date('H:i') > '14:59') {
                    $for_date_today = date('d.m.y', strtotime($twodays));
                }
                if (date('w') == '5') {

                    $for_date_today = date('d.m.y', strtotime($threedays));
                    if (date('H:i') > '14:59') {
                        $for_date_today = date('d.m.y', strtotime($threedays));

                    }
                }
                if (date('w') == '6') {
                    $for_date_today = date('d.m.y', strtotime($twodays));
                }
                if (date('w') == '0') {
                    $for_date_today = date('d.m.y', strtotime($onedays));
                }
            }
            else
            {
                $for_date_today = date('d.m.y', strtotime($threedays));
                if (date('H:i') > '14:59') {
                    $for_date_today = date('d.m.y', strtotime($fourdays));
                }
                if (date('w') == '2') {
                    $for_date_today = date('d.m.y', strtotime($threedays));
                    if (date('H:i') > '14:59') {
                        $for_date_today = date('d.m.y', strtotime($sixdays));
                    }
                }
                if (date('w') == '3') {
                    $for_date_today = date('d.m.y', strtotime($fivedays));
                    if (date('H:i') > '14:59') {
                        $for_date_today = date('d.m.y', strtotime($sixdays));
                    }
                }
                if (date('w') == '4') {
                    $for_date_today = date('d.m.y', strtotime($fivedays));
                    if (date('H:i') > '14:59') {
                        $for_date_today = date('d.m.y', strtotime($sixdays));
                    }
                }
                if (date('w') == '5') {
                    $for_date_today = date('d.m.y', strtotime($fivedays));
                    if (date('H:i') > '14:59') {
                        $for_date_today = date('d.m.y', strtotime($sixdays));
                    }
                }
                if (date('w') == '6') {
                    $for_date_today = date('d.m.y', strtotime($fourdays));
                }
                if (date('w') == '0') {
                    $for_date_today = date('d.m.y', strtotime($threedays));
                }
            }

            $data['date_today'] = $for_date_today;
            $data['location'] = $product_info['location'];

            $data['more_than'] =  $this->language->get('more_than');
            $data['pcs'] =  $this->language->get('pcs');
            $data['sklad_cr'] =  $this->language->get('sklad_cr');


            if ($product_info['location'] == 'pohoda') {

                if (true && $product_info['quantity'] <= 0) {
                    $data['stock_status'] = 'outofstock';
                }
                if (true && $product_info['quantity'] > 0) {
                    $data['stock_status'] = 'instock';
                }
                $data['labels'] = $this->model_journal2_product->getLabels($product_info['product_id']);
            
                if ($product_info['quantity'] <= 0) {
                    $data['stock'] = $product_info['stock_status'];
                }
                elseif ($product_info['quantity'] > 20) {
                    //$data['stock'] = 'více než 20 ks';
                    $data['stock'] =$data['more_than'];
                }
                elseif ($this->config->get('config_stock_display')) {
                    $data['stock'] = $product_info['quantity'] . $data['pcs'];

                }
            } else {
                $data['stock'] = '<span class="gree">' . $product_info['quantity'] . $data['sklad_cr'] . '</span>';

            }

            $this->load->model('tool/image');

            if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
                $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));

                        $data['selector'] = $this->model_tool_image->resize($product_info['image'], $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_additional_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_additional_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_additional_height'));
                        $data['medium'] = $this->model_tool_image->resize($product_info['image'], $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_thumb_height'));
                        if (isset($data['popup'])) {
                            $product_info['popup'] = $data['popup'];
                        } else if (isset($data['popup_fixed'])) {
                            $product_info['popup'] = $data['popup_fixed'];
                        } else {
                            $product_info['popup'] = $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
                        }
                        $product_info['medium'] = $data['medium'];
                        $product_info['selector'] = $data['selector'];
                        if(isset($data['popup'])) $data['popup'] = $data['popup'].'" id="mainimage';
            } else {
                $data['popup'] = '';

                        $data['selector'] = $this->model_tool_image->resize($product_info['image'], $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_additional_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_additional_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_additional_height'));
                        $data['medium'] = $this->model_tool_image->resize($product_info['image'], $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_thumb_height'));
                        if (isset($data['popup'])) {
                            $product_info['popup'] = $data['popup'];
                        } else if (isset($data['popup_fixed'])) {
                            $product_info['popup'] = $data['popup_fixed'];
                        } else {
                            $product_info['popup'] = $data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
                        }
                        $product_info['medium'] = $data['medium'];
                        $product_info['selector'] = $data['selector'];
                        if(isset($data['popup'])) $data['popup'] = $data['popup'].'" id="mainimage';
            }

            if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
                $data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
            } else {
                $data['thumb'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
            }

            $data['images'] = array();

            $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

            foreach ($results as $result) {
                $data['images'][] = array(
'video' => $result['video'],
                    'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
                    'thumb' => ($this->config->get('video_status') && $result['video'] && !$result['image']) ? $this->model_tool_image->resize($this->config->get('video_image'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height')) : $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height'))
                );
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $data['price'] = false;
            }


                        $product_info['thumb'] = $data['thumb'];
                        if (!isset($product_info['images'])) {
                            $product_info['images'] = $results;
                        }
                        $product_info['images_original'] = $data['images'];
                        
            if ((float)$product_info['special']) {

                if (strpos($this->config->get('config_template'), 'journal2') === 0 && $this->journal2->settings->get('show_countdown_product_page', 'on') == 'on') {
                    $this->load->model('journal2/product');
                    $date_end = $this->model_journal2_product->getSpecialCountdown($this->request->get['product_id']);
                    if ($date_end === '0000-00-00') {
                        $date_end = false;
                    }
                    $data['date_end'] = $date_end;
                }
            
                $data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $data['special'] = false;
            }

            if ($this->config->get('config_tax')) {
                $data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
            } else {
                $data['tax'] = false;
            }

            $discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

            $data['discounts'] = array();

            foreach ($discounts as $discount) {
                $data['discounts'][] = array(
                    'quantity' => $discount['quantity'],
                    'price' => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
                );
            }

            $data['options'] = array();

                $extra = 0;
            

            foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
                $product_option_value_data = array();

                if ($option['option_id'] == 1){

                    foreach ($option['product_option_value'] as $option_value) {
                        if ($option_value['quantity'] > 0) {
                            if ($option_value['option_value_id'] == 54){
                                $extra = 0;
                                $product_option_value_data = array();
                            }
                            $price = false;
                            if ($option_value['price_prefix'] === '+') {
                                $extra += (float)$option_value['price'];
                            } else {
                                $extra -= (float)$option_value['price'];
                            }

                            $product_option_value_data[] = array(
                                'product_option_value_id' => $option_value['product_option_value_id'],
                                'option_value_id'         => $option_value['option_value_id'],
                                'name'                    => $option_value['name'],
                                'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
                                'price'                   => $price,
                                'price_prefix'            => $option_value['price_prefix']
                            );
                            if ($option_value['option_value_id'] == 54){
                                break;
                            }
                        }
                    }

                }else{
            

                foreach ($option['product_option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($this->config->get('module_avail_status')?$option_value['quantity'] >= 0 : $option_value['quantity'] > 0 )) {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                        } else {
                            $price = false;
                        }

                        $product_option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'image' => strpos($this->config->get('config_template'), 'journal2') === 0 && $option_value['image'] && is_file(DIR_IMAGE . $option_value['image']) ? Journal2Utils::resizeImage($this->model_tool_image, $option_value['image'], $this->journal2->settings->get('product_page_options_push_image_width', 30), $this->journal2->settings->get('product_page_options_push_image_height', 30), 'crop') : $this->model_tool_image->resize($option_value['image'], 50, 50),
                            'price' => $price,
                            'price_prefix' => $option_value['price_prefix']
                        );
                    }
                }

}
                $data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'value' => $option['value'],
                    'required' => $option['required']
                );
            }


            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $data['price'] = $this->currency->format($this->tax->calculate($product_info['price']+$extra, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $data['price'] = false;
            }

            if ($this->config->get('config_tax')) {
                $data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']+$extra, $this->session->data['currency']);
            } else {
                $data['tax'] = false;
            }
            
            if ($product_info['minimum']) {
                $data['minimum'] = $product_info['minimum'];
            } else {
                $data['minimum'] = 1;
            }

            $data['review_status'] = $this->config->get('config_review_status');

            if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
                $data['review_guest'] = true;
            } else {
                $data['review_guest'] = false;
            }

            if ($this->customer->isLogged()) {
                $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            } else {
                $data['customer_name'] = '';
            }

            $data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
            $data['rating'] = (int)$product_info['rating'];

            // Captcha
            if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
                $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
            } else {
                $data['captcha'] = '';
            }

            $data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);

            $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

            //EPREL
            foreach ($data['attribute_groups'][0]["attribute"] as $attribute) {
                If ($attribute["attribute_id"] == 38 && $attribute["text"] && $attribute["text"] > 0) {
                    $data['eprel_atrib'] = $this->model_catalog_product->getImgEprel($this->request->get['product_id']);
                    $filepath = './image/Label/' . $attribute["text"] . '.png';
                    If ($data['eprel_atrib'][0]["atrib_eprel"]){

                        if ($data['eprel_atrib']['atrib_eprel'] == $attribute["text"]){
                            $data['eprel_img'] = $data['eprel_atrib']['image_eprel'];
                        }
                        else {
                            $this->getEvroImage($attribute["text"], $filepath);

                            // Размер плохого файла (без картинки) 455.
                            If (file_exists($filepath) && filesize($filepath) > 1024) {
                                $this->model_catalog_product->editImgEprel($product_id['product_id'],
                                    ($data['eprel_atrib']['image_eprel'] ? '/image/label/' . $data['eprel_atrib']['image_eprel'] . '.png' : ('/image/label/' . $attribute["text"] . '.png')),
                                    ($data['eprel_atrib']['atrib_eprel'] ? $data['eprel_atrib']['atrib_eprel'] : $attribute["text"]), $filepath, $attribute["text"]);
                                unlink('/image/label/' . $data['eprel_atrib']['image_eprel'] . '.png');
                                $data['eprel_img'] .= $filepath;

                            }
                        }

                    }

                }
            }

            $data['products'] = array();

            $results = $this->model_catalog_product->getRelatedByCategory($this->request->get['product_id']);
//$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }


           if(!empty($result['product_id'])){
            $AvailArray = Array(
                'quantity' => $result['quantity'],
                'stock_status_id' => $result['stock_status_id'],
                'product_id' => $result['product_id'],
                );
            } else if(!empty($product_info['product_id'])){
             $AvailArray = Array(
                'quantity' => $product_info['quantity'],
                'stock_status_id' => $product_info['stock_status_id'],
                'product_id' => $product_info['product_id'],
                );
            } else if(!empty($product['product_id'])){
            $AvailArray = Array(
                'quantity' => $product['quantity'],
                'stock_status_id' => $product['stock_status_id'],
                'product_id' => $product['product_id'],
                );
            } else {
            $AvailArray = false;
            }


           if($AvailArray) {
                $avail_product_quantity =  $this->load->controller('extension/module/avail/GetProductStatus',$AvailArray);
           }  else {
               $avail_product_quantity = false;
           }
        

                $date_end = false;
                if (strpos($this->config->get('config_template'), 'journal2') === 0 && $special && $this->journal2->settings->get('show_countdown', 'never') !== 'never') {
                    $this->load->model('journal2/product');
                    $date_end = $this->model_journal2_product->getSpecialCountdown($result['product_id']);
                    if ($date_end === '0000-00-00') {
                        $date_end = false;
                    }
                }
            

                $additional_images = $this->model_catalog_product->getProductImages($result['product_id']);

                $image2 = false;

                if (count($additional_images) > 0) {
                    $image2 = $this->model_tool_image->resize($additional_images[0]['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                }
            
                $data['products'][] = array(

	 'manufacturer'    => !empty($result['manufacturer']) ? $result['manufacturer'] : '',
	 'brand'           => !empty($result['manufacturer']) ? $result['manufacturer'] : '',
	 'product_id'      => $result['product_id'],
	 'model'           => $result['model'],
	 'clear_price'     => $this->currency->format($result['special'] ? $result['special'] : $result['price'], $this->session->data['currency'], '', false),
	 'google_price'    => $this->currency->format($result['special'] ? $result['special'] : $result['price'], $this->config->get('remarketing_google_currency'), '', false),
	 'facebook_price'  => $this->currency->format($result['special'] ? $result['special'] : $result['price'], $this->config->get('remarketing_facebook_currency'), '', false),
	 'ecommerce_price' => $this->currency->format($result['special'] ? $result['special'] : $result['price'], $this->config->get('remarketing_ecommerce_currency'), '', false),
	  
 'avail_product_quantity'	  => $avail_product_quantity,
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $rating,
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }

            $data['tags'] = array();

            if ($product_info['tag']) {
                $tags = explode(',', $product_info['tag']);

                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'tag' => trim($tag),
                        'href' => $this->url->link('product/search', 'tag=' . trim($tag))
                    );
                }
            }

            $data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);


	    // remarketing all in one
		$data['facebook_remarketing_code'] = '';
		$data['google_remarketing_code'] = '';
		$data['remarketing_vk_code'] = '';
		$data['tiktok_remarketing_code'] = '';
		$data['facebook_remarketing_status'] = false;
		$data['google_remarketing_status'] = false;
		$this->load->model('tool/remarketing');
		
		if ($this->config->get('remarketing_status') && !$this->model_tool_remarketing->isBot()) {
			$current_price = $product_info['special'] ? $product_info['special'] : $product_info['price'];
			$product_price = $this->currency->format($current_price, $this->session->data['currency'], '', false);
			$google_price = $this->currency->format($current_price, $this->config->get('remarketing_google_currency'), '', false);
			$facebook_price = $this->currency->format($current_price, $this->config->get('remarketing_facebook_currency'), '', false);
			$ecommerce_price = $this->currency->format($current_price, $this->config->get('remarketing_ecommerce_currency'), '', false);
			
			$fb_time = time();
			
			if ($this->config->get('remarketing_facebook_status') && $this->config->get('remarketing_facebook_identifier') && $this->config->get('remarketing_facebook_pixel_status')) {
				$data['facebook_remarketing_status'] = true;
				$data['facebook_remarketing_code'] .= '<script>' . "\n";
				$data['facebook_remarketing_code'] .= "$(document).ready(function() {" . "\n";
				$data['facebook_remarketing_code'] .= "if (typeof fbq != 'undefined') {"."\n";
				$data['facebook_remarketing_code'] .= "fbq('track', 'ViewContent', {" . "\n";
				$data['facebook_remarketing_code'] .= "content_name: '" . addslashes($product_info['name']) . "'," . "\n";
				if (!empty($category_info['name'])) $data['facebook_remarketing_code'] .= "content_category: '" . addslashes($category_info['name']) . "'," . "\n";
				$data['facebook_remarketing_code'] .= "content_ids: ['" . ($this->config->get('remarketing_facebook_id') == 'id' ? $product_info['product_id'] : $product_info['model']) . "']," . "\n";
				$data['facebook_remarketing_code'] .= "content_type: 'product'," . "\n";
				$data['facebook_remarketing_code'] .= 'value: ' . $facebook_price . ',' . "\n";
				$data['facebook_remarketing_code'] .= "currency: '" . $this->config->get('remarketing_facebook_currency') . "'" . "\n";
				$data['facebook_remarketing_code'] .= '}, {eventID: ' . $fb_time . '})}});' . "\n</script>\n";	
				$data['facebook_price'] = $facebook_price;
				$data['facebook_currency'] = $this->config->get('remarketing_facebook_currency');
				$data['facebook_name'] = addslashes($product_info['name']);
				$data['facebook_id'] = ($this->config->get('remarketing_facebook_id') == 'id') ? $product_info['product_id'] : $product_info['model'];
			}
			
			if ($this->config->get('remarketing_tiktok_status')) { 
				$data['tiktok_remarketing_status'] = true;
				$data['tiktok_remarketing_code'] .= '<script>' . "\n";
				$data['tiktok_remarketing_code'] .= "$(document).ready(function() {" . "\n";
				$data['tiktok_remarketing_code'] .= "if (typeof ttq != 'undefined') {"."\n";
				$data['tiktok_remarketing_code'] .= "ttq.track('ViewContent', {" . "\n"; 
				$data['tiktok_remarketing_code'] .= "content_name: '" . addslashes($product_info['name']) . "'," . "\n";
				if (!empty($category_info['name'])) $data['tiktok_remarketing_code'] .= "content_category: '" . addslashes($category_info['name']) . "'," . "\n";
				$data['tiktok_remarketing_code'] .= "content_id: '" . $product_info['product_id'] . "'," . "\n";
				$data['tiktok_remarketing_code'] .= "content_type: 'product'," . "\n";
				$data['tiktok_remarketing_code'] .= 'value: ' . $product_price . ',' . "\n";
				$data['tiktok_remarketing_code'] .= "currency: '" . $this->session->data['currency'] ."'" . "\n";
				$data['tiktok_remarketing_code'] .= '})}});' . "\n</script>\n";	
				$data['tiktok_price'] = $product_price;
				$data['tiktok_currency'] = $this->session->data['currency'];
				$data['tiktok_name'] = addslashes($product_info['name']);
				$data['tiktok_id'] = $product_info['product_id'];
			}
			
			if ($this->config->get('remarketing_facebook_status') && $this->config->get('remarketing_facebook_server_side') && $this->config->get('remarketing_facebook_token')) {
				$data['facebook_remarketing_status'] = true; 
				$data['facebook_data_json']['products'] = [
					'value'            => $facebook_price,
					'currency'         => $this->config->get('remarketing_facebook_currency'),
					'content_ids'      => [$product_info['product_id']],
					'content_type'     => 'product',
					'content_name'     => addslashes($product_info['name']),
					'content_category' => !empty($category_info['name']) ? addslashes($category_info['name']) : '',
					'opt_out'          => false
				];
	
				$data['facebook_data_json']['time'] = $fb_time;
			}
			
			if ($this->config->get('remarketing_vk_status') && $this->config->get('remarketing_vk_identifier')) {	
				$related = [];
				if (isset($data['products']) && !empty($data['products']) && is_array($data['products'])) {
					foreach ($data['products'] as $product) {
						$related[] = $this->config->get('remarketing_vk_id') == 'id' ? $product['product_id'] : $product['model'];
					}
				}
				$eventParams = [];
				$eventParams['currency_code'] = $this->session->data['currency'];
				$eventParams['products'] = [];
				$eventParams['products'][] = [
					'id' =>  $this->config->get('remarketing_vk_id') == 'id' ? $product_info['product_id'] : $product_info['model'],
					'price' => $product_price,
					'products_recommended_ids' => (!empty($related) ? implode(',', $related) : '')
				]; 
				
				$data['remarketing_vk_code'] .= '<script>' . "\n";
				$data['remarketing_vk_code'] .= "$(document).ready(function() { setTimeout(function() { if (typeof VK != 'undefined') {" . "\n";
				$data['remarketing_vk_code'] .= "VK.Retargeting.ProductEvent(" . $this->config->get('remarketing_vk_identifier') . ", 'view_product', " . json_encode($eventParams) . ");" . "\n";
				$data['remarketing_vk_code'] .= '}}, 1000)})' . "\n";
				$data['remarketing_vk_code'] .= '</script>' . "\n";
			}
			
			if ($this->config->get('remarketing_google_status') && $this->config->get('remarketing_google_identifier')) {
				$data['google_remarketing_status'] = true;
				
				$data['google_price'] = $google_price;
				$data['google_code'] = $this->config->get('remarketing_google_identifier');
				$data['google_id'] = ($this->config->get('remarketing_google_id') == 'id') ? $product_info['product_id'] : $product_info['model'];
			}	
		
			if (($this->config->get('remarketing_ecommerce_status') || $this->config->get('remarketing_ecommerce_measurement_status'))) {
				$data['ecommerce_product_json'] = [];
				$data['measurement_status'] = false;
				$data['remarketing_ecommerce_status'] = $this->config->get('remarketing_ecommerce_status');
				$data['ecommerce_status'] = true;
				
				$product_impressions = [];
				$i = 1;
				if (isset($data['products']) && is_array($data['products'])) {
					foreach ($data['products'] as $product) {
						if (!empty($product['name'])) {
							$product_impressions[] = [
								'name'     => addslashes($product['name']),
								'id'       => ($this->config->get('remarketing_ecommerce_id') == 'id') ? $product['product_id'] : $product['model'],
								'price'    => $this->currency->format($product['special'] ? $product['special'] : $product['price'], $this->config->get('remarketing_ecommerce_currency'), '', false),
								'brand'    => !empty($product['manufacturer']) ? addslashes($product['manufacturer']) : '',
								'category' => $this->model_catalog_product->getRemarketingCategories($product['product_id']),
								'position' => $i
							];
						$i++;
						}
					}
				}
				$data['ecommerce_product_json'] = [
					'ecommerce' => [
						'currencyCode' => $this->config->get('remarketing_ecommerce_currency'),
						'detail' => [
							'actionField' => [
									'list' => addslashes($data['heading_title'])
							],
							'products' => [[
								'name'     => addslashes($product_info['name']),
								'id'       => ($this->config->get('remarketing_ecommerce_id') == 'id') ? $product_info['product_id'] : $product_info['model'],
								'price'    => $ecommerce_price,
								'brand'    => addslashes($product_info['manufacturer']),
								'category' => !empty($category_info['name']) ? addslashes($category_info['name']) : ''
							]],
						],
					],
					'event'                        => 'gtm-ee-event',
					'gtm-ee-event-category'        => 'Enhanced Ecommerce',
					'gtm-ee-event-action'          => 'Product Details',
					'gtm-ee-event-non-interaction' => 'True'
				];
				
				if (!empty($product_impressions)) {
					$data['ecommerce_product_json']['ecommerce']['impressions'] = $product_impressions;
				}
				
				
				if ($this->config->get('remarketing_ecommerce_measurement_status')) {
					$data['measurement_status'] = true;
				}
			}
			
			if (($this->config->get('remarketing_ecommerce_ga4_status') || $this->config->get('remarketing_ecommerce_ga4_measurement_status')) && isset($data['products'])) {
				$data['ecommerce_ga4_product_json'] = [];
				$data['ecommerce_ga4_status'] = true;
				$data['measurement_ga4_status'] = $this->config->get('remarketing_ecommerce_ga4_measurement_status');
				
				$item = [
					'item_name'      => addslashes($product_info['name']),
					// Google refuses id 'item_id'        => ($this->config->get('remarketing_ecommerce_ga4_id') == 'id') ? $product_info['product_id'] : $product_info['model'],
					'price'          => $ecommerce_price,
					'index'          => 1,
					'quantity'       => 1
				];
				if(!empty($product_info['manufacturer'])) $item['item_brand'] = addslashes($product_info['manufacturer']);
				if(!empty($category_info['name'])) $item['item_category'] = $item['item_list_name'] = addslashes($category_info['name']);

				$data['ecommerce_ga4_product_json'] = [ 
						'send_to' => $this->config->get('remarketing_ecommerce_ga4_identifier'),
						'currency' => $this->config->get('remarketing_ecommerce_currency'),
						'items' => [$item],
				];
			}
			
			if ($this->config->get('remarketing_esputnik_status') && $this->customer->isLogged()) {
				$data['esputnik_remarketing_status'] = true;
				$data['esputnik_data_json'] = [
					'productId' => addslashes($product_info['name']),
					'quantity' => $product_info['quantity'],
					'price' => $product_price,
					'isInStock' => $product_info['quantity'] > 0 ? '1' : '0'
				];
			} 
		}
	
            $this->model_catalog_product->updateViewed($this->request->get['product_id']);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput(magicRender($this->load->view('product/product', $data),$this,'product',$product_info));
        } else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['manufacturer_id'])) {
                $url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
            }

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . $this->request->get['search'];
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . $this->request->get['tag'];
            }

            if (isset($this->request->get['description'])) {
                $url .= '&description=' . $this->request->get['description'];
            }

            if (isset($this->request->get['category_id'])) {
                $url .= '&category_id=' . $this->request->get['category_id'];
            }

            if (isset($this->request->get['sub_category'])) {
                $url .= '&sub_category=' . $this->request->get['sub_category'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

    public function review()
    {
        $this->load->language('product/product');
$this->load->model('setting/setting');

        $this->load->model('catalog/review');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

        $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

        foreach ($results as $result) {
            $data['reviews'][] = array(
                'author' => $result['author'],
                'text' => nl2br($result['text']),
                'rating' => (int)$result['rating'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

        $this->response->setOutput($this->load->view('product/review', $data));
    }

    public function write()
    {
        $this->load->language('product/product');
$this->load->model('setting/setting');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                $json['error'] = $this->language->get('error_text');
            }

            if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
                $json['error'] = $this->language->get('error_rating');
            }

            // Captcha
            if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
                $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

                if ($captcha) {
                    $json['error'] = $captcha;
                }
            }

            if (!isset($json['error'])) {
                $this->load->model('catalog/review');

                $this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getRecurringDescription()
    {
        $this->load->language('product/product');
$this->load->model('setting/setting');
        $this->load->model('catalog/product');

        if (isset($this->request->post['product_id'])) {
            $product_id = $this->request->post['product_id'];
        } else {
            $product_id = 0;
        }

        if (isset($this->request->post['recurring_id'])) {
            $recurring_id = $this->request->post['recurring_id'];
        } else {
            $recurring_id = 0;
        }

        if (isset($this->request->post['quantity'])) {
            $quantity = $this->request->post['quantity'];
        } else {
            $quantity = 1;
        }

        $product_info = $this->model_catalog_product->getProduct($product_id);
$data['avail_status'] = $this->config->get('avail_status');
                                      $AvailArray = Array(
                                            'quantity' => $product_info['quantity'],
                                            'stock_status_id' => $product_info['stock_status_id'],
                                            'product_id' => $product_info['product_id'],
                                            );

                                         $avail_product_quantity =  $this->load->controller('extension/module/avail/GetProductStatus', $AvailArray);
										$data['avail_product_quantity'] = $avail_product_quantity;
										$data['language_id'] = (int)$this->config->get('config_language_id');
										$avail_text = $this->config->get('avail_text');
										$data['text_button_avail'] = $avail_text[$data['language_id']]['button_avail']?$avail_text[$data['language_id']]['button_avail']:$this->language->get('notify_me');
										$data['avail_button_cart_productpage'] = $this->config->get('avail_button_cart_productpage');//avail
										$data['avail_options_status'] = $this->config->get('avail_options_status')?$this->config->get('avail_options_status'):'0';//avail
										$data['change_buttom'] = $this->config->get('avail_status')?$this->config->get('avail_status'):'0';
										$data['avail_default'] = $this->config->get('avail_default');
			

        $recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

        $json = array();

        if ($product_info && $recurring_info) {
            if (!$json) {
                $frequencies = array(
                    'day' => $this->language->get('text_day'),
                    'week' => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month' => $this->language->get('text_month'),
                    'year' => $this->language->get('text_year'),
                );

                if ($recurring_info['trial_status'] == 1) {
                    $price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    $trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
                } else {
                    $trial_text = '';
                }

                $price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

                if ($recurring_info['duration']) {
                    $text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
                } else {
                    $text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
                }

                $json['success'] = $text;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getEvroImage($attrib_text, $filepath)
    {

        $ch = curl_init('http://eprel.ec.europa.eu/label/Label_' . $attrib_text . '.png');
        $fp = fopen($filepath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36",
        ));
        #curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

    }

}
