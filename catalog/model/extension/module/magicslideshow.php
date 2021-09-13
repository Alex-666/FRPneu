<?php
class ModelExtensionModulemagicslideshow extends Model 
{
	public function getImages($module_id) 
	{
                $query = $this->db->query("SELECT setting FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id ."'");
		
		$result = $query->row;
		
		$result = json_decode($result['setting']);
		
		return $result->slideshow_image;
	}
}
