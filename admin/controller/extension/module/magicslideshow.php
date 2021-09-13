<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');


global $modulesPath;
global $marketPath;
global $breadcrumbsPath;

if (version_compare(VERSION,'2.3','>=')) { //newer than 2.2.x
    $modulesPath = 'extension/module';
    $breadcrumbsPath = 'extension';
    $englishPath = 'en-gb';
    if (version_compare(VERSION,'3','>=')) {
        $marketPath = 'marketplace';
    } else {
        $marketPath = 'extension';
    }
} else {
    $breadcrumbsPath = $modulesPath = 'module';
    $marketPath = 'extension';
    $englishPath = 'english';
}

require_once (DIR_APPLICATION . 'controller/'.$modulesPath.'/magicslideshow-opencart-module/module.php');

$tool = & magicslideshow_load_core_class();



class ControllerModuleMagicSlideshow extends Controller {
    private $error = array();
    private $params = array();
    
    public $style = '<style type="text/css">
          .fa-check:before { font-weight:bold; color:green; font-size:16px; } 
          .fa-close { font-weight:bold; color:red; font-size:16px; }
          .fixed .buttons {
            margin-right: 25px !important;
            right: 0% !important;
        }
        .fixed{
              border-radius: 0 0 0 0 !important;
              left: 0;
              position: fixed;
              top: -20px;
              width: 100%;
              background: white;
              z-index: 9999999;
              padding-top: 10px;
              border-bottom: 1px solid lightgray;
        }
      </style>';

    public $script = '<script type="text/javascript">
    $(document).ready(function() {
        var headingTop = $(\'.page-header\').position().top;
        console.log(headingTop);
        $(window).scroll(function() {
            if(headingTop >= $(window).scrollTop()) {
                if($(\'.page-header\').hasClass(\'fixed\')) {
                    $(\'.page-header\').removeClass(\'fixed\');
                }
            } else { 
                if(!$(\'.page-header\').hasClass(\'fixed\')) {
                    $(\'.page-header\').addClass(\'fixed\');
                }
            }
        });
    });
    </script>';

    public $blocks = array(
		'default' => 'General',
		'bestseller' => 'Bestsellers block',
		'special' => 'Specials block',
		'featured' => 'Featured block',
		'latest' => 'Latest block',
		'module' => 'Slideshow boxes',
	);
    public $map = array(
		'default' => array(
			'Common settings' => array(
				'width',
				'height',
				'orientation',
				'arrows',
				'loop',
				'effect',
				'effect-speed',
				'effect-easing',
			),
			'Autoplay' => array(
				'autoplay',
				'slide-duration',
				'shuffle',
				'kenburns',
				'pause',
			),
			'Selectors' => array(
				'selectors-style',
				'selectors',
				'selectors-eye',
				'bullets-preview',
				'selectors-fill',
			),
			'Caption' => array(
				'caption',
			),
			'Other settings' => array(
				'fullscreen',
				'preload',
				'keyboard',
				'show-loader',
			),
			'Miscellaneous' => array(
				'z-index',
				'imagemagick',
				'image-quality',
			),
			'Watermark' => array(
				'watermark',
				'watermark-max-width',
				'watermark-max-height',
				'watermark-opacity',
				'watermark-position',
				'watermark-offset-x',
				'watermark-offset-y',
			),
		),
		'bestseller' => array(
			'General' => array(
				'page-status',
			),
			'Positioning and Geometry' => array(
				'square-images',
			),
			'Common settings' => array(
				'width',
				'height',
				'orientation',
				'arrows',
				'loop',
				'effect',
				'effect-speed',
				'effect-easing',
			),
			'Autoplay' => array(
				'autoplay',
				'slide-duration',
				'shuffle',
				'kenburns',
				'pause',
			),
			'Selectors' => array(
				'selectors-style',
				'selectors',
				'selectors-eye',
				'bullets-preview',
				'selectors-fill',
			),
			'Caption' => array(
				'caption',
			),
			'Other settings' => array(
				'fullscreen',
				'preload',
				'keyboard',
				'show-loader',
			),
			'Miscellaneous' => array(
				'z-index',
			),
		),
		'special' => array(
			'General' => array(
				'page-status',
			),
			'Positioning and Geometry' => array(
				'square-images',
			),
			'Common settings' => array(
				'width',
				'height',
				'orientation',
				'arrows',
				'loop',
				'effect',
				'effect-speed',
				'effect-easing',
			),
			'Autoplay' => array(
				'autoplay',
				'slide-duration',
				'shuffle',
				'kenburns',
				'pause',
			),
			'Selectors' => array(
				'selectors-style',
				'selectors',
				'selectors-eye',
				'bullets-preview',
				'selectors-fill',
			),
			'Caption' => array(
				'caption',
			),
			'Other settings' => array(
				'fullscreen',
				'preload',
				'keyboard',
				'show-loader',
			),
			'Miscellaneous' => array(
				'z-index',
			),
		),
		'featured' => array(
			'General' => array(
				'page-status',
			),
			'Positioning and Geometry' => array(
				'square-images',
			),
			'Common settings' => array(
				'width',
				'height',
				'orientation',
				'arrows',
				'loop',
				'effect',
				'effect-speed',
				'effect-easing',
			),
			'Autoplay' => array(
				'autoplay',
				'slide-duration',
				'shuffle',
				'kenburns',
				'pause',
			),
			'Selectors' => array(
				'selectors-style',
				'selectors',
				'selectors-eye',
				'bullets-preview',
				'selectors-fill',
			),
			'Caption' => array(
				'caption',
			),
			'Other settings' => array(
				'fullscreen',
				'preload',
				'keyboard',
				'show-loader',
			),
			'Miscellaneous' => array(
				'z-index',
			),
		),
		'latest' => array(
			'General' => array(
				'page-status',
			),
			'Positioning and Geometry' => array(
				'square-images',
			),
			'Common settings' => array(
				'width',
				'height',
				'orientation',
				'arrows',
				'loop',
				'effect',
				'effect-speed',
				'effect-easing',
			),
			'Autoplay' => array(
				'autoplay',
				'slide-duration',
				'shuffle',
				'kenburns',
				'pause',
			),
			'Selectors' => array(
				'selectors-style',
				'selectors',
				'selectors-eye',
				'bullets-preview',
				'selectors-fill',
			),
			'Caption' => array(
				'caption',
			),
			'Other settings' => array(
				'fullscreen',
				'preload',
				'keyboard',
				'show-loader',
			),
			'Miscellaneous' => array(
				'z-index',
			),
		),
	);
    
    
    public function install () { 
    

        //create database
        
        $query = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'product_video` (
            `video_id` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `url` varchar(255) NOT NULL,
            `code` varchar(255) NOT NULL,
            `thumb` varchar(255) NOT NULL,
            `type` enum("youtube","vimeo") NOT NULL,
            PRIMARY KEY (`video_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;';
        
        $this->db->query($query);

            
    }
    
    
    public function index () {
    
            global $modulesPath;
            global $breadcrumbsPath;
            global $marketPath;
            
            $this->install();
            
            $tool = $GLOBALS["magictoolbox"]["magicslideshow"];
            $shop_dir = str_replace('system/', '', DIR_SYSTEM);
            $image_dir = str_replace($shop_dir, '', DIR_IMAGE);
            $pathToCache = '/'.$image_dir.'magictoolbox_cache';

            $this->language->load($modulesPath.'/magicslideshow'); // load lang. file
            $this->load->model('setting/setting');

            if (method_exists($this->document, 'setTitle')) {
                $this->document->setTitle($this->language->get('title'));
            } else {
                $this->document->title = $this->language->get('title'); //load title
            }

            $this->load->model('setting/setting');

            if (isset($this->session->data['token'])) {
                $tokenName = 'token';
                $token = $this->session->data['token'];
                $tokenUrl = 'token='.$this->session->data['token'];
            } 
            if (isset($this->session->data['user_token'])) { // OpenCart 3 and above   
                $tokenName = 'user_token';
                $token = $this->session->data['user_token']; 
                $tokenUrl = 'user_token='.$this->session->data['user_token'];
            } 
            
            $extension_data['token_url'] = $tokenUrl;
            if (!isset($this->session->data['refresh_modifications'])) {
                $extension_data['refresh_modifications'] = false;
            } else {
                unset($this->session->data['refresh_modifications']);
                $extension_data['refresh_modifications'] = true;
            }

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
                if(isset($this->request->post['clear_cache']) && $this->request->post['clear_cache'] == '1') {
                    //clear cache
                    $this->params = $this->model_setting_setting->getSetting('magicslideshow');//load settings from DB
                    foreach ($tool->params->getParams() as $param => $values) {
                        if (isset($this->params[$values['id']])) {
                            $tool->params->setValue($values['id'],$this->params[$values['id']]);
                        }
                    }

                    require_once(DIR_APPLICATION . 'controller/'.$modulesPath.'/magicslideshow-opencart-module/magictoolbox.imagehelper.class.php');

                    $imagehelper = new MagicToolboxImageHelperClass($shop_dir, $pathToCache, $tool->params);
                    $usedSubCache = $imagehelper->getOptionsHash();
                    if(is_dir($shop_dir.$pathToCache))
                        $this->clearCache($shop_dir.$pathToCache, ($this->request->post['what-clear'] == 'all_items')?null:$usedSubCache);
                } else { //save params
                     if (isset($this->request->post['module_id'])) { //magicslideshow modules params
                                                
                        if (version_compare(VERSION,'3','>=')) {
                            $this->load->model('setting/module');
                        } else { //oc2
                            $this->load->model('extension/module');
                        }
                        
                        $this->session->data['success'] = $this->language->get('text_success');

                        
                        if (version_compare(VERSION,'3','>=')) {
                            if ($this->request->post['module_id'] == 'new') {
                                $this->model_setting_module->addModule('magicslideshow', $this->request->post);
                                //hack for getting last id
                                $this->request->post['module_id'] = $this->db->getLastId();
                                $this->model_setting_module->editModule($this->request->post['module_id'],  $this->request->post);
                                $this->response->redirect($this->url->link($modulesPath.'/magicslideshow', $tokenUrl.'&module_id='.$this->request->post['module_id'], true));
                            } else {
                                $this->model_setting_module->editModule($this->request->post['module_id'], $this->request->post);
                                $this->response->redirect($this->url->link($modulesPath.'/magicslideshow', $tokenUrl.'&module_id='.$this->request->post['module_id'], true));
                            }
                        } else { //oc2
                            if ($this->request->post['module_id'] == 'new') {
                                $this->model_extension_module->addModule('magicslideshow', $this->request->post);
                                //hack for getting last id
                                $this->request->post['module_id'] = $this->db->getLastId();
                                $this->model_extension_module->editModule($this->request->post['module_id'],  $this->request->post);
                                $this->response->redirect($this->url->link($modulesPath.'/magicslideshow', $tokenUrl.'&module_id='.$this->request->post['module_id'], true));
                            } else {
                                $this->model_extension_module->editModule($this->request->post['module_id'], $this->request->post);
                                $this->response->redirect($this->url->link($modulesPath.'/magicslideshow', $tokenUrl.'&module_id='.$this->request->post['module_id'], true));
                            }
                        }
                        
                        //$this->session->data['success'] = $this->language->get('text_success');
                        $this->response->redirect($this->url->link($marketPath.'/'.$breadcrumbsPath, $tokenUrl, true));
                        
                    } else { // extension params
                    
                        unset($this->request->post['clear_cache']);
                        unset($this->request->post['what-clear']);
                        
                        $finalSettings = array('magicslideshow_settings' => $this->request->post); //serialize to avoid module name check for each option
                        
                        if (!empty($finalSettings['magicslideshow_settings']['default_watermark'])) {
                            $finalSettings['magicslideshow_settings']['default_watermark'] = str_replace(HTTP_CATALOG,'',$finalSettings['magicslideshow_settings']['default_watermark']);
                            $finalSettings['magicslideshow_settings']['default_watermark'] = str_replace(str_replace('http://','https://',HTTPS_CATALOG),'',$finalSettings['magicslideshow_settings']['default_watermark']);
                            $finalSettings['magicslideshow_settings']['default_watermark'] = str_replace(preg_replace('/(https?\:\/\/)(.*)/is','$2',HTTP_CATALOG),'',$finalSettings['magicslideshow_settings']['default_watermark']);
                        }
                        
                        
                        if ($this->request->post['magicslideshow_status'] == '1' && $this->request->post['magicslideshow_status'] != $this->request->post['magicslideshow_status_was']) { //fire only on activaion
                            $this->session->data['refresh_modifications'] = true;
                        }
                        
                        $this->model_setting_setting->editSetting('magicslideshow', $finalSettings);
                        $this->model_setting_setting->editSetting('module_magicslideshow_status', ['module_magicslideshow_status' => $this->request->post['magicslideshow_status']]); // status fix
                        
                        $this->params = $this->model_setting_setting->getSetting('magicslideshow');//load settings from DB
                        
                        if (version_compare(VERSION,'2','<')) {
                                    $this->session->data['success'] = $this->language->get('text_success');
                                    $this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&' . $tokenUrl);
                        } else {
                                    $this->session->data['success'] = $this->language->get('text_success');
                            //$this->response->redirect($this->url->link($marketPath.'/'.$breadcrumbsPath, $tokenUrl, 'SSL'));
                            $this->response->redirect($this->url->link($modulesPath.'/magicslideshow', $tokenUrl, true));
                        }
                }
            }
            }

            $extension_data['heading_title'] = $this->language->get('heading_title_big');

            $extension_data['text_enabled'] = $this->language->get('text_enabled');
            $extension_data['text_disabled'] = $this->language->get('text_disabled');

            $extension_data['entry_status'] = $this->language->get('entry_status');

            $extension_data['button_save'] = $this->language->get('button_save');
            $extension_data['button_cancel'] = $this->language->get('button_cancel');
            $extension_data['button_clear'] = $this->language->get('button_clear');
            
            $extension_data['blocks'] = $this->blocks;
	    
            $this->params = $this->model_setting_setting->getSetting('magicslideshow');//load settings from DB
            
	    if (isset($this->params['magicslideshow_settings'])) {
		  $this->params = $this->params['magicslideshow_settings'];
	    } else {
		  $this->params = array();
	    }
	    
	    
	    $defaults = $tool->params->getParams();
	    
	    if (!count($this->params)) { //first time page config loaded after save
		foreach ($this->blocks as $profile_id => $profile_name) {
		    $tool->params->appendParams($defaults,$profile_id);
		}
	    } else {
		foreach ($this->blocks as $profile_id => $profile_name) {
		    foreach ($defaults as $id => $values) {
			if (isset($this->params[$profile_id.'_'.$id])) { //param profile naming
			    $tool->params->setValue($id,$this->params[$profile_id.'_'.$id],$profile_id);
			}
		    }
		}
	    }
	    
	    
	    
            foreach ($tool->params->getParams() as $param => $values) {
                if (isset($this->params[$values['id']])) {
                    $tool->params->setValue($values['id'],$this->params[$values['id']]);
                }
            }

            require_once(DIR_APPLICATION . 'controller/'.$modulesPath.'/magicslideshow-opencart-module/magictoolbox.imagehelper.class.php');

            $imagehelper = new MagicToolboxImageHelperClass($shop_dir, $pathToCache, $tool->params);
            $usedSubCache = $imagehelper->getOptionsHash();
            $cacheInfo = $this->MagicSlideshowgetCacheInfo($shop_dir.$pathToCache, $usedSubCache);
            $extension_data['path_to_cache'] = $pathToCache;
            $extension_data['total_items'] = $cacheInfo['totalCount'].' ('.$this->MagicSlideshowformat_size($cacheInfo['totalSize']).')';
            $extension_data['unused_items'] = $cacheInfo['unusedCount'].' ('.$this->MagicSlideshowformat_size($cacheInfo['unusedSize']).')';


            $profiles = $tool->params->getProfiles();
	    foreach ($profiles as $profile) {
	      $extension_data['parameters'][$profile] = $tool->params->getParams($profile); // LOAD PARAMS
	    }

            if (isset($this->error['warning'])) {
                    $extension_data['error_warning'] = $this->error['warning'];
            } else {
                    $extension_data['error_warning'] = '';
            }

            if (isset($this->error['code'])) {
                    $extension_data['error_code'] = $this->error['code'];
            } else {
                    $extension_data['error_code'] = '';
            }

            $extension_data['breadcrumbs'] = array();

            $extension_data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route=common/dashboard&' . $tokenUrl,
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
            );

            $extension_data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route='.$marketPath.'/'.$breadcrumbsPath . '&'.$tokenUrl,
            'text'      => $this->language->get('text_module'),
            'separator' => ' :: '
            );

            $extension_data['breadcrumbs'][] = array(
            'href'      => HTTPS_SERVER . 'index.php?route='.$modulesPath.'/magicslideshow' . '&'.$tokenUrl,
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
            );

            $extension_data['action'] = HTTPS_SERVER . 'index.php?route='.$modulesPath.'/magicslideshow' . '&'.$tokenUrl;

            $extension_data['cancel'] = HTTPS_SERVER . 'index.php?route='.$marketPath.'/'.$breadcrumbsPath . '&'.$tokenUrl;

            if (isset($this->request->post['magicslideshow_status'])) {
                $extension_data['magicslideshow_status'] = $this->request->post['magicslideshow_status'];
            }  else if (isset($this->request->post['magicslideshow_settings']['magicslideshow_status'])) {
                $extension_data['magicslideshow_status'] = $this->request->post['magicslideshow_settings']['magicslideshow_status']; 
            } else {
                if (isset($this->params['magicslideshow_status'])) {
                    $this->config->set('magicslideshow_status',$this->params['magicslideshow_status']);
                } 
                $extension_data['magicslideshow_status'] = $this->config->get('magicslideshow_status');
            }
            
            $toolAbr = '';
            $abr = explode(" ", strtolower("Magic Slideshow"));
            foreach ($abr as $word) $toolAbr .= $word[0];

            $extension_data['map'] = $this->map;
            $default_map = array('default' => array('Watermark' => $this->map['default']['Watermark'],
                                                    'Miscellaneous' => array('image-quality','headers-on-every-page','original-images')));
                                       
            $data['groupsHTML']['general'] = $this->MagicSlideshow_groupsHTML($extension_data['parameters'],'default', $default_map);

            foreach ($this->blocks as $block_id => $block_name) { 
                if ($block_id == 'default') continue;
                if ($block_id == 'module') {
                
                    $query = "SELECT * FROM `".DB_PREFIX."module` WHERE code = 'magicslideshow'";
                    $result = $this->db->query($query);
                    $result = $result->rows;
                    
                    foreach ($result as $row_id => $row) {
                        $settings = json_decode($row['setting']);
                        $result[$row_id]['status'] = ($settings->status ? 'Enabled' : 'Disabled');
                        
                        $result[$row_id]['edit'] = $this->url->link('extension/module/'.$row['code'] , '&' . $tokenUrl . '&module_id=' . $row['module_id'], true);
                        $result[$row_id]['delete'] = $this->url->link('extension/extension/module/delete', '&' . $tokenUrl . '&module_id=' . $row['module_id'], true);
      
                    }
                    
                    $data['new_module']  = $this->url->link('extension/module/magicslideshow' , '&' . $tokenUrl . '&module_id=new', true);
                    $data['slideshow_modules'] = $result;
                    
                    
                    continue;
                }


                $data['groupsHTML'][$block_id] = '<div class="tab-pane" id="tab-'.$block_id.'">';
                $data['groupsHTML'][$block_id] .= $this->MagicSlideshow_groupsHTML($extension_data['parameters'], $block_id, $this->map);
                $data['groupsHTML'][$block_id] .= '</div>';

            }


            if (!preg_match('/DEMO/s',@file_get_contents(DIR_CATALOG.'view/javascript/magicslideshow.js'))) {
                $data['trial'] = false; 
            } else {
                $data['trial'] = true;  
            }

            $data['trial_bage'] = '<div style="border: 1px solid #C7C16C;background: #feffd0;text-align: center; margin-bottom: 15px;">
                                <h1 style="color: black;font-size: 1.5em;text-transform: none;">Trial version</h1>
                                To remove the red "<span style="color:red;">Please upgrade..</span>" text, please <a target="_blank" href="http://magictoolbox.com/buy/magicslideshow/"><b>buy</b></a> and upload your licensed magicslideshow.js file to this folder:
                                <div style="padding:10px 0;">'.preg_replace('/^.*?([^\/]*\/?$)/is','$1',DIR_CATALOG).'view/javascript/magicslideshow.js</div></div>';

            $data['style'] = $this->style;
            $data['script'] = $this->script;




	    if (version_compare(VERSION,'2','<')) {

    		foreach ($extension_data as $key => $value) {
    		    $this->data[$key] = $value;
    		}
    		$this->template = $modulesPath.'/magicslideshow_oc15.tpl';
    		$this->children = array(
    			'common/header',
    			'common/footer'
    		);

    		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));

	    } else {

    		foreach ($extension_data as $key => $value) {
    		    $data[$key] = $value;
    		}

    		$data['header'] = $this->load->controller('common/header');
    		$data['column_left'] = $this->load->controller('common/column_left');
    		$data['footer'] = $this->load->controller('common/footer');
            
            if (isset($this->request->get['module_id'])) { //magicslideshow_modules
            
                if (version_compare(VERSION,'3','>=')) {
                    $this->load->model('setting/module');
                } else { //oc2
                    $this->load->model('extension/module');
                }
                
                $data['module_id'] = $this->request->get['module_id'];
                
                if ($data['module_id'] != 'new') {

                    if (version_compare(VERSION,'3','>=')) {
                        $module_data = $this->model_setting_module->getModule($data['module_id']);
                    } else { //oc2
                        $module_data = $this->model_extension_module->getModule($data['module_id']);
                    }

                    $data['status'] = $module_data['status'];
                    $data['name'] = $module_data['name'];
                    
                     foreach ($module_data as $pid => $param) {
                        $pid = str_replace('default_','',$pid);
                        if ($tool->params->paramExists($pid)) {
                            $tool->params->setValue($pid,$param,'default');
                        }
                    }
                    
                    $extension_data['parameters']['default'] = $tool->params->getParams('default');
                    
                }
                
                $data['settings'] = $this->MagicSlideshow_groupsHTML($extension_data['parameters'], 'default', $this->map);
                
                $this->load->model('tool/image');
                $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                
                // Images
                if (isset($this->request->post['image'])) {
                        $slideshow_images = $this->request->post['slideshow_image'];
                } elseif ($data['module_id'] != 'new' && isset($module_data['slideshow_image'])) {
                        $slideshow_images = $module_data['slideshow_image'];
                } else {
                        $slideshow_images = array();
                }

                $data['slideshow_images'] = array();

                foreach ($slideshow_images as $slideshow_image) {
                        if (is_file(DIR_IMAGE . $slideshow_image['image'])) {
                                $image = $slideshow_image['image'];
                                $thumb = $slideshow_image['image'];
                        } else {
                                $image = '';
                                $thumb = 'no_image.png';
                        }

                        $data['slideshow_images'][] = array(
                                'image'      => $image,
                                'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
                                'link' => isset($slideshow_image['link']) ? $slideshow_image['link'] : '',
                                'title' => isset($slideshow_image['title']) ? $slideshow_image['title'] : '',
                                'description' => isset($slideshow_image['description']) ? $slideshow_image['description'] : ''
                        );
                }
                
                $this->response->setOutput($this->load->view($modulesPath.'/magicslideshow_banner', $data));
                
            } else {
                $js = '<script>
                        $(\'#tab-module\').on(\'click\', \'.btn-warning\', function(e) {
                                e.preventDefault();
                                
                                if (confirm(\'Are you sure?\')) {
                                        var element = this;
                                
                                        $.ajax({
                                                url: $(element).attr(\'href\'),
                                                dataType: \'html\',
                                                beforeSend: function() {
                                                        $(element).button(\'loading\');
                                                },
                                                complete: function() {
                                                        $(element).button(\'reset\');
                                                },
                                                success: function(html) {
                                                        $(element).closest(\'tr\').remove();
                                                },
                                                error: function(xhr, ajaxOptions, thrownError) {
                                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                }
                                        });
                                }
                        });
                        </script>';
                if (version_compare(VERSION,'3','>=')) { //twig

                    $output = $this->load->view($modulesPath.'/magicslideshow', $data);

                } else { //oc2

                    $output = $this->load->view($modulesPath.'/magicslideshow_oc2', $data);

                }
                $output = str_replace('</head>',$js.'</head>',$output);

                $this->response->setOutput($output);
            }
	    }
    }

    private function validate() {
    
            global $modulesPath;
            
            if (!$this->user->hasPermission('modify', $modulesPath.'/magicslideshow')) {
                    $this->error['warning'] = $this->language->get('error_permission');
            }

            if (!$this->error) {
                    return TRUE;
            } else {
                    return FALSE;
            }
    }

    private function clearCache($path, $usedSubCache = null) {
        $files = glob($path.DIRECTORY_SEPARATOR.'*');
        if($files !== FALSE && !empty($files)) {
            foreach($files as $file) {
                if(is_dir($file)) {
                    if(!$usedSubCache || $usedSubCache != substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1)) {
                        $this->clearCache($file);
                        @rmdir($file);
                    }
                } else {
                    @unlink($file);
                }
            }
        }
        return;
    }

    function MagicSlideshowgetCacheInfo($path, $usedSubCache = null) {

        $totalSize = 0;
        $totalCount = 0;
        $usedSize = 0;
        $usedCount = 0;
        if (is_dir($path))
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                $next = $path . DIRECTORY_SEPARATOR . $file;
                if ($file != '.' && $file != '..' && !is_link($next)) {
                    if (is_dir($next)) {
                        $result = $this->MagicSlideshowgetCacheInfo($next);
                        if($file == $usedSubCache) {
                            $usedSize += $result['totalSize'];
                            $usedCount += $result['totalCount'];
                        }
                        $totalSize += $result['totalSize'];
                        $totalCount += $result['totalCount'];
                    } elseif (is_file($next)) {
                        $totalSize += filesize($next);
                        $totalCount++;
                    }
                }
            }
            closedir($handle);
        }
        return array('totalSize' => $totalSize, 'totalCount' => $totalCount, 'unusedSize' => $totalSize-$usedSize, 'unusedCount' => $totalCount-$usedCount);
    }

    function MagicSlideshowformat_size($size) {
        $units = array(' bytes', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }

    function MagicSlideshow_groupsHTML ($parameters, $profile = 'default', $map) {

        if (!isset($parameters[$profile])) return false;

        $groups = array();

        $parameters = $parameters[$profile];
                
        $imgArray = array('zoom &amp; expand', //array for the images ordering
                          'yes',
                          'zoom',
                          'expand',
                          'expanded',
                          'original',
                          'swap images only',
                          'no',
                          'left',
                          'top left', 
                          'top-left', 
                          'top',
                          'top right', 
                          'top-right', 
                          'right', 
                          'bottom right', 
                          'bottom-right', 
                          'bottom', 
                          'bottom left', 
                          'bottom-left'); 

        global $toolAbr, $magicslideshow_status;

        if ($magicslideshow_status) {
            $selected_yes = 'selected';
        } else {
            $selected_yes = '';
        }

        if (!$magicslideshow_status) {
            $selected_no = 'selected'; 
        } else {
            $selected_no = '';  
        }

        $html = '';
        
        foreach ($parameters as $name => $s) {

            if (!isset($map[$profile][$s['group']]) || !in_array($s['id'], $map[$profile][$s['group']])) continue;

            if ($profile == 'product') {
                if ($s['id'] == 'page-status' && !isset($s['value'])) {
                    $s['default'] = 'Yes';
                }
            }

            if ($profile == 'default' && $s['id'] == 'page-status') continue;
            
            if ($s['id'] == 'direction') continue;

            
            
            if (in_array($s['id'],array('thumb-max-width','thumb-max-height')) && !isset($s['value']) && in_array($profile,array('featured','bestseller','latest','special'))) {
                $s['default'] = '100';
            }
            
            if (!isset($s['group'])) {
                $s['group'] = 'Miscellaneous';
            }
            if (!isset($groups[$s['group']])) {
                $groups[$s['group']] = array();
            }

            if (strpos($s["label"],'(')) {
                $before = substr($s["label"],0,strpos($s["label"],'('));
                $after = ' '.str_replace(')','',substr($s["label"],strpos($s["label"],'(')+1));
            } else {
                $before = $s["label"];
                $after = '';
            }

            if (strpos($after,'%')) $after = ' %';
            if (strpos($after,'in pixels')) $after = ' pixels';
            if (strpos($after,'milliseconds')) $after = ' milliseconds';
            
            $description = '';
            if (isset($s["description"]) && trim($s["description"]) != '') {
                $description = $s["description"];
            }

            $html  .= '<div class="form-group">';
            
            if ($s["type"]=='num' || $s["type"]=='text') {
                $html  .= '<label class="col-sm-2 control-label" for="magicslideshowsettings'. ucwords(strtolower($name)).'">'.$before.'</label>';
            } else {
                $html  .= '<label class="col-sm-2 control-label">'.$before.'</label>';
            }

            if(($s['type'] != 'array') && isset($s['values'])) {
                $description = implode(', ',$s['values']);
            }
                            
            $html .= '<div class="col-sm-10">';
                                        
            if (!empty($after)) {
                $html .= '<div class="input-group">';
            }

            switch($s["type"]) {
                case "array":

                    $rButtons = array();

                    foreach($s["values"] as $p) {
                        if (!isset($s["value"])) $s["value"] = $s["default"];
                        $rButtons[strtolower($p)] = '<label class="radio-inline"><input type="radio" value="'.$p.'"'. ($s["value"]==$p?"checked=\"checked\"":"").' name="'.$profile.'_'.$s['id'].'" id="magicslideshowsettings'. ucwords(strtolower($name)).$p.'">';
                        $pName = ucwords($p);

                        if ($pName=='Yes') $pName = '<span class="fa fa-check"></span>';
                        if ($pName=='No') $pName = '<span class="fa fa-close"></span>';

                        $rButtons[strtolower($p)] .= ' '.$pName.'</label>';
                    }

                    foreach ($imgArray as $img){
                        if (isset($rButtons[$img])) {
                            $html .= $rButtons[$img];
                            unset($rButtons[$img]);
                        }
                    }

                    $html .= implode('',$rButtons);

                    break;
                case "num":
                case "text":
                default:
                    if (strtolower($name) == 'message') { $width = 'style="width:95%;"';} else {$width = '';}
                    if (!isset($s["value"])) $s["value"] = $s["default"];
                    $html .= '<input class="form-control" '.$width.' type="text" name="'.$profile.'_'.$s['id'].'" id="magicslideshowsettings'. ucwords(strtolower($name)).'" value="'.$s["value"].'" />';
                    break;
            }

            if (!empty($after)) {
                $html .= '<span class="input-group-addon">'.$after.'</span>';
            }

            if (!empty($after)) {
                $html .= '</div>';
            }

            if (!empty($description)) $html .= '<p class="help-block">'.$description.'</p>';
            
            $html .= '</div>';
            $html .= '</div>';
            $groups[$s['group']][] = $html;
            $html = '';
        }

        $finalHTML = '';
        foreach ($groups as $name => $group) {
            $i = 0;
            $group[count($group)-1] = str_replace('<tr','<tr class="last"',$group[count($group)-1]); //set "last" class

            $finalHTML .= ' <div class="panel panel-default">
                            <div class="panel-heading">
                              <h3 class="panel-title"><i class="fa fa-pencil"></i>'.$name.'</h3>
                            </div>
                            <div class="panel-body '.$toolAbr.'params">';

            foreach ($group as $g) {
                if (++$i%2==0) { //set stripes
                    if (strpos($g,'class="last"')) {
                        $g = str_replace('class="last"','class="back last"',$g);
                    } else {
                        $g = str_replace('<tr','<tr class="back"',$g);
                    }
                }
                $finalHTML .= $g;
            }
            $finalHTML .= ' </div></div>';
        }
        return $finalHTML;
      }
      
      
      
}
if (version_compare(VERSION,'2.3','>=')) { //newer than 2.2.x
    class_alias('ControllerModuleMagicSlideshow','ControllerExtensionModuleMagicSlideshow'); //hack the class system
}



?>
