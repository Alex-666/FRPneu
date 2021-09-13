<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

if (version_compare(VERSION,'2.3','>=')) { //newer than 2.2.x

    $modulesPath = 'extension/module';
    $englishPath = 'en-gb';
} else {
    $modulesPath = 'module';
    $englishPath = 'english';
}

$GLOBALS['magicslideshow_module_loaded'] = 'true'; // to fix boxes and pages conflict, I thunk we could find a better way in future

if (defined('HTTP_ADMIN')) {
    define ('MTOOLBOX_ADMIN_FOLDER_magicslideshow',str_replace('catalog',preg_replace('/.*?([^\/]*)\/$/is','$1',HTTP_ADMIN),DIR_APPLICATION) . 'controller/'.$modulesPath.'/magicslideshow-opencart-module/');
} else {
    //define ('MTOOLBOX_ADMIN_FOLDER_magicslideshow',DIR_APPLICATION . 'controller/'.$modulesPath.'/magicslideshow-opencart-module/');
    define ('MTOOLBOX_ADMIN_FOLDER_magicslideshow',str_replace('catalog','admin',DIR_APPLICATION) . 'controller/'.$modulesPath.'/magicslideshow-opencart-module/');
}


function magicslideshow($content, $currentController = false , $type = false, $info = false) {

    
    $settings = $currentController->config->get('magicslideshow_settings');
    $magicslideshow_status = $settings['magicslideshow_status'];
  

    if ($magicslideshow_status != 0) {

        $tool = & magicslideshow_load_core_class($currentController);
	
	
	if ($tool->params->profileExists($type)) { //for main profiles
	    $tool->params->setProfile($type);
	}

        $enabled_on_this_page = false;

        unset($GLOBALS['magictoolbox']['items']);

        if ($tool->type == 'standard') { //do not apply MSS-like modules to category & product pages
            if ($tool->params->checkValue('page-status','Yes') && (!$tool->params->checkValue('zoomMode', 'off') || !$tool->params->checkValue('expand', 'off')) && $tool->params->profileExists($type)) {
		$enabled_on_this_page = true;
	    }
        }

        if ($tool->type == 'circle') { //Apply 360 only to Products Page 
            if ($type && $type == 'product') {
                    $gallery = magic360_get_gallery($info['product_id']);
                    if ($gallery) {
                        $info['gallery_images'] = explode(';',$gallery['images']);
                        $tool->params->setValue('columns',$gallery['columns'],'product');
                        $enabled_on_this_page = true;
                    } else {
                        $info['gallery_images'] = array();
                    }
            }

	} else { //boxes

		if ($type && ($type == 'latest_home_category' || $type == 'latest_home' || $type == 'latest_right' || $type == 'latest_left' || $type == 'latest_content_top' || $type == 'latest_content_bottom' || $type == 'latest_column_left' || $type == 'latest_column_right')) {
			$tool->params->setProfile('latest');
		}
		if ($type && ($type == 'featured_home' || $type == 'featured_right' || $type == 'featured_left' || $type == 'featured_left' || $type == 'featured_content_top' || $type == 'featured_content_bottom' || $type == 'featured_column_left' || $type == 'featured_column_right')) {
			$tool->params->setProfile('featured');
		}
		
		if ($type && ($type == 'special_home' || $type == 'special_right' || $type == 'special_left' || $type == 'special_content_top' || $type == 'special_content_bottom' || $type == 'special_column_left' || $type == 'special_column_right')) {
			$tool->params->setProfile('special');
		}
		
		if ($type && ($type == 'bestseller_home' || $type == 'bestseller_right' || $type == 'bestseller_left' || $type == 'bestseller_content_top' || $type == 'bestseller_content_bottom' || $type == 'bestseller_column_left' || $type == 'bestseller_column_right')) {
			$tool->params->setProfile('bestseller');
		}
		
		if ($tool->params->checkValue('page-status','Yes') && (!$tool->params->checkValue('zoomMode', 'off') || !$tool->params->checkValue('expand', 'off'))) {
		    $enabled_on_this_page = true;
		}


	}

        if ($enabled_on_this_page) {

	    if ($type && $info) {
		$GLOBALS['magictoolbox']['page_type'] = $type;
		$GLOBALS['magictoolbox']['prods_info'] = $info;
	    } else {
		return $content;
	    }
            
            $oldContent = $content;
            $GLOBALS['current_profile'] = $tool->params->getProfile();
            $content = magicslideshow_parse_contents($content,$currentController);
            //if ($oldContent != $content) $content = magicslideshow_set_headers($content);
            $content = magicslideshow_set_headers($content); //TODO ?
            
            if ($type == 'product') $pid = $GLOBALS['magictoolbox']['prods_info']['product_id'];
            
	    if ($type == 'product' && $tool->type == 'standard') {
                // template helper class
                $tool->params->setProfile($type);
                require_once (MTOOLBOX_ADMIN_FOLDER_magicslideshow.'magictoolbox.templatehelper.class.php');
                MagicToolboxTemplateHelperClass::setPath(MTOOLBOX_ADMIN_FOLDER_magicslideshow.'templates');
                MagicToolboxTemplateHelperClass::setOptions($tool->params);
                $scroll = magicslideshow_LoadScroll($tool);
                $html = MagicToolboxTemplateHelperClass::render(array(
                    'main' => magicslideshow_getMainTemplate($GLOBALS['magictoolbox']['prods_info'],$type),
                    'thumbs' => magicslideshow_getSelectorsTemplate($GLOBALS['magictoolbox']['prods_info'],$type),
                    'magicscrollOptions' => $scroll ? $scroll->params->serialize(false, '', 'product-magicscroll-options') : '',
                    'pid' => $pid,
                ));

                preg_match('/(<a[^>]*?class="Magic360[^>]*>.*?<\/a>)/is',$content,$m360); //get 360 container
                $content = preg_replace('/(<a[^>]*?class="Magic360[^>]*>.*?<\/a>)/is','MAGICTOOLBOX_PLACEHOLDER',$content); //cut 360 it from code
                
                
                $content = str_replace('MAGICTOOLBOX_PLACEHOLDER', $html, $content);
                
                

            } else if ($type != 'product' && $tool->type == 'category' ) { //scroll & slideshow boxes
            
		$tool->params->setProfile($GLOBALS['current_profile']);
            
		$items = array();
		$position = '';
		
		$shop_dir = str_replace('system/','',DIR_SYSTEM);
		$image_dir = str_replace ($shop_dir,'',DIR_IMAGE);

		if (preg_match('/.*_content_(top|bottom).*/is',$type)) $position = 'home';
		if (preg_match('/.*_column_(left|right).*/is',$type,$matches)) {
		    $position = $matches[1];
		    $tool->params->setValue('orientation','vertical');
		}

		foreach ($GLOBALS['magictoolbox']['prods_info'] as $product) {
		
		    $src = $image_dir.$product['image'];
		    $img = magicslideshow_getThumb($src,'original',$product['product_id']);
		    $thumb = magicslideshow_getThumb($src,$position.'-thumb',$product['product_id']);
		    $thumb2x = magicslideshow_getThumb($src,$position.'-thumb2x',$product['product_id']);                
		    
		    if (isset($GLOBALS['magictoolbox']['plinks'][$product['product_id']])) {
			$link = $GLOBALS['magictoolbox']['plinks'][$product['product_id']];
		    }
		    $title = $product['name'];
		    
		    if (!empty($product['special'])) {
                        $price = $product['special'];
                    } else {
                        $price = $product['price'];
                    }
                    
                    $description = ($currentController->currency->format($currentController->tax->calculate($price, $product['tax_class_id'], $currentController->config->get('config_tax')), $currentController->session->data['currency']));
                    
		    $items[] = array('img' => $thumb, 'thumb' => $thumb, 'thumb2x' => $thumb2x, 'title' => $title, 'description' => $description, 'link' => $link);
		}
		$html = $tool->getMainTemplate($items);
		$content = str_replace('MAGICTOOLBOX_PLACEHOLDER', $html, $content);
		$GLOBALS['magictoolbox']['plinks'] = array();
		
             } else if ($type == 'product' && $tool->type == 'circle' ) {
                $items = array();
                $tool->params->setProfile('product');
                if (isset($GLOBALS['magictoolbox']['prods_info']['gallery_images']) && count($GLOBALS['magictoolbox']['prods_info']['gallery_images'])) {
                    foreach ($GLOBALS['magictoolbox']['prods_info']['gallery_images'] as $image) {
                        
                        $src = 'image/magic360/'.$pid.'/'.$image;
                        
                        if ($tool->params->checkValue('original-images','no','default')) {
                            $img = magicslideshow_getThumb($src,'original',$pid);
                            $medium = magicslideshow_getThumb($src,'thumb',$pid);
                        } else {
                            $img = $medium = HTTPS_SERVER.$src;
                        }
                        
                        
                        $items[] = array('img' => $img, 'medium' => $medium);
                    }
                    
                    $html = $tool->getMainTemplate($items);
                    
                    $content = str_replace('MAGICTOOLBOX_PLACEHOLDER', $html, $content);
                }
            }



        $content = str_replace('$(\'.thumbnails\').magnificPopup({','$(\'.thumbnails-mt\').magnificPopup({',$content); //cut magnificPopup
        }
    }
    
    return $content;
}

function magicslideshow_set_headers ($content, $headers = false, $controller = false) {


    if (isset($GLOBALS["magictoolbox"]["magicslideshow"])) {
        $plugin = $GLOBALS["magictoolbox"]["magicslideshow"];
    } else {
        $plugin = magicslideshow_load_core_class($controller);  
    } 
    
    if (empty($GLOBALS['magictoolbox']['page_type']) && $plugin->params->getValue('headers-on-every-page','general') != 'Yes') { 
        return $content;
    }
	

    if (!$headers) {

        $prefix = '';
        if (preg_match("/components\/com_(ayelshop|aceshop|mijoshop)\/opencart\//ims",DIR_APPLICATION,$matches) || strpos($content,'</head>')) {
            if (!$controller) {
                $controller = $GLOBALS['magictoolbox']['currentController'];
            }
            $prefix = $controller->config->get('site_ssl');
            if ($matches) $prefix = 'components/com_'.$matches[1].'/opencart/';
            $headers = $plugin->getHeadersTemplate($prefix.'catalog/view/javascript',$prefix.'catalog/view/css');
        }

        

    }

    preg_match('/var\s+magicAddEvent\s+\=\s+\"je1\";/is',$content,$matches); 
    
    if (!count($matches)) { //check if already present
    
        if (!$plugin->params->checkValue('use-effect-on-category-page', 'No') || !$plugin->params->checkValue('use-effect-on-manufacturers-page', 'No') || !$plugin->params->checkValue('use-effect-on-search-page', 'No')) {//fix for category && manufacturers view switch
            $headers .= '<script type="text/javascript">
                        var magicAddEvent = "je1";
                        if(typeof(magicJS.Doc.je1) == "undefined") magicAddEvent = "jAddEvent";
                        
                        $mjs(document)[magicAddEvent](\'domready\', function() {
                        if (typeof display !== \'undefined\') {
                            var olddisplay = display;
                            window.display = function (view) {
                            if ("MagicSlideshow" != "MagicZoomPlus") {
                                MagicSlideshow.stop();
                                olddisplay(view);
                                MagicSlideshow.start();
                            } else {
                                MagicZoom.stop();
                                olddisplay(view);
                                MagicZoom.start();
                            }
                            }
                        }
                        });
                    </script>';
        }
    }
    

    if ($headers && $content && !isset($GLOBALS['magicslideshow_headers_set'])) {

        //if (preg_match("/components\/com_(ayelshop|aceshop|mijoshop)\/opencart\//ims",DIR_APPLICATION)) {
        if (preg_match("/components\/com_jcart/ims",DIR_APPLICATION) || preg_match("/components\/com_(ayelshop|aceshop|mijoshop)\/opencart\//ims",DIR_APPLICATION)) {
            $content = $headers.$content;
            $GLOBALS['magicslideshow_headers_set'] = true;
        } else {
            $content = preg_replace('/\<\/head\>/is',"\n".$headers."\n</head>",$content,1,$matched);
        }

        if ($matched > 0) $GLOBALS['magicslideshow_headers_set'] = true;
    } else if (isset($GLOBALS['magictoolbox']['scrollHeaders']) && !isset($GLOBALS['magictoolbox']['scrollHeadersSet']) && $GLOBALS['magictoolbox']['page_type'] == 'product') { //if scroll headers still not printed
        $content = preg_replace('/\<\/head\>/is',"\n".$GLOBALS['magictoolbox']['scrollHeaders']."\n</head>",$content,1,$matched);
        if ($matched > 0) $GLOBALS['magictoolbox']['scrollHeadersSet'] = true;
    }
    return $content;
}

function &magicslideshow_load_core_class($currentController = false) {
    if(!isset($GLOBALS["magictoolbox"])) $GLOBALS["magictoolbox"] = array();
    if(!isset($GLOBALS["magictoolbox"]["magicslideshow"])) {
        /* load core class */
        require_once (MTOOLBOX_ADMIN_FOLDER_magicslideshow.'magicslideshow.module.core.class.php');
        $tool = new MagicSlideshowModuleCoreClass();
        /* add category for core params */
        $params = $tool->params->getParams();
        foreach($params as $k => $v) {
            $v['category'] = array(
                "name" => 'General options',
                "id" => 'general-options'
            );
            $params[$k] = $v;
        }
        $tool->params->appendParams($params);

        $GLOBALS["magictoolbox"]["magicslideshow"] = & $tool;
    }
    if($currentController) {


        $GLOBALS['magictoolbox']['currentController'] = $currentController; //SEO url fix
        
	$settings = $currentController->config->get('magicslideshow_settings');
	
	foreach($settings as $param_name => $param_value) {
	    if (preg_match('/([^_]*?)_([^_]*)/is',$param_name,$matches)) {
		if (!preg_match('/watermark/is',$param_name)) {
		    $GLOBALS["magictoolbox"]["magicslideshow"]->params->setValue($matches[2],$param_value,$matches[1]);
		} else if (preg_match('/default_watermark/is',$param_name)) {
		    $GLOBALS["magictoolbox"]["magicslideshow"]->params->setValue($matches[2],$param_value,$matches[1]);
		    $GLOBALS["magictoolbox"]["magicslideshow"]->params->setValue(str_replace('default_','',$param_name),$param_value,'default');
		}
	    }
	} 

        $selectorsSize = false;
        if($GLOBALS['magictoolbox']['magicslideshow']->params->checkValue('selectors', array('bottom', 'top'))) {
            $selectorsSize = $GLOBALS['magictoolbox']['magicslideshow']->params->getValue('selector-max-height');
        } else if($GLOBALS['magictoolbox']['magicslideshow']->params->checkValue('selectors', array('right', 'left'))) {
            $selectorsSize = $GLOBALS['magictoolbox']['magicslideshow']->params->getValue('selector-max-width');
        }
        if($selectorsSize) {
            $GLOBALS['magictoolbox']['magicslideshow']->params->setValue('selectors-size', $selectorsSize);
        }

    }
    return $GLOBALS["magictoolbox"]["magicslideshow"];
}

function magicslideshow_parse_contents($content,$currentController) {

    $plugin = $GLOBALS['magictoolbox']['magicslideshow'];
    $type = $GLOBALS['magictoolbox']['page_type'];

    
        

    //some bugs fix
    $content = str_replace("<!--code start-->",'',$content);
    $content = str_replace("<!--code end-->",'',$content);
    if (empty($GLOBALS['magictoolbox']['prods_info']['image']) && isset($GLOBALS['magictoolbox']['prods_info']['images'][0]['image'])) {
        $GLOBALS['magictoolbox']['prods_info']['image'] = $GLOBALS['magictoolbox']['prods_info']['images'][0]['image'];
    }

    if ($type == 'product') {
    
        $enabled = true;
        $keepSelectors = false;
        $plugin->params->setProfile('product');

        if ($plugin->type == 'circle') {
            
            $all_img = $GLOBALS['magictoolbox']['prods_info']['images'];
            if (isset($GLOBALS['magictoolbox']['prods_info']['image']) && !empty($GLOBALS['magictoolbox']['prods_info']['image'])) {
                $all_img[]['image'] = $GLOBALS['magictoolbox']['prods_info']['image'];
            }
            $enabled = $plugin->isEnabled($all_img,$GLOBALS['magictoolbox']['prods_info']['product_id']);
        }

        if ($enabled) {
        
            $oldContent = $content;
    	    
            $content = preg_replace('/<ul class="thumbnails">.*?<\/ul>/is','MAGICTOOLBOX_PLACEHOLDER',$content,1); //replace main image and stop
            
            if ($content == $oldContent) { //journal3
                $content = preg_replace('/<div class="product\-image.*?[^<]*<div class="swiper main\-image".*?<div class="lightgallery lightgallery\-product\-images".*?<\/div>/ims','MAGICTOOLBOX_PLACEHOLDER',$content,1); //replace main image and stop
            }

            if ($content == $oldContent) {
                $content = preg_replace('/<ul id="product-gallery" class="gc-start">.*?<\/ul>/is','MAGICTOOLBOX_PLACEHOLDER',$content,1); //replace main image and stop
            }

            if ($content == $oldContent) {
                $content = preg_replace('/<a[^>]*?id=\"mainimage\"[^<]*?<img[^<]*?<\/a>/is','MAGICTOOLBOX_PLACEHOLDER',$content,1); //replace main image and stop
            }
	    
	        if ($content == $oldContent) {
                    $content = preg_replace('/<div class="view\-product">.*?<\/div>/is','MAGICTOOLBOX_PLACEHOLDER',$content,1); //replace main image and stop
	        }
	    
	        if ($content == $oldContent) { //cut swiper
                    $content = preg_replace('/<div class="swiper\-container[^>]*>[^<]*<div class="swiper\-wrapper">([^<]*<div class="swiper\-slide">.*?<\/div>[^<]*){1,}<div class="swiper\-pagination"><\/div>[^<]*<\/div>/ims', 'MAGICTOOLBOX_PLACEHOLDER', $content);
	        }
	    
	    
    	    $content = preg_replace('/<div id="qt\-similar\-product"/is','$0 style="display:none"',$content);

	    
	        $content = preg_replace('/<a[^>]*?id=\"mainimage\"[^<]*?<img[^<]*?<\/a>/is','',$content); //cut the possible first selector with id="mainimage"
            $content = preg_replace('/<a[^>]*?id=\"selector\"[^<]*?<img[^<]*?<\/a>/is','',$content);
            $content = preg_replace('/<div[^>]*?class=\"swiper-slide additional-image\"[^<]*?<img[^>]*?\/>.*?<\/div>/is','',$content);
            
            $content = preg_replace('/<a[^>]*?id=\"selector\"[^<]*?<img[^<]*?<\/a>/is','',$content);
            
            if (preg_match('/(<a[^>]*?class="Magic360)/is',$content)) {
                array_unshift($GLOBALS['magictoolbox']['prods_info']['images'],array('image' => '../catalog/view/css/360icon.png'));//add 360 icon to selectors
            }
            
        }

    } else {
		      
        $products = $GLOBALS['magictoolbox']['prods_info'];
        
        $productsKeys = array_keys($products);
        if (!isset($products[$productsKeys[0]]['product_id'])) return $content; //check if products are present on page
        
        if ($plugin->type != 'category') {
	    $content = magicslideshow_category_like_replace($products,$type,$content);
	} else {
	    $content = magicslideshow_cut_boxes($products,$type,$content); //cut all product images
	}

    } 

    if (isset($jsOptions) && count($jsOptions)  && $content != $oldContent) $content = str_replace('</head>','<script type="text/javascript"> MagicSlideshowOptiMages = '.json_encode($jsOptions).'; </script></head>',$content);

    $content = str_replace('var poip_product_settings','var poip_product_settings = window[\'mt_poip_product_settings\']',$content); //poip variations

    return $content;
}

function magicslideshow_getProductParams ($id, $params = false) {
    if (!$params) $params = $GLOBALS['magictoolbox']['prods_info'];
    foreach ($params as $key=>$product_array) {
        if ($product_array['product_id'] == $id) {
            return $product_array;
        }
    }
}

function magicslideshow_getThumb($src, $size = null, $pid = null) {
    if($size === null) $size = 'thumb';
    require_once (MTOOLBOX_ADMIN_FOLDER_magicslideshow.'magictoolbox.imagehelper.class.php');
    
    if (defined('HTTP_IMAGE')) {
        $url = str_replace('image/','',HTTP_IMAGE);
    } else {
        $url = HTTP_SERVER;
    }
    $shop_dir = str_replace('system/','',DIR_SYSTEM);
    $image_dir = str_replace ($shop_dir,'',DIR_IMAGE);

    if (!isset($GLOBAL['imagehelper'])) {
        $GLOBAL['imagehelper'] = new MagicToolboxImageHelperClass($shop_dir, '/'.$image_dir.'magictoolbox_cache', $GLOBALS["magictoolbox"]["magicslideshow"]->params, null, $url);
    }
    return preg_replace('/https?\:\/\//is','//',$GLOBAL['imagehelper']->create('/' . $src, $size, $pid));
}

function magicslideshow_getMainTemplate($info,$type) {

    $plugin = $GLOBALS["magictoolbox"]["magicslideshow"];
    
    $shop_dir = str_replace('system/','',DIR_SYSTEM);
    $image_dir = str_replace ($shop_dir,'',DIR_IMAGE);
    
    $title = $info['name'];
    $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
    $description = $info['description'];
    $description = htmlspecialchars(htmlspecialchars_decode($description, ENT_QUOTES));
    $id = $info['product_id'].'_'.$type;
    
    if ($type == 'product') {
        $js_id = '<script type="text/javascript"> 
                    idimg = "MagicSlideshowImage'.$id.'"; 
                    productId = '.(int)$info['product_id'].'; 
                </script>';
    } else {
        $js_id = '';
    }

    if ($plugin->params->checkValue('original-images','no','default')) {
        $src = $image_dir.$info['image'];
        $img = magicslideshow_getThumb($src,'original',$id);
        $thumb = magicslideshow_getThumb($src,null,$id);
        $thumb2x = magicslideshow_getThumb($src,'thumb2x',$id);    
    } else {
         if (isset($info['thumb'])) {
            $img = $info['popup'];
            $thumb = $info['thumb'];
        } else {
            $img = $thumb = $info['popup'];
        }
        $thumb2x = '';
    }
    
    $link = '';
    if (isset($info['link'])) $link = $info['link'];
    
    $img_org = $img = $plugin->getMainTemplate(compact('img','thumb', 'thumb2x','id','title','description','link'));
    $img = str_replace('rel="','rel="group: '.$type.'; ',$img);
    if ($img_org == $img) $img = str_replace('<a','<a rel="group: '.$type.'" ',$img);
    $result = $js_id.$img;
    
    return $result; 
}

function magicslideshow_getSelectorsTemplate($info,$type) {

    $plugin = $GLOBALS["magictoolbox"]["magicslideshow"];

    $controller = $GLOBALS['magictoolbox']['currentController'];
    $controller->load->model('tool/image');

    $shop_dir = str_replace('system/','',DIR_SYSTEM);
    $image_dir = str_replace ($shop_dir,'',DIR_IMAGE);
    
    $title = $info['name'];
    $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
    $id = $info['product_id'].'_'.$type;
    
    $result = array();
    $spinPresent = false;
    
    $images = $info['images'];

    /*Product Option Color and Size Combination Pro START*/
    $query = $controller->db->query("SHOW TABLES LIKE '".DB_PREFIX."product_option_value'");
    if ($query->num_rows) {
        $query = $controller->db->query("SELECT * FROM ".DB_PREFIX."product_option_value WHERE product_id = '".(int)$info['product_id']."'");
        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                if (isset($row['image']) && !empty($row['image'])) {
                    $images[] = array(
                        'image' => $row['image'],
                        'product_id' => $row['product_id'],
                    );

                    $info['images_original'][] = array(
                        'popup' => $controller->model_tool_image->resize($row['image'], $controller->config->get('theme_' . $controller->config->get('config_theme') . '_image_popup_width'), $controller->config->get('theme_' . $controller->config->get('config_theme') . '_image_popup_height')),

                        'medium' => $controller->model_tool_image->resize($row['image'], $controller->config->get((null !== $controller->config->get('theme_'.$controller->config->get('config_theme') . '_image_thumb_width') ? 'theme_' : '') . $controller->config->get('config_theme') . '_image_thumb_width'), $controller->config->get((null !== $controller->config->get('theme_'.$controller->config->get('config_theme') . '_image_thumb_width') ? 'theme_' : '') . $controller->config->get('config_theme') . '_image_thumb_height')),

                        'thumb' => $controller->model_tool_image->resize($row['image'], $controller->config->get((null !== $controller->config->get('theme_'.$controller->config->get('config_theme') . '_image_additional_width') ? 'theme_' : '') . $controller->config->get('config_theme') . '_image_additional_width'), $controller->config->get((null !== $controller->config->get('theme_'.$controller->config->get('config_theme') . '_image_additional_width') ? 'theme_' : '') . $controller->config->get('config_theme') . '_image_additional_height')),
                    );

                }
            }
        }
    }
    /*Product Option Color and Size Combination Pro END*/


    if (isset($info['image']) && !empty($info['image'])) { //add main image to selectors
        if (isset($images[0]) && strpos($images[0]['image'],'360icon.png')) {
            $m360selector = array_shift($images);
            array_unshift($images,array('image' => $info['image']));
            array_unshift($info['images_original'],array('popup' => $info['popup'],
                                                    'medium' => $info['medium'],
                                                    'thumb' => $info['selector']
                                                    ));

            array_unshift($images,$m360selector);
            $spinPresent = true;
        } else {
            array_unshift($info['images_original'],array('popup' => $info['popup'],
                                                    'medium' => $info['medium'],
                                                    'thumb' => $info['selector']
                                                    ));

            array_unshift($images,array('image' => $info['image']));
        }
    }
    
    //if (is_array($images) && count($images) > 1) {
    if (is_array($images) && (count($images) > 1 || $videoPresent || $spinPresent)) {
	    foreach ($images as $img_id => $image) {
	    
            if ($spinPresent && !$plugin->params->checkValue('watermark', '')) { //skip the first image (spin selector)
                $watermark_original = $plugin->params->getValue('watermark'); 
                $plugin->params->setValue('watermark',''); 
                $spinPresent = false;
            }

            if ($plugin->params->checkValue('original-images','no','default') || strpos($image['image'],'360icon') != false) {
	            $src = $image_dir.$image['image'];
	            $img = magicslideshow_getThumb($src,'original',$id);
	            $medium = magicslideshow_getThumb($src,null,$id);
	            $medium2x = magicslideshow_getThumb($src,'thumb2x',$id);
                $medium2x = str_replace(' ','%20',$medium2x);//fix for chrome
	            $thumb = magicslideshow_getThumb($src,'selector',$id);
	            $thumb2x = magicslideshow_getThumb($src,'selector2x',$id);                
                $thumb2x = str_replace(' ','%20',$thumb2x);//fix for chrome
            } else {

                if ($spinPresent && isset($info['images_original'][$img_id - 1])) $img_id--;

                $img =  str_replace('" id="selector','',$info['images_original'][$img_id]['popup']);
                $medium = $info['images_original'][$img_id]['medium'];
                $thumb = $info['images_original'][$img_id]['thumb'];
                $thumb2x = $medium2x = '';
            }

	        
	        $result[] = $plugin->getSelectorTemplate(compact('title','img','medium', 'medium2x','thumb', 'thumb2x','id'));;
	        
	        if (isset($watermark_original)) { //restore watermark after spin render
                $plugin->params->setValue('watermark',$watermark_original); 
                unset($watermark_original);
           }
	    }
     }
     
    
     
    return $result;
}

function magicslideshow_set_params_from_config ($config = false) {
    if ($config) {
        $plugin = $GLOBALS["magictoolbox"]["magicslideshow"];

        foreach ($plugin->params->getNames() as $name) {
            if ($config->get($name)) {
                $plugin->params->setValue($name,$config->get($name));
            }
        }
        foreach ($plugin->params->getParams() as $param) {
            if (!isset($param['value'])) {
                $plugin->params->setValue($param['id'],$plugin->params->getValue($param['id']));
            }
        }

        $plugin->general->appendParams($plugin->params->getParams());
    }
}

function magicslideshow_use_effect_on(&$tool) {
    return !$tool->params->checkValue('use-effect-on-product-page','No') ||
           !$tool->params->checkValue('use-effect-on-category-page','No') ||
           !$tool->params->checkValue('use-effect-on-latest-box','No') ||
           !$tool->params->checkValue('use-effect-on-featured-box','No') ||
           !$tool->params->checkValue('use-effect-on-special-box','No') ||
           !$tool->params->checkValue('use-effect-on-bestsellers-box','No');
}


function magicslideshow_category_like_replace ($products, $type, $content) {
    
    $plugin = $GLOBALS["magictoolbox"]["magicslideshow"];
    if ($type != $plugin->params->getProfile()) $type = $plugin->params->getProfile(); //fix for boxes
    
    foreach ($products as $pid => $product) {
	if ($pid != $product['product_id']) $pid = $product['product_id']; //fix for boxes
	$id = $type.'_'.$pid;
	
	preg_match('/<a[^>]*?href=\"([^\"]*)\"[^<]*?<img[^>]*?id=\"'.$id.'\"[^<]*?<\/a>/is',$content,$matches);
	if (isset($matches[1])) {
            $product['link'] = $matches[1];
        }
        $readyImage = str_replace('$','\$',magicslideshow_getMainTemplate($product,$type)); //backslash the dollar char in path
        $content = preg_replace('/<a[^<]*?<img[^>]*?id=\"'.$id.'\"[^<]*?<\/a>/is',$readyImage,$content);
    }
        
    return $content;
}

function magicslideshow_cut_boxes ($products,$type,$content) {

    $placeholder = 'MAGICTOOLBOX_PLACEHOLDER';

    $plugin = $GLOBALS["magictoolbox"]["magicslideshow"];
    if ($type != $plugin->params->getProfile()) $type = $plugin->params->getProfile(); //fix for boxes
    
    //get Product links
    foreach ($products as $pid => $product) {
        if ($pid != $product['product_id']) $pid = $product['product_id']; //fix for boxes
        $id = $type.'_'.$product['product_id'];
        preg_match('/<a[^>]*?href=\"([^\"]*)\"[^<]*?<img[^>]*?id=\"'.$id.'\"[^<]*?<\/a>/is',$content,$matches);
        $link = $matches[1];
        $GLOBALS['magictoolbox']['plinks'][$pid] = $link;
    }
    
    $content = preg_replace('/(^.*?<div[^>]*>)(.*)(<\/div>.*)/is','$1'.$placeholder.'$3',$content); //cut all content inside first div
           
    return $content;
}




?>
