    <?php
class ControllerExtensionFeedJRZboziCz extends Controller {
	public function index() { 
		if ($this->config->get('feed_jr_zbozi_cz_status')) { 

			$this->load->model('catalog/category');
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
            $this->load->language('product/product');

      /*
      if(!file_exists(DIR_CACHE . 'xml-feed/')) {
        $xml_feed_dir = mkdir(DIR_CACHE . 'xml-feed/', 0775);

        if($xml_feed_dir) {

          $htaccess = "";
          $htaccess = "";
          $htaccess.= "<Files *.*>\n";
          $htaccess.= "Order Allow,Deny\n";
          $htaccess.= "Allow from all\n";
          $htaccess.= "</Files>";

          //file_put_contents(DIR_CACHE . 'xml-feed/.htaccess', $htaccess);
        }
      }
      */
     
      $this->generateXML();
    }
  }

  private function generateXML()
  { 
      $this->load->model('setting/setting');
      $xml      = array();
      $currency = 'CZK';
      $products = $this->getProducts();
      $delivery_date = $this->config->get('zbozi_delivery_date');
      $zbozi_categories = $this->config->get('zbozi_category');

      $basedir = (realpath(dirname(__FILE__))) . '/../../../../' ;
      $filename = $basedir . 'zbozi.xml.tmp';
      
      $fp = fopen($filename, 'w+');
      
      fwrite($fp, "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
      fwrite($fp, "<SHOP xmlns=\"http://www.zbozi.cz/ns/offer/1.0\">\n");

      foreach ($products as $row) {
          $product_data = $this->getProduct($row['product_id']);
          $product_info = $this->model_catalog_product->getProduct($row['product_id']);
          $attributes = $this->model_catalog_product->getProductAttributes($row['product_id']);


          fwrite($fp, " <SHOPITEM>\n");
          
          $product_id = $product_data['product_id'];
          $zbozi_id = 0; //$product_data['zbozi_id'];


          if(empty($product)) {
            $product = $product_data['name'];
          }
          
          $description = $this->description($product_data['description']);
          $name          = $product_data['name'];
          $special       = $product_data['special'];
          $tax_class_id  = $product_data['tax_class_id'];
          $product_price = $product_data['price'];
          $vat           = $this->getTaxRate($tax_class_id) / 100;
          $manufacturer  = trim($product_data['manufacturer']);
          $ean           = trim($product_data['ean']);
          
          //$zbozi_id = $product_data['zbozi_id'];
          
          $image  = $this->model_tool_image->resize($product_data['image'], 500, 500);
          //$images = $this->model_catalog_product->getProductImages($product_id);
          
          if ($special) {
              $price_vat = $this->currency->format($this->tax->calculate($special, $tax_class_id), $currency, false, false);
              $price     = $this->currency->format($special, $currency, false, false);
          } else {
              $price_vat = $this->currency->format($this->tax->calculate($product_price, $tax_class_id), $currency, false, false);
              $price     = $this->currency->format($product_price, $currency, false, false);
          }
          
          $url = $this->url->link('product/product', 'product_id=' . $product_id);
          
          // ITEM_ID
          fwrite($fp, "  <ITEM_ID>$product_id</ITEM_ID>\n");

          // EAN
          if(strlen($ean) == 13) {
              fwrite($fp, "  <EAN>$ean</EAN>\n");
          }

          // PRODUCTNNAME
          fwrite($fp, "  <PRODUCTNAME><![CDATA[$name]]></PRODUCTNAME>\n");

          // DESCRIPTION
          fwrite($fp, "  <DESCRIPTION><![CDATA[$description]]></DESCRIPTION>\n");
          
          // URL
          fwrite($fp, "  <URL><![CDATA[$url]]></URL>\n");
          
          // PRICE_VAT
          fwrite($fp, "  <PRICE_VAT>$price_vat</PRICE_VAT>\n");
          
          // MANUFACTURER
          fwrite($fp, "  <MANUFACTURER><![CDATA[$manufacturer]]></MANUFACTURER>\n");
          
          /*
          $options = $this->model_catalog_product->getProductOptions($product_id);
          
          if($options) {
            foreach($options as $option) {
              if(isset($option["option_value"])) {
                foreach($option["option_value"] as $option_value) {
                  if($option_value["quantity"]) {
                    fwrite($fp, "  <PARAM>\n");
                    fwrite($fp, "    <PARAM_NAME><![CDATA[" . $option["name"] . "]]></PARAM_NAME>\n");
                    fwrite($fp, "    <VAL><![CDATA[" . $option_value["name"] . "]]></VAL>\n");
                    fwrite($fp, "  </PARAM>\n");
                  }
                }      
              }
            }
          }
          */

          // DELIVERY_DATE

          //var_dump($product_info);
          if ($product_info['location'] == 'pohoda') {
              fwrite($fp, "  <DELIVERY_DATE>0</DELIVERY_DATE>\n");
          }
          else {
              if ($product_info['quantity']) {
                  fwrite($fp, "  <DELIVERY_DATE>3</DELIVERY_DATE>\n");
                  //$output .= '<DELIVERY_DATE>1-3</DELIVERY_DATE>';
              } else {
                  if ($product_info['heureka_delivery_date'] > 0) {
                      fwrite($fp, '  <DELIVERY_DATE>' . $product_info['heureka_delivery_date'] . '</DELIVERY_DATE>\n');
                      //$output .= '<DELIVERY_DATE>' . $product['heureka_delivery_date'] . '</DELIVERY_DATE>';
                  } else {
                      fwrite($fp, "  <DELIVERY_DATE>5</DELIVERY_DATE>\n");
                      //$output .= '<DELIVERY_DATE>5</DELIVERY_DATE>';
                  }
              }
          }

          //fwrite($fp, "  <DELIVERY_DATE>" . $delivery_date[$product_data['stock_status_id']] . "</DELIVERY_DATE>\n");
          
          if ($image) {
              $image = urlencode($image);
              $image = str_replace('%3A', ':', $image);
              $image = str_replace('%2F', '/', $image);
              $image = str_replace('+' , '%20' , $image);
              fwrite($fp, "  <IMGURL><![CDATA[" . str_replace(" ", "%20", ($image)) . "]]></IMGURL>\n");
          }
          
          //*
          $categories = $this->model_catalog_product->getCategories($product_id);

          if($categories) {

            foreach ($categories as $category) {
              $path = $this->getPath($category['category_id']);
              
              if($path) {
                $string = '';
                $cats = array();
                $cats_id = array();
                
                foreach (explode('_', $path) as $path_id) {

                  $category_info = $this->model_catalog_category->getCategory($path_id);
                  
                  if ($category_info) {
                    if (!$string) {
                      $string = $category_info['name'];
                    } else {
                      $string .= ' | ' . $category_info['name'];
                    }
                  }

                  $cats_id[] = $category_info['category_id'];
                  $cats[] = $string;
                }
              }
            }

            $deepest_category_id = end($cats_id);

            $deep_count = array();

            if($cats) {
              foreach($cats as $key => $value) {
                $deep_count[$key] = substr_count($value, " | ");
              }                  
            }
            
            $deepest_level = max($deep_count);
           
            fwrite($fp, "  <CATEGORYTEXT><![CDATA[".$cats[$deepest_level]."]]></CATEGORYTEXT>\n");
            
          }
          //*/
          /*foreach ($attributes as $attrib) {
              foreach ($attrib["attribute"] as $att) {
                  fwrite($fp, "<PARAM>\n");
                  fwrite($fp, "<NAME><![CDATA[" . $att["name"] . "]]></NAME>\n");
                  fwrite($fp, "<VALUE><![CDATA[" . $att["text"] . "]]></VALUE>\n");
                  fwrite($fp, "</PARAM>\n");
              }
          }*/
          
          
          fwrite($fp, "</SHOPITEM>\n");
      }
      
      fwrite($fp, "</SHOP>");
      fclose($fp);

      //rename(DIR_CACHE . "xml-feed/zbozi.xml.tmp", DIR_CACHE . "xml-feed/zbozi.xml");

      $basedir = (realpath(dirname(__FILE__))) . '/../../../../' ;
      
      rename($basedir . 'zbozi.xml.tmp', $basedir . 'zbozi.xml');

      header ("Content-Type:text/xml");
      readfile($basedir . 'zbozi.xml');
  }

	public function getProducts($data = array()) {

			$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY p.product_id ORDER BY p.sort_order";

			//echo $sql;exit;

			$query = $this->db->query($sql);

			return $query->rows;
	}


	public function getProduct($product_id) {

			$customer_group_id = $this->config->get('config_customer_group_id');
      // p.zbozi_id,
			$query = $this->db->query("SELECT DISTINCT pd.product_id, p.model, p.stock_status_id, p.upc, p.quantity, p.tax_class_id, pd.name AS name, pd.description, p.price, p.image, p.ean, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "stock_status ss ON ( p.stock_status_id = ss.stock_status_id ) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

			if ($query->num_rows) {
				$query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
				//$query->row['description'] = $query->row['description']);

				return $query->row;
			}
			else {
				return false;
			}
	}

public function description($product_description)
{
	$product_description = html_entity_decode($product_description);
	$product_description = str_replace("<", " <", $product_description);
	$product_description = str_replace(">", "> ", $product_description);
	$product_description = str_replace(array("\r","\n"), '', $product_description);
	$product_description = strip_tags($product_description);
	$product_description = preg_replace('/[\s]+/u', ' ', $product_description);
	$product_description = mb_substr(strip_tags($product_description), 0, 508, 'UTF-8');
	$product_description = iconv('UTF-8', 'UTF-8//IGNORE', $product_description);

	return $product_description;
}
public function text($text)
{
	return $text;
}


		function getTaxRate($tax_class_id) {
	    if($tax_class_id != '') {
	      $tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1
	  LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id)

	  WHERE tr1.tax_class_id = '".(int)$tax_class_id."' ORDER BY tr1.priority");
	      if(isset($tax_query->row['rate'])) {
	        return $tax_query->row['rate'];
	      }
	      else {
	       return 0;
	      }
	    }
	    else {
	      return 0;
	    }
	  }

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}

}
?>