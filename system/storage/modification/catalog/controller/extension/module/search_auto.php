<?php

class ControllerExtensionModuleSearchAuto extends Controller
{

    private $messages = array(
        'entry_width',
        'entry_height',
        'entry_diameter',
        'entry_season',
        'entry_type',
        'entry_loadindex',
        'entry_speedindex',
        'entry_manufacturer',
        'entry_pcd',
        'entry_dia',
        'entry_et',
        'entry_vendor',
        'entry_model',
        'entry_year',
        'entry_mod',
        'text_null',
        'text_select',
        'button_search'
    );

    private $product_messages = array(
        'heading_title',
        'text_empty'
    );

    private $tire_params = array('width', 'height', 'diameter', 'season', 'type', 'loadindex', 'speedindex', 'manufacturer', 'model');
    private $disc_params = array('width', 'diameter', 'dia', 'pcd', 'et', 'manufacturer');
    private $auto_params = array('vendor', 'model', 'year', 'mod');


    public function index()
    {
        $this->document->addScript('https://www.frpneu.cz/catalog/view/javascript/searchavto.js' );
        $this->load->language('extension/module/search_auto');
        $this->load->model('extension/module/search_auto');
        $this->load->model('catalog/category');

        foreach ($this->messages as $key) {
            $data[$key] = $this->language->get($key);
        }


        if (isset($this->request->get['tab'])) {
            $tab = $this->request->get['tab'];
            $data['tab'] = $tab;
        } else {
            $data['tab'] = '';
        }
        if (isset($this->request->get['limit'])) {
            $data['limit'] = $this->request->get['limit'];
        } else {
            $data['limit'] = '';
        }

        if ($data['tab'] == 'tire') {
            foreach ($this->tire_params as $param) {
                if (isset($this->request->get[$param])) {
                    $data['tire_' . $param] = $this->request->get[$param];
                }
            }
        } elseif ($data['tab'] == 'disc') {
            foreach ($this->disc_params as $param) {
                if (isset($this->request->get[$param])) {
                    $data['disc_' . $param] = $this->request->get[$param];
                }
            }
        } elseif ($data['tab'] == 'auto') {
            foreach ($this->auto_params as $param) {
                if (isset($this->request->get[$param])) {
                    $data['auto_' . $param] = $this->request->get[$param];
                }
                $data['auto_' . $param . '_choices'] = array();
            }
        }
        //В базе "x" лежит с чешским кодом в запросе в английским. Меняю в запросе на "x" чешскую
        $data["disc_pcd"] = str_replace('X','x',$data["disc_pcd"]);

        $data['tire_manufacturer_choices'] = $this->model_extension_module_search_auto->getManufacturers($this->config->get('module_search_auto_tire_category'));
        $data['disc_manufacturer_choices'] = $this->model_extension_module_search_auto->getManufacturers($this->config->get('module_search_auto_disc_category'));
        $attributes = $this->model_extension_module_search_auto->getAttributes();
        foreach ($this->tire_params as $param) {
            if ($param != 'manufacturer') {
                if (isset($attributes[$this->config->get('module_search_auto_tire_' . $param)])) {
                    $data['tire_' . $param . '_choices'] = $attributes[$this->config->get('module_search_auto_tire_' . $param)];
                } else {
                    $data['tire_' . $param . '_choices'] = array();
                }
            }
        }
        foreach ($this->disc_params as $param) {
            if ($param != 'manufacturer') {
                if (isset($attributes[$this->config->get('module_search_auto_disc_' . $param)])) {
                    $data['disc_' . $param . '_choices'] = $attributes[$this->config->get('module_search_auto_disc_' . $param)];
                } else {
                    $data['disc_' . $param . '_choices'] = array();
                }
            }
        }
        foreach ($this->auto_params as $param) {
            $data['auto_' . $param . '_choices'] = array();
        }
        $data['auto_vendor_choices'] = $this->model_extension_module_search_auto->getVendors();

        if ($data['auto_vendor_choices'] && isset($data['auto_vendor'])) {
            $data['auto_model_choices'] = $this->model_extension_module_search_auto->getModels($data['auto_vendor']);
        }

        if ($data['auto_model_choices'] && isset($data['auto_model'])) {
            $data['auto_year_choices'] = $this->model_extension_module_search_auto->getYears($data['auto_vendor'], $data['auto_model']);
        }

        if ($data['auto_year_choices'] && isset($data['auto_year'])) {
            $data['auto_mod_choices'] = $this->model_extension_module_search_auto->getMods($data['auto_vendor'], $data['auto_model'], $data['auto_year']);
        }

        if (isset($this->request->get['path'])) {
            $path = explode('_', (string)$this->request->get['path']);
            $category_id = (int)array_shift($path);
        } else {
            $category_id = 0;
        }

        if (isset($this->request->get['manufacturer_id'])) {
            $this->load->model('catalog/manufacturer');
            $manufacturer_id = (int)$this->request->get['manufacturer_id'];
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
            if ($manufacturer_info) {
                $manufacturer = array('name' => $manufacturer_info['name']);
                if (in_array($manufacturer, $data['disc_manufacturer_choices'])) {
                    $data['tab'] = 'disc';
                    $data['disc_manufacturer'] = $manufacturer_info['name'];
                } elseif (in_array($manufacturer, $data['tire_manufacturer_choices'])) {
                    $data['tab'] = 'tire';
                    $data['tire_manufacturer'] = $manufacturer_info['name'];
                } else $data['tab'] = 'disabled';
            }
        } elseif ($category_id == $this->config->get('module_search_auto_tire_category')) {
            $data['tab'] = 'tire';
            $filters = array();
            if (count($path)) {
                if (count($path) == 1) {
                    $filters['filter_category_id'] = (int)$path[0];
                    $filters['filter_sub_category'] = 1;
                } elseif (count($path) == 2) {
                    $filters['filter_category_id'] = (int)$path[1];
                }
                $map = array(
                    366 => 'nákladní',
                    383 => 'celoroční',
                    297 => 'letní',
                    303 => 'zimní'
                );
                if (array_key_exists((int)$path[0], $map)) {
                    $data['tire_season'] = $map[(int)$path[0]];
                }
                if (count($path) == 2) {
                    $category_info = $this->model_catalog_category->getCategory((int)$path[1]);
                    $data['tire_manufacturer'] = $category_info['name'];
                }
                //$filters['limit'] = 1;
                //$filters['start'] = 0;
                //$items = $this->model_catalog_product->getProducts($filters)['query'];
                //if (count($items)){
                //$tmp = array_shift($items);
                //$product = $this->model_catalog_product->getProduct($tmp['product_id']);
                //if ((int)$path[0] == 366){
                //$data['tire_season'] = 'nákladní';
                //}else{
                //$tmp_arrts = $this->model_catalog_product->getProductAttributes($tmp['product_id']);
                //foreach($tmp_arrts[0]['attribute'] as $attribute){
                //if ($attribute['attribute_id'] == $this->config->get('module_search_auto_tire_season')){
                //$data['tire_season'] = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $attribute['text']);
                ////print $data['tire_season'];
                //break;
                //}
                //}
                //}
                //if (count($path) == 2){
                //$data['tire_manufacturer'] = $product['manufacturer'];
                //}
                //}
            }
        } elseif ($category_id == $this->config->get('module_search_auto_disc_category')) {
            $data['tab'] = 'disc';
            if (count($path) == 2) {
                $filters['filter_category_id'] = (int)$path[1];
                $filters['limit'] = 1;
                $filters['start'] = 0;
                $items = $this->model_catalog_product->getProducts($filters)['query'];
                if (count($items)) {
                    $tmp = array_shift($items);
                    $product = $this->model_catalog_product->getProduct($tmp['product_id']);
                    $data['disc_manufacturer'] = $product['manufacturer'];
                }
            }
        } elseif ($category_id > 0) {
            $data['tab'] = 'disabled';
        }

        $data['action'] = $this->url->link('extension/module/search_auto/search');

        $this->document->addScript('catalog/view/theme/default/js/search_auto.js');
        return $this->load->view('extension/module/search_auto', $data);

    }

    public function ajax()
    {

        $this->load->language('product/search_auto');

        $this->load->model('extension/module/search_auto');

        foreach ($this->product_messages as $key) {
            $data[$key] = $this->language->get($key);
        }
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['compare'] = $this->url->link('product/compare');

        $url = '';
        if (isset($this->request->get['tab'])) {
            $data['tab'] = $this->request->get['tab'];
            $url .= '&tab=' . $data['tab'];
        } else {
            $data['tab'] = '';
        }

        if ($data['tab'] == 'tire') {
            $filters = array('type' => $data['tab'], 'attr' => array());
            foreach ($this->tire_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    if ($param == 'manufacturer') {
                        $filters[$param] = $this->request->get[$param];
                    } else {
                        $filters['attr'][$param] = $this->request->get[$param];
                    }
                    $url .= '&' . $param . '=' . urlencode(html_entity_decode($this->request->get[$param], ENT_QUOTES, 'UTF-8'));
                    $data[$param] = $this->request->get[$param];
                }
            }
        } elseif ($data['tab'] == 'disc') {
            $filters = array('type' => $data['tab'], 'attr' => array());
            foreach ($this->disc_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    if ($param == 'manufacturer') {
                        $filters[$param] = $this->request->get[$param];
                    } else {
                        $filters['attr'][$param] = $this->request->get[$param];
                    }
                    $data[$param] = $this->request->get[$param];
                }
            }
        } elseif ($data['tab'] == 'auto') {
            $auto_filters = array();
            foreach ($this->auto_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    $data[$param] = $this->request->get[$param];
                    $auto_filters[$param] = $this->request->get[$param];
                }
            }
        }

        $params = array();
        if (isset($data['tab']) && $data['tab'] == 'tire') {
            $defaults = array(
                'sort' => 'p.sort_order',
                'order' => 'ASC',
                'page' => 1,
                'limit' => $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')
            );
        } else {
            $defaults = array(
                'sort' => 'p.price',
                'order' => 'ASC',
                'page' => 1,
                'limit' => $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')
            );
        }
        foreach (array('sort', 'order', 'page', 'limit') as $param) {
            if (isset($this->request->get[$param])) {
                $url .= '&' . $param . '=' . $this->request->get[$param];
                $params[$param] = $this->request->get[$param];
            } else {
                $params[$param] = $defaults[$param];
            }
            $data[$param] = $params[$param];
            $filters[$param] = $params[$param];
        }

        unset($filters['page']);


        if (in_array($data['tab'], ['tire', 'disc'])) {
            $keys = isset($filters['attr']) ? $filters['attr'] : [];

            if(isset($filters['manufacturer'])){
                 $keys['manufacturer'] = $filters['manufacturer'];
            }

            $keystr = '';
            foreach ($keys as $key => $value) {
                    $keystr .=  '_'.$key . '=' .$value; 
            }    

            $keystr = 'searchavto_' .$data['tab']. md5($keystr) . "." . (int) $this->config->get("config_store_id");
            if(!($result = $this->cache->get($keystr )) ) {
                 $result = $this->model_extension_module_search_auto->getFilteredAttributes($filters);
                //$result['total_text'] = ' ' . $this->language->get('products_found') . ' <span class="total">' .  $this->model_extension_module_search_auto->getTotalProducts($filters). '<span>';
                $result['total_text'] = ' ' . $this->language->get('products_found') . ' <span class="total">' .  $this->model_extension_module_search_auto->getProducts($filters)['totalproduct']. '<span>';
            $this->cache->set($keystr, $result );
            }
           
            header('Content-Type: application/json');
            echo json_encode($result);die;
        }
    }

    public function search()
    {

        $this->document->addScript('https://www.frpneu.cz/catalog/view/javascript/searchavto.js' );

        if(isset($this->request->get['ajax'])){
            $this->ajax();
        }

        $this->load->language('product/search_auto');

        $this->load->model('extension/module/search_auto');

        $this->load->model('journal2/product');

        $this->load->model('tool/image');

        foreach ($this->product_messages as $key) {
            $data[$key] = $this->language->get($key);
        }
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['compare'] = $this->url->link('product/compare');

        $url = '';
        if (isset($this->request->get['tab'])) {
            $data['tab'] = $this->request->get['tab'];
            $url .= '&tab=' . $data['tab'];
        } else {
            $data['tab'] = '';
        }

        if ($data['tab'] == 'tire') {
            $filters = array('type' => $data['tab'], 'attr' => array());
            foreach ($this->tire_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    if ($param == 'manufacturer') {
                        $filters[$param] = $this->request->get[$param];
                    } else {
                        $filters['attr'][$param] = $this->request->get[$param];
                    }
                    $url .= '&' . $param . '=' . urlencode(html_entity_decode($this->request->get[$param], ENT_QUOTES, 'UTF-8'));
                    $data[$param] = $this->request->get[$param];
                }
            }
        } elseif ($data['tab'] == 'disc') {
            $filters = array('type' => $data['tab'], 'attr' => array());
            foreach ($this->disc_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    if ($param == 'manufacturer') {
                        $filters[$param] = $this->request->get[$param];
                    } else {
                        $filters['attr'][$param] = $this->request->get[$param];
                    }
                    $url .= '&' . $param . '=' . urlencode(html_entity_decode($this->request->get[$param], ENT_QUOTES, 'UTF-8'));
                    $data[$param] = $this->request->get[$param];
                }
            }
        } elseif ($data['tab'] == 'auto') {
            $auto_filters = array();
            foreach ($this->auto_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    $url .= '&' . $param . '=' . urlencode(html_entity_decode($this->request->get[$param], ENT_QUOTES, 'UTF-8'));
                    $data[$param] = $this->request->get[$param];
                    $auto_filters[$param] = $this->request->get[$param];
                }
            }
        }
        $search_url = $url;
        $params = array();
        if (isset($data['tab']) && $data['tab'] == 'tire') {
            $defaults = array(
                'sort' => 'p.sort_order',
                'order' => 'ASC',
                'page' => 1,
                'limit' => $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')
            );
        } else {
            $defaults = array(
                'sort' => 'p.price',
                'order' => 'ASC',
                'page' => 1,
                'limit' => $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')
            );
        }
        foreach (array('sort', 'order', 'page', 'limit') as $param) {
            if (isset($this->request->get[$param])) {
                $url .= '&' . $param . '=' . $this->request->get[$param];
                $params[$param] = $this->request->get[$param];
            } else {
                $params[$param] = $defaults[$param];
            }
            $data[$param] = $params[$param];
            $filters[$param] = $params[$param];
        }
        $filters['start'] = ($params['page'] - 1) * $params['limit'];
        unset($filters['page']);

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/search_auto/search', $url)
        );

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        $data['more_than'] =  $this->language->get('more_than');
        $data['pcs'] =  $this->language->get('pcs');
        $data['sklad_cr'] =  $this->language->get('sklad_cr');


        if (in_array($data['tab'], ['tire', 'disc'])) {

            //$product_total = $this->model_extension_module_search_auto->getTotalProducts($filters);
            $product_total = $this->model_extension_module_search_auto->getProducts($filters)['totalproduct'];
            $products = $this->model_extension_module_search_auto->getProducts($filters)['query'];

            foreach ($products as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
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

                if ($result['location'] == 'pohoda') {
                    if ($result['quantity'] <= 0) {
                        $stock = $result['stock_status'];
                    } elseif ($this->config->get('config_stock_display')) {
                        $stock = $result['quantity'] . $data['pcs'];

                    }
                    if ($result['quantity'] > 20) {
                        $stock = $data['more_than'];
                    }
                } else {
                    $stock = $result['quantity'] . $data['sklad_cr'];

                }


          if(!empty($result['stock_status_id'])){
            $AvailArray = Array(
                'quantity' => $result['quantity'],
                'stock_status_id' => $result['stock_status_id'],
                'product_id' => $result['product_id'],
                );
            } else if(!empty($product_info['stock_status_id'])){
             $AvailArray = Array(
                'quantity' => $product_info['quantity'],
                'stock_status_id' => $product_info['stock_status_id'],
                'product_id' => $product_info['product_id'],
                );
            } else if(!empty($product['stock_status_id'])){
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
        
                $data['products'][] = array(
 'avail_product_quantity'	  => $avail_product_quantity,
                    'product_id' => $result['product_id'],
                    'model' => $result['model'],
                    'thumb' => $image,
                    'stock' => $stock,
                    'name' => $result['name'],
                    'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    //'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $result['rating'],
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url),
                    'labels' => $this->model_journal2_product->getLabels($result['product_id'])
                );
            }

            $url = $search_url;

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();
            if (isset($data['tab']) && $data['tab'] == 'tire') {

                $data['sorts'][] = array(
                    'text' => $this->language->get('text_default'),
                    'value' => 'p.sort_order-ASC',
                    'href' => $this->url->link('extension/module/search_auto/search', 'sort=p.sort_order&order=ASC' . $url)
                );
            }

            //$data['sorts'][] = array(
            //'text'  => $this->language->get('text_recommended'),
            //'value' => 'p.sort_recommended',
            //'href'  => $this->url->link('extension/module/search_auto/search', 'sort=p.location&order=pohoda' . $url)
            //);

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href' => $this->url->link('extension/module/search_auto/search', 'sort=pd.name&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href' => $this->url->link('extension/module/search_auto/search', 'sort=pd.name&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $this->url->link('extension/module/search_auto/search', 'sort=p.price&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $this->url->link('extension/module/search_auto/search', 'sort=p.price&order=DESC' . $url)
            );

            if ($this->config->get('config_review_status')) {
                $data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href' => $this->url->link('extension/module/search_auto/search', 'sort=rating&order=DESC' . $url)
                );

                $data['sorts'][] = array(
                    'text' => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href' => $this->url->link('extension/module/search_auto/search', 'sort=rating&order=ASC' . $url)
                );
            }

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href' => $this->url->link('extension/module/search_auto/search', 'sort=p.model&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href' => $this->url->link('extension/module/search_auto/search', 'sort=p.model&order=DESC' . $url)
            );

            $url = $search_url;

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 24, 48, 96));

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = array(
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('extension/module/search_auto/search', $url . '&limit=' . $value)
                );
            }

            $url = $search_url;

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $params['page'];
            $pagination->limit = $params['limit'];
			
			if (isset($this->request->get['lazy'])) {
				$data['lazy'] = "yes"; 
            }
			
            $pagination->url = $this->url->link('extension/module/search_auto/search', $url . '&page={page}');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($params['page'] - 1) * $params['limit']) + 1 : 0, ((($params['page'] - 1) * $params['limit']) > ($product_total - $params['limit'])) ? $product_total : ((($params['page'] - 1) * $params['limit']) + $params['limit']), $product_total, ceil($product_total / $params['limit']));

            $this->response->setOutput($this->load->view('product/category', $data));

        } else if ($data['tab'] == 'auto' && isset($data['vendor']) && isset($data['model']) && isset($data['year']) && isset($data['mod'])) {
            function sortByWidth($a, $b)
            {
                if (isset($a[0])) $a = $a[0];
                if (isset($b[0])) $b = $b[0];

                if ($a['w'] == $b['w']) {
                    return 0;
                }

                return ($a['w'] < $b['w']) ? -1 : 1;
            }

            $text_original = $this->language->get('text_original');
            $text_tuning = $this->language->get('text_tuning');
            $data['text_hint'] = sprintf($this->language->get('text_hint'), $text_original, $text_tuning);

            $auto = $this->model_extension_module_search_auto->getAuto($auto_filters);

            if ($auto) {
                $auto['pcd'] = mb_ereg_replace("\.00", "", $auto['pcd']);
                $auto['pcd'] = preg_replace('/(.*\.[1-9]+)0+$/', '$1', $auto['pcd']);
                $auto['dia'] = preg_replace('/(.*\.[1-9]+)0+$/', '$1', $auto['dia']);
                //$auto['pcd'] = str_replace('.', ',', $auto['pcd']);
                $data['auto'] = array(
                    'id' => $auto['id'],
                    'vendor' => $auto['vendor'],
                    'year' => $auto['year'],
                    'model' => $auto['model'],
                    'modification' => $auto['modification'],
                    'nut' => $auto['nut'],
                    'pcd' => $auto['pcd'],
                    'dia' => $auto['dia']
                );

                $autoData = json_decode($auto['data'], true);

                /*
                    0   defTires
                    1   altTires
                    2   defDiscs
                    3   altDiscs
                */

                $type = array($this->language->get('text_original'), $this->language->get('text_tuning'), $this->language->get('text_original'), $this->language->get('text_tuning'));

                $data['data'] = array();

                if ($autoData) {
                    for ($i = 0, $countI = sizeof($autoData); $i < $countI; ++$i) {
                        for ($n = 0, $countN = sizeof($autoData[$i]); $n < 4; ++$n) {
                            if ($n < $countN) {
                                if (is_array($autoData[$i][$n][0])) {
                                } else {
                                    if (($n == 0) or ($n < 2 and $countN > 2)) {

                                        $autoData[$i][$n][2] = (int)$autoData[$i][$n][2];

                                        $data['data']['tire'][$autoData[$i][$n][2]][] = array(
                                            'w' => $autoData[$i][$n][0],
                                            'h' => $autoData[$i][$n][1],
                                            'r' => $autoData[$i][$n][2],
                                            'n' => "{$autoData[$i][$n][0]}/{$autoData[$i][$n][1]} R{$autoData[$i][$n][2]}",
                                            'url' => $this->url->link('extension/module/search_auto/search', "tab=tire&width={$autoData[$i][$n][0]}&height={$autoData[$i][$n][1]}&diameter={$autoData[$i][$n][2]}"),
                                            'type' => isset($data['data']['tire'][$autoData[$i][$n][2]]) ? $type[1] : $type[$n]
                                        );
                                    } else {

                                        $autoData[$i][$n][1] = (int)$autoData[$i][$n][1];
                                        //$autoData[$i][$n][0] = str_replace(',', '.', $autoData[$i][$n][0]);
                                        $autoData[$i][$n][0] = str_replace('.0', '', $autoData[$i][$n][0]);
                                        //$autoData[$i][$n][2] = str_replace(',', '.', $autoData[$i][$n][2]);
                                        $autoData[$i][$n][2] = str_replace('.0', '', $autoData[$i][$n][2]);
                                        // &pcd={$auto['pcd']}
                                        $data['data']['disc'][$autoData[$i][$n][1]][] = array(
                                            'w' => $autoData[$i][$n][0],
                                            'r' => $autoData[$i][$n][1],
                                            'e' => $autoData[$i][$n][2],
                                            'n' => "{$autoData[$i][$n][0]} x {$autoData[$i][$n][1]} {$auto['pcd']} D{$auto['dia']} ET {$autoData[$i][$n][2]}",
                                            //'url' => $this->url->link('extension/module/search_auto/search', "tab=disc&width={$autoData[$i][$n][0]}&diameter={$autoData[$i][$n][1]}&dia={$auto['dia']}&et={$autoData[$i][$n][2]}"),
                                            'url' => $this->url->link('extension/module/search_auto/search', "tab=disc&width={$autoData[$i][$n][0]}&diameter={$autoData[$i][$n][1]}&pcd={$auto['pcd']}&dia={$auto['dia']}"),
                                            'type' => isset($data['data']['disc'][$autoData[$i][$n][1]]) ? $type[1] : $type[($countN > 2) ? $n : 0]
                                        );
                                    }
                                }
                            }
                        }
                    }
                }


                foreach ($data['data'] as $group => $itemsR) {
                    ksort($itemsR, SORT_NUMERIC);

                    foreach ($itemsR as $r => $items) {
                        uasort($itemsR[$r], 'sortByWidth');
                    }

                    $data['data'][$group] = $itemsR;
                }
            }

            $this->response->setOutput($this->load->view('product/search_auto', $data));

        } else {

            $this->document->setTitle($this->language->get('text_error'));

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }

    }

    public function json()
    {
        $this->load->language('product/search_auto');

        $this->load->model('extension/module/search_auto');

        $this->load->model('journal2/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['tab'])) {
            $data['tab'] = $this->request->get['tab'];
        } else {
            $data['tab'] = '';
        }

        if ($data['tab'] == 'tire') {
            $filters = array('type' => $data['tab'], 'attr' => array());
            foreach ($this->tire_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    if ($param == 'manufacturer') {
                        $filters[$param] = $this->request->get[$param];
                    } else {
                        $filters['attr'][$param] = $this->request->get[$param];
                    }
                    $data[$param] = $this->request->get[$param];
                }
            }
        } elseif ($data['tab'] == 'disc') {
            $filters = array('type' => $data['tab'], 'attr' => array());
            foreach ($this->disc_params as $param) {
                if (isset($this->request->get[$param]) and $this->request->get[$param] != '') {
                    if ($param == 'manufacturer') {
                        $filters[$param] = $this->request->get[$param];
                    } else {
                        $filters['attr'][$param] = $this->request->get[$param];
                    }
                    $data[$param] = $this->request->get[$param];
                }
            }
        }
        $params = array();
        $defaults = array(
            'sort' => 'p.price',
            'order' => 'ASC',
            'page' => 1,
            'limit' => 100 //$this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit')
        );
        foreach (array('sort', 'order', 'page', 'limit') as $param) {
            if (isset($this->request->get[$param])) {
                $params[$param] = $this->request->get[$param];
            } else {
                $params[$param] = $defaults[$param];
            }
            $data[$param] = $params[$param];
            $filters[$param] = $params[$param];
        }
        $filters['start'] = ($params['page'] - 1) * $params['limit'];
        unset($filters['page']);

        if (in_array($data['tab'], ['tire', 'disc'])) {

            //$product_total = $this->model_extension_module_search_auto->getTotalProducts($filters);
            $product_total = $this->model_extension_module_search_auto->getProducts($filters)['totalproduct'];
            $products = $this->model_extension_module_search_auto->getProducts($filters)['query'];

            foreach ($products as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
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



          if(!empty($result['stock_status_id'])){
            $AvailArray = Array(
                'quantity' => $result['quantity'],
                'stock_status_id' => $result['stock_status_id'],
                'product_id' => $result['product_id'],
                );
            } else if(!empty($product_info['stock_status_id'])){
             $AvailArray = Array(
                'quantity' => $product_info['quantity'],
                'stock_status_id' => $product_info['stock_status_id'],
                'product_id' => $product_info['product_id'],
                );
            } else if(!empty($product['stock_status_id'])){
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
        
                $data['products'][] = array(
 'avail_product_quantity'	  => $avail_product_quantity,
                    'product_id' => $result['product_id'],
                    'model' => $result['model'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    //'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    //'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    //'rating'      => $result['rating'],
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    //'labels'      => $this->model_journal2_product->getLabels($result['product_id'])
                );
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($data['products']));
        }
    }

    public function model()
    {
        $this->load->language('extension/module/search_auto');
        $this->load->model('extension/module/search_auto');

        $data = array();
        $vendor = isset($this->request->post['vendor']) ? $this->request->post['vendor'] : '';

        if ($this->checkRequest() && $vendor) {
            $models = $this->model_extension_module_search_auto->getModels($vendor);

            $data['model'] = '<option value="">' . $this->language->get('text_null') . '</option>';

            foreach ($models as $model) {
                $data['model'] .= '<option value="' . $model['model'] . '">' . $model['model'] . '</option>';
            }
        } else {
            $data['error'] = $this->language->get('text_error_result');
        }

        echo json_encode($data);
    }

    public function year()
    {
        $this->load->language('extension/module/search_auto');
        $this->load->model('extension/module/search_auto');

        $data = array();
        $vendor = isset($this->request->post['vendor']) ? $this->request->post['vendor'] : '';
        $model = isset($this->request->post['model']) ? $this->request->post['model'] : '';

        if ($this->checkRequest() && $vendor && $model) {
            $years = $this->model_extension_module_search_auto->getYears($vendor, $model);

            $data['year'] = '<option value="">' . $this->language->get('text_null') . '</option>';

            foreach ($years as $year) {
                $data['year'] .= '<option value="' . $year['year'] . '">' . $year['year'] . '</option>';
            }
        } else {
            $data['error'] = $this->language->get('text_error_result');
        }

        echo json_encode($data);
    }

    public function mod()
    {
        $this->load->language('extension/module/search_auto');
        $this->load->model('extension/module/search_auto');

        $data = array();
        $vendor = isset($this->request->post['vendor']) ? $this->request->post['vendor'] : '';
        $model = isset($this->request->post['model']) ? $this->request->post['model'] : '';
        $year = isset($this->request->post['year']) ? $this->request->post['year'] : '';

        if ($this->checkRequest() && $vendor && $model && $year) {
            $mods = $this->model_extension_module_search_auto->getMods($vendor, $model, $year);

            $data['mod'] = '<option value="">' . $this->language->get('text_null') . '</option>';

            foreach ($mods as $mod) {
                $data['mod'] .= '<option value="' . $mod['modification'] . '">' . $mod['modification'] . '</option>';
            }
        } else {
            $data['error'] = $this->language->get('text_error_result');
        }

        echo json_encode($data);
    }

    private function checkRequest()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !isset($this->request->server['HTTP_REFERER']) || !isset($this->request->server['HTTP_X_REQUESTED_WITH']) || strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            return false;
        }

        return preg_match('#(www.)?' . $this->request->server['HTTP_HOST'] . '#', $this->request->server['HTTP_REFERER']);
    }

}

?>
