<?php
class ocextFeedGeneratorGoogle {
    private $data = array();
    private $path_oc_version;
    private $language;
    private $db;
    private $settings = array(
        'edition'=>array(
            'version_host'=>'manyfeed-google-merchant.ocext',
            'extension'=>'manyfeed_be',
            'version'=>'5.0.0.0'
        ),
        'functional'=>array(
            
        ),
    );

    public function __construct($registry,$path_oc_version,$language,$load,$db) {
        $this->registry = $registry;
        $this->language = $language;
        $this->db = $db;
        $this->load = $load;
        $this->path_oc_version = $path_oc_version;
        $this->setSetings();
    }
    
    public function setSetings() {
        foreach ($this->settings as $key => $value) {
            $this->data[$key] = $value;
        }
    }
    
    public function get($key) {
            return (isset($this->data[$key]) ? $this->data[$key] : null);
    }
    
    public function getData() {
            return $this->data;
    }

    public function set($key, $value) {
            $this->data[$key] = $value;
    }
    
    public function getSqlWhereOperators() {
        $operators = array('&lt;'=>'&lt;','≤'=>'≤','='=>'=','≥'=>'≥','&gt;'=>'&gt;','≠'=>'≠','±'=>'±','like_left'=>'Contain left','like_right'=>'Contain right','like'=>'Contain','not_like_left'=>'Does not contain left' ,'not_like_right'=>'Does not contain right','not_like'=>'Does not contain');
        return $operators;
    }
    public function getStringWhereOperators() {
        $operators = array('&lt;'=>'&lt;','≤'=>'≤','='=>'=','≥'=>'≥','&gt;'=>'&gt;','≠'=>'≠','±'=>'±','like'=>'contains','not_like'=>'Does not contain');
        return $operators;
    }
    
    public function getSqlWhereLogic() {
        $operators = array('OR'=>'OR','AND'=>'AND');
        return $operators;
    }
    
    public function getLimitProducts() {
        $limit_products = array(1=>1000,2=>2000,5=>5000,10=>10000,20=>20000,50=>50000,100=>100000);
        return $limit_products;
    }
    
    public function getCurrencies() {
        $currency_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

        foreach ($query->rows as $result) {
                $currency_data[$result['code']] = array(
                        'currency_id'   => $result['currency_id'],
                        'title'         => $result['title'],
                        'code'          => $result['code'],
                        'symbol_left'   => $result['symbol_left'],
                        'symbol_right'  => $result['symbol_right'],
                        'decimal_place' => $result['decimal_place'],
                        'value'         => $result['value'],
                        'status'        => $result['status'],
                        'date_modified' => $result['date_modified']
                );
        }
        $this->load->model('localisation/currency');
        
        return $currency_data;
    }
    
    public function getRulePictures() {
        $rule_pictures = array('by_wh_side','by_h_side','by_w_side','no_cache'); 
        return $rule_pictures;
    }
    
    public function getPartsSelect($basic_sql,$count_sql,$limit,$parts_select) {
        
        if($limit){
            
            $query = $this->db->query($count_sql);
            
                if($query->num_rows > ($limit*1000)){

                    $count_parts = ceil($query->num_rows/($limit*1000));

                    $parts_select = array();

                    for($cpi=0;$cpi<$count_parts;$cpi++){

                        $parts_select[] = $basic_sql." LIMIT  ".($cpi*($limit*1000)).", ".($limit*1000) ;

                    }

            }
            
        }
        
        return $parts_select;
    }

    public function getSettingVersionSettings(){
        return $this->settings;
    }
    
    public function getAdvancedSettings($param,$setting_name){
        
        $methods = get_class_methods($this);
        
        $result = '';
        
        if(in_array('get_'.$setting_name.'_advanced_settings_veiw', $methods)){
            
            $result = $this->{'get_'.$setting_name.'_advanced_settings_veiw'}($param);
            
        }
        
        return $result;
        
    }
    
    public function getProductColumns() {
        
        $result = array();
        
        $columns = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . 'product` ' );
        
        foreach ($columns->rows as $column) {
            
            $result[$column['Field']] = $column['Field'];
            
        }
        
        return $result;
        
    }
    
    public function getProductOptionValueColumns() {
        
        $result = array();
        
        $columns = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . 'product_option_value` ' );
        
        foreach ($columns->rows as $column) {
            
            $result[$column['Field']] = $column['Field'];
            
        }
        
        return $result;
        
    }
    
    public function resizeImage($file,$w,$h,$d,$HTTP_SERVER) {
	
		if( is_file(DIR_IMAGE.$file) ){
			
			$extension = pathinfo(DIR_IMAGE.$file, PATHINFO_EXTENSION);
			$old_image = $file;
			$image_new = 'cache/' . utf8_substr($file, 0, utf8_strrpos($file, '.')) . '-' . (int)$w . 'x' . (int)$h . '.' . $extension;

			if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $image_new))) {
			
                                $path = '';

				$directories = explode('/', dirname(str_replace('../', '', $image_new)));

				foreach ($directories as $directory) {
					$path = $path . '/' . $directory;

					if (!is_dir(DIR_IMAGE . $path)) {
						@mkdir(DIR_IMAGE . $path, 0777);
					}
				}
                            
				$image = new Image(DIR_IMAGE.$file);
				$imagesize = getimagesize(DIR_IMAGE.$file);
				$lw = $imagesize[0];
				$lh = $imagesize[1];
				if($d=='w'){
					$ws = $w/$lw;
					$h = $ws*$lh;
				}elseif($d=='h'){
					$hs = $h/$lh;
					$w = $hs*$lw;
				}
				$image->resize($w, $h);
				$image->save(DIR_IMAGE . $image_new);
				$image_new = str_replace(' ', '%20', $HTTP_SERVER.'image/'.$image_new); 
				
                        }else{
                            
                            $image_new = str_replace(' ', '%20', $HTTP_SERVER.'image/'.$image_new); 
                            
                        }
			
		}else{
			
			$image_new = '';
			
		}
		
        return $image_new;
    }
    
    public function getStores() {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

            $store_data = $query->rows;

            return $store_data;
    }
    
    
    public function manyfeed_view($template_file,$data) {
        
        $file = DIR_SYSTEM.'library/vendor/ocext/manyfeed_view/' . $template_file;
        
        $output = '';

        if (is_file($file)) {
            
                extract($data);

                ob_start();

                require($file);

                $output = ob_get_clean();
                
        }
        
        return $output;
        
    }
    
}