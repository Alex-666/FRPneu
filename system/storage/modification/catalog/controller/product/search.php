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
class ControllerProductSearch extends Controller {
    public function index() {
        $this->load->language('product/search');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

                $this->load->model('journal2/product');
            

        $this->load->model('tool/image');

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
            $search_request = $this->request->get['search'];
            $search_exploded = false;

            $pos_l = strpos($search_request, 'letn');
            $pos_n = strpos($search_request, 'naklad');
            $pos_z = strpos($search_request, 'zemědělské');
            $pos_p = strpos($search_request, 'průmyslové');
            $pos_c = strpos($search_request, 'celoro');
            $pos_a = strpos($search_request, 'agro');
            $pos_zi = strpos($search_request, 'zimn');            

            $this->request->get['sub_category'] = true;            

            if ($pos_l === false) {
                if ($pos_z === false) {
                    if ($pos_p === false) {
                        if ($pos_c === false) {
                            if ($pos_a === false) {
                               if ($pos_zi === false) {
                                   if ($pos_n === false) {
                                            
                                        } else {
                                          $search_exploded = true;
                                          $this->request->get['category_id'] =  '366'; 
                                        }       
                                    } else {
                                      $search_exploded = true;
                                      $this->request->get['category_id'] = '303';  
                                    }                 
                            } else {
                              $search_exploded = true;  
                            }                            
                        } else {
                          $search_exploded = true;
                          $this->request->get['category_id'] = '383';  
                        }                        
                    } else {
                      $search_exploded = true;
                      $this->request->get['category_id'] = '2004';  
                    }                    
                } else {
                 $search_exploded = true;
                 $this->request->get['category_id'] = '2001';   
                }
            } else {
                $search_exploded = true;
                $this->request->get['category_id'] = '297';
            }

            $search_result = explode(' ', $search_request);

            if ($search_exploded) {
                $search = $search_result[0];
            } else {
                $category_id = false;
                $search = $search;
            }


        } else {
            $search = '';
        }

        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
        } elseif (isset($this->request->get['search'])) {
            $tag = $this->request->get['search'];
        } else {
            $tag = '';
        }

        if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
        } else {
            $description = '';
        }

        if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
        } else {
            $category_id = 0;
        }

        if (isset($this->request->get['sub_category'])) {
            $sub_category = $this->request->get['sub_category'];
        } else {
            $sub_category = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.location';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
        }

        if (isset($this->request->get['search'])) {
            $this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
        } elseif (isset($this->request->get['tag'])) {
            $this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
        } else {
            $this->document->setTitle($this->language->get('heading_title'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $url = '';

        if (isset($this->request->get['search'])) {
            $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['tag'])) {
            $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('product/search', $url)
        );

        if (isset($this->request->get['search'])) {
            $data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
        } else {
            $data['heading_title'] = $this->language->get('heading_title');
        }

        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

        $data['compare'] = $this->url->link('product/compare');
        $data['text_empty'] = $this->language->get('text_empty');

        $this->load->model('catalog/category');

        // 3 Level Category Search
        $data['categories'] = array();

        $categories_1 = $this->model_catalog_category->getCategories(0);

        foreach ($categories_1 as $category_1) {
            $level_2_data = array();

            $categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

            foreach ($categories_2 as $category_2) {
                $level_3_data = array();

                $categories_3 = array();

                foreach ($categories_3 as $category_3) {
                    $level_3_data[] = array(
                        'category_id' => $category_3['category_id'],
                        'name'        => $category_3['name'],
                    );
                }

                $level_2_data[] = array(
                    'category_id' => $category_2['category_id'],
                    'name'        => $category_2['name'],
                    'children'    => $level_3_data
                );
            }

            $data['categories'][] = array(
                'category_id' => $category_1['category_id'],
                'name'        => $category_1['name'],
                'children'    => $level_2_data
            );
        }

        $data['more_than'] =  $this->language->get('more_than');
        $data['pcs'] =  $this->language->get('pcs');
        $data['sklad_cr'] =  $this->language->get('sklad_cr');

        $data['products'] = array();

        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
            $filter_data = array(
                'filter_name'         => $search,
                'filter_tag'          => $tag,
                'filter_description'  => $description,
                'filter_category_id'  => $category_id,
                'filter_sub_category' => $sub_category,
                'sort'                => $sort,
                'order'               => $order,
                'start'               => ($page - 1) * $limit,
                'limit'               => $limit
            );

            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

	  $data['remarketing_google_ids'] = [];
	  $data['remarketing_facebook_ids'] = [];
	  $data['remarketing_target_ids'] = [];
	  $data['remarketing_vk_ids'] = [];
	  
			
			

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));

                        $results[$result['product_id']]['popup'] = $this->model_tool_image->resize($result['image'], $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_popup_height'));
                        $image = $image .'" id="search_'.$result['product_id'].'"';
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));

                        $results[$result['product_id']]['popup'] = $this->model_tool_image->resize($result['image'], $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_popup_height'));
                        $image = $image .'" id="search_'.$result['product_id'].'"';
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
                        $stock = $result['quantity'] .$data['pcs'] ;

                    }
                    if ($result['quantity'] > 20) {
                        $stock = $data['more_than'];
                    }
                } else {
                    $stock = $result['quantity'] . $data['sklad_cr'] ;

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

                        $results[$result['product_id']]['popup'] = $this->model_tool_image->resize($result['image'], $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get((null !== $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width') ? 'theme_' : '') . $this->config->get('config_theme') . '_image_popup_height'));
                        $image = $image .'" id="search_'.$result['product_id'].'"';
                }
            

	  $data['remarketing_google_ids'][] = $this->config->get('remarketing_google_id') == 'id' ? $result['product_id'] : $result['model'];
	  $data['remarketing_facebook_ids'][] = $this->config->get('remarketing_facebook_id') == 'id' ? $result['product_id'] : $result['model'];
	  $data['remarketing_target_ids'][] = $this->config->get('remarketing_mytarget_id') == 'id' ? $result['product_id'] : $result['model'];
	  $data['remarketing_vk_ids'][] = $this->config->get('remarketing_vk_id') == 'id' ? $result['product_id'] : $result['model'];
	  
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
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,

                'thumb2'       => $image2,
            

                'labels'        => $this->model_journal2_product->getLabels($result['product_id']),
            
                    'stock'       => $stock,
                    'name'        => $result['name'],
                    'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price'       => $price,
                    'special'     => $special,

                'date_end'       => $date_end,
            
                    'tax'         => $tax,
                    'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating'      => $result['rating'],
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
                );
            }

            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href'  => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href'  => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href'  => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href'  => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href'  => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url)
            );

            if ($this->config->get('config_review_status')) {
                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href'  => $this->url->link('product/search', 'sort=rating&order=DESC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href'  => $this->url->link('product/search', 'sort=rating&order=ASC' . $url)
                );
            }

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href'  => $this->url->link('product/search', 'sort=p.model&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href'  => $this->url->link('product/search', 'sort=p.model&order=DESC' . $url)
            );

            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 24, 48, 96));

            sort($limits);

            foreach($limits as $value) {
                $data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
                );
            }

            $url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
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

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }


                array_shift($data['sorts']);
                
            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('product/search', $url . '&page={page}');

            $data['pagination'] = $pagination->render();

	  // remarketing all in one
	  $search_page = (!empty($this->request->get['route']) && $this->request->get['route'] == 'product/search' && !empty($this->request->get['search'])) ? true : false;
	  if (empty($data['heading_title'])) $data['heading_title'] = $this->language->get('heading_title');
	  
	  $data['remarketing_target_code'] = '';
	  $data['facebook_remarketing_code'] = '';
	  $data['target_page'] = 'category';
	  $data['google_page'] = 'view_item_list';
	  $data['vk_page'] = 'view_category';
	  $data['remarketing_vk_code'] = '';
	  
	  if ($search_page) {
	      $data['google_page'] = 'view_search_results';
	      $data['target_page'] = 'category';
		  $data['vk_page'] = 'view_search';
		  $data['view_search_results'] = true;
	  }
	  
	  $this->load->model('tool/remarketing');
	  
	  if ($this->config->get('remarketing_status') && !$this->model_tool_remarketing->isBot()) {
	  if ($this->config->get('remarketing_google_status') && $this->config->get('remarketing_google_identifier')) {
		  $data['remarketing_google_json'] = [];
		  if ($data['google_page']) {
			$items = [];
			if (isset($data['remarketing_google_ids']) && count($data['remarketing_google_ids']) > 0) {
				foreach ($data['remarketing_google_ids'] as $item) {
					$items[] = [
						'id' => $item, 
						'google_business_vertical' => 'retail'
					];
				}
			}
			$data['remarketing_google_json'] = [
				'event' => $data['google_page'],
				'data'  => [
					'send_to' => $this->config->get('remarketing_google_identifier'),
					'items'   => $items
				]
			];
			}
	  }
	  
	  $fb_time = time();
	   
	  if ($this->config->get('remarketing_facebook_status') && $this->config->get('remarketing_facebook_identifier') && $this->config->get('remarketing_facebook_pixel_status')) {
	  	  $data['facebook_remarketing_status'] = true;
	  	  $data['facebook_remarketing_code'] .= '<script>' . "\n";
	  	  $data['facebook_remarketing_code'] .= "$(document).ready(function() {" . "\n";
	  	  $data['facebook_remarketing_code'] .= "if (typeof fbq != 'undefined') {"."\n";
		  if (!$search_page) {
			  $data['facebook_remarketing_code'] .= "fbq('trackCustom', 'ViewCategory', {" . "\n";
		  } else {
			  $data['facebook_remarketing_code'] .= "fbq('track', 'Search', {" . "\n";
			  $data['facebook_remarketing_code'] .= "search_string: '" . $this->request->get['search'] . "'," . "\n";
		  }
	  	  $data['facebook_remarketing_code'] .= "content_name: '" . $data['heading_title'] . "'," . "\n";
	  	  if (!empty($data['remarketing_facebook_ids'])) {
			  $data['facebook_remarketing_code'] .= "content_ids: ['" . implode('\',\'', $data['remarketing_facebook_ids']) . "']," . "\n";
	  	  }
	  	  $data['facebook_remarketing_code'] .= "content_type: 'product'," . "\n";
	  	  $data['facebook_remarketing_code'] .= "currency: '" . $this->config->get('remarketing_facebook_currency') ."'," . "\n";
	  	  $data['facebook_remarketing_code'] .= "value: 0" . "\n";
	  	  $data['facebook_remarketing_code'] .= '}, {eventID: ' . $fb_time . '})}});' . "\n</script>\n";
	  }
	
	  if ($this->config->get('remarketing_facebook_status') && $this->config->get('remarketing_facebook_server_side') && $this->config->get('remarketing_facebook_token')) {
		  $data['facebook_remarketing_status'] = true;
		  $content_ids = [];
		  if (isset($data['remarketing_facebook_ids']) && count($data['remarketing_facebook_ids']) > 0) {
			foreach ($data['remarketing_facebook_ids'] as $id) {
				$content_ids[] = $id;
			}
	  	  }
		$data['facebook_data_json_category']['products'] = [
			'value'            => 0,
			'currency'         => $this->config->get('remarketing_facebook_currency'),
			'content_ids'      => $content_ids,
			'content_type'     => 'product',
			'content_name'     => addslashes($data['heading_title']),
			'content_category' => !empty($category_info['name']) ? addslashes($category_info['name']) : '',
			'opt_out'          => false
		];
	
		$data['facebook_data_json_category']['time'] = $fb_time;
	  }
	  
	  if ($this->config->get('remarketing_mytarget_status') && $this->config->get('remarketing_mytarget_identifier') && $data['target_page']) {	
		  if (count($data['remarketing_target_ids']) > 1) {
			  $target_itemid = '[\'' . implode('\',\'', $data['remarketing_target_ids']) . '\']';
		  } elseif (!empty($data['remarketing_target_ids'])) {
			  $target_itemid = "'" . $data['remarketing_target_ids'][0] . "'";
		  } else {
	          $target_itemid = '';
		  }
		  $data['remarketing_target_code'] .= '<!-- Rating@Mail.ru counter dynamic remarketing appendix -->' . "\n";
		  $data['remarketing_target_code'] .= '<script>' . "\n";
		  $data['remarketing_target_code'] .= 'var _tmr = _tmr || [];' . "\n";
		  $data['remarketing_target_code'] .= '_tmr.push({' . "\n";
		  $data['remarketing_target_code'] .= "type: 'itemView'," . "\n";
		  if (!empty($target_itemid)) $data['remarketing_target_code'] .= "productid: " . $target_itemid . "," . "\n";
		  if (!empty($data['target_page'])) $data['remarketing_target_code'] .= "pagetype: '" . $data['target_page'] . "'," . "\n"; 
		  $data['remarketing_target_code'] .= "list: '" . $this->config->get('remarketing_mytarget_identifier') . "'," . "\n";
		  $data['remarketing_target_code'] .= '});' . "\n"; 
		  $data['remarketing_target_code'] .= '</script>' . "\n";
		  $data['remarketing_target_code'] .= '<!-- // Rating@Mail.ru counter dynamic remarketing appendix -->' . "\n";
	  }
	  
	  if ($this->config->get('remarketing_vk_status') && $this->config->get('remarketing_vk_identifier') && $data['vk_page']) {	
		  if (!empty($data['products'])) {
			 $eventParams = [];
			 $eventParams['currency_code'] = $this->session->data['currency'];
			 foreach ($data['products'] as $product) {
			 $eventParams['products'][] = [
				'id'    =>  $this->config->get('remarketing_vk_id') == 'id' ? $result['product_id'] : $result['model'],
				'price' => $product['clear_price']
				]; 
			 }
			 $data['remarketing_vk_code'] .= '<script>' . "\n";
			 $data['remarketing_vk_code'] .= "$(document).ready(function() { setTimeout(function() { if (typeof VK != 'undefined') {" . "\n";
			 $data['remarketing_vk_code'] .= "VK.Retargeting.ProductEvent(" . $this->config->get('remarketing_vk_identifier') . ", '" . $data['vk_page'] . "', " . json_encode($eventParams) . ");" . "\n";
			 $data['remarketing_vk_code'] .= '}}, 1000)})' . "\n";
			 $data['remarketing_vk_code'] .= '</script>' . "\n";
		  }
	  }
	
	if ($this->config->get('remarketing_ecommerce_status') || $this->config->get('remarketing_ecommerce_measurement_status')) {
			$data['remarketing_ecommerce_status'] = $this->config->get('remarketing_ecommerce_status');
			$data['remarketing_ecommerce_json'] = [];
			$data['measurement_status'] = $this->config->get('remarketing_ecommerce_measurement_status');
			$currency = $this->config->get('remarketing_ecommerce_currency');
			$i = 0;
			foreach ($data['products'] as $product) {
				$data['remarketing_ecommerce_json'][$i] = [];
				$data['remarketing_ecommerce_json'][$i]['name'] = addslashes($product['name']);
				$data['remarketing_ecommerce_json'][$i]['id'] = ($this->config->get('remarketing_ecommerce_id') == 'id' ? $product['product_id'] : $product['model']);
				$data['remarketing_ecommerce_json'][$i]['price'] = $product['ecommerce_price'];
				$data['remarketing_ecommerce_json'][$i]['brand'] = addslashes($product['brand']);
				$data['remarketing_ecommerce_json'][$i]['list'] = addslashes($data['heading_title']);
				$data['remarketing_ecommerce_json'][$i]['currency'] = $currency;
				$data['remarketing_ecommerce_json'][$i]['category'] = addslashes($data['heading_title']);
				$data['remarketing_ecommerce_json'][$i]['position'] = $i+1;
				$i++; 
			}
				
			
			$data['ecommerce_selector'] = $this->config->get('remarketing_ecommerce_selector');
		}
		
		if ($this->config->get('remarketing_ecommerce_ga4_status') || $this->config->get('remarketing_ecommerce_ga4_measurement_status')) {
			$data['ecommerce_ga4_status'] = true;
			$data['remarketing_ecommerce_ga4_json'] = [];
			$data['measurement_ga4_status'] = $this->config->get('remarketing_ecommerce_ga4_measurement_status');
			
			$currency = $this->config->get('remarketing_ecommerce_currency');
			$i = 0;
			foreach ($data['products'] as $product) {
				$data['remarketing_ecommerce_ga4_json'][$i] = [];
				$data['remarketing_ecommerce_ga4_json'][$i]['item_name'] = addslashes($product['name']);
				$data['remarketing_ecommerce_ga4_json'][$i]['item_id'] = ($this->config->get('remarketing_ecommerce_ga4_id') == 'id' ? $product['product_id'] : $product['model']);
				$data['remarketing_ecommerce_ga4_json'][$i]['price'] = $product['ecommerce_price'];
				if (!empty($product['brand'])) $data['remarketing_ecommerce_ga4_json'][$i]['item_brand'] = addslashes($product['brand']);
				$data['remarketing_ecommerce_ga4_json'][$i]['item_list_name'] = addslashes($data['heading_title']);
				$data['remarketing_ecommerce_ga4_json'][$i]['item_category'] = addslashes($data['heading_title']);
				$data['remarketing_ecommerce_ga4_json'][$i]['index'] = $i+1;
				$data['remarketing_ecommerce_ga4_json'][$i]['quantity'] = 1;
				$i++;
			}

			$data['remarketing_ecommerce_ga4_selector'] = $this->config->get('remarketing_ecommerce_ga4_selector');
		}
		
		if ($this->config->get('remarketing_esputnik_status') && $this->customer->isLogged()) {
			$data['esputnik_remarketing_status'] = true;
			$data['esputnik_data_category_json'] = [
				'productCategoryId' => addslashes($data['heading_title'])
			];
		} 
	}

	  

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            if (isset($this->request->get['search']) && $this->config->get('config_customer_search')) {
                $this->load->model('account/search');

                if ($this->customer->isLogged()) {
                    $customer_id = $this->customer->getId();
                } else {
                    $customer_id = 0;
                }

                if (isset($this->request->server['REMOTE_ADDR'])) {
                    $ip = $this->request->server['REMOTE_ADDR'];
                } else {
                    $ip = '';
                }

                $search_data = array(
                    'keyword'       => $search,
                    'category_id'   => $category_id,
                    'sub_category'  => $sub_category,
                    'description'   => $description,
                    'products'      => $product_total,
                    'customer_id'   => $customer_id,
                    'ip'            => $ip
                );

                $this->model_account_search->addSearch($search_data);
            }
        }

        $data['search'] = $search;
        $data['description'] = $description;
        $data['category_id'] = $category_id;
        $data['sub_category'] = $sub_category;

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput(magicRender($this->load->view('product/search', $data),$this,'search',(isset($results) ? $results : array())));
    }
}
