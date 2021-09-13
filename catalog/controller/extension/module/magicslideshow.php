<?php
class ControllerExtensionModulemagicslideshow extends Controller 
{
	public function index($settings) 
	{
                if (!$settings) return; 
	
                $mId = $settings['module_id'];
                $this->load->model('extension/module/magicslideshow');
                
                $images = $this->model_extension_module_magicslideshow->getImages($mId);
                $data = array();
                
                $GLOBALS['magictoolbox']['page_type'] = 'module';
                
                require_once (str_replace('catalog/','',DIR_APPLICATION).'admin/controller/extension/module/magicslideshow-opencart-module/module.php');
                                
                $shop_dir = str_replace('system/','',DIR_SYSTEM);
                $image_dir = str_replace ($shop_dir,'',DIR_IMAGE);
                
                $tool = & magicslideshow_load_core_class($this->controller);
                
                $items = array();
                
                foreach ($images as $image) {
                
                    $src = $image_dir.$image->image;
                    $img = magicslideshow_getThumb($src,'original','module-'.$mId);
                    $thumb = magicslideshow_getThumb($src,'thumb','module-'.$mId);
                    $thumb2x = magicslideshow_getThumb($src,'thumb2x','module-'.$mId);                
                    
                    if (!empty($image->link)) {
                        $link = $image->link;
                    } else {
                        $link = '';
                    }
                    
                    if (!empty($image->title)) {
                        $title = $image->title;
                    } else {
                        $title = '';
                    }
                    
                    if (!empty($image->description)) {
                        $description = $image->description;
                    } else {
                        $description = '';
                    }
                    
                    $items[] = array('img' => $thumb, 'thumb' => $thumb, 'thumb2x' => $thumb2x, 'title' => $title, 'description' => $description, 'link' => $link);
                }
                
                
                foreach ($settings as $id => $value) {
                    $pid = str_replace('default_','',$id);
                    if ($tool->params->paramExists($pid)) {
                        $tool->params->setValue($pid, $value);
                    }
                }
                
                $data = [
                    'html' => $tool->getMainTemplate($items),
                    'class' => 'MagicSlideshow-block block-id-'.$mId,
                ];
                
                $GLOBALS['magictoolbox']['plinks'] = array();
              
                return $this->load->view('extension/module/magicslideshow', $data);
		
		
		
	}
}