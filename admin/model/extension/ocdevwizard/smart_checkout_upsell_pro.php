<?php
##==================================================================##
## @author    : OCdevWizard                                         ##
## @contact   : ocdevwizard@gmail.com                               ##
## @support   : http://help.ocdevwizard.com                         ##
## @license   : http://license.ocdevwizard.com/Licensing_Policy.pdf ##
## @copyright : (c) OCdevWizard. Smart Checkout Upsell Pro, 2018    ##
##==================================================================##
libxml_use_internal_errors(true);
class ModelExtensionOcdevwizardSmartCheckoutUpsellPro extends Model {

	private $_name    = 'smart_checkout_upsell_pro';
	private $_code 		= 'smchup';
	private $_version = '1.0.0';

  public function createDBTables() {
    $sql1  = "CREATE TABLE IF NOT EXISTS ".DB_PREFIX."ocdevwizard_setting ( ";
		$sql1 .= "`setting_id` int(11) NOT NULL AUTO_INCREMENT,";
		$sql1 .= "`store_id` int(11) NOT NULL DEFAULT '0',";
		$sql1 .= "`code` varchar(32) NOT NULL,";
		$sql1 .= "`key` varchar(64) NOT NULL,";
		$sql1 .= "`value` text NOT NULL,";
		$sql1 .= "`serialized` tinyint(1) NOT NULL,";
		$sql1 .= "PRIMARY KEY (`setting_id`)";
		$sql1 .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

		$this->db->query($sql1);
  }

  public function getOCdevCatalog() {
		$catalog = array();

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://ocdevwizard.com/products/share/share.xml');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, 0);

		$response_data = curl_exec($curl);
		$httpcode_data = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$results = simplexml_load_string($response_data);

		if ($httpcode_data == 200 && !empty($response_data) && $results !== false) {
			foreach ($results->product as $product) {
			  $language = substr($this->request->server['HTTP_ACCEPT_LANGUAGE'], 0, 2);

				$catalog[] = array(
					'extension_id'     => (int)$product->extension_id,
					'title'            => (string)$product->title,
					'img'              => (string)$product->img,
					'price'            => (string)$product->price,
					'url'              => (string)str_replace("&amp;", "&", $product->url),
					'date_added'       => (string)$product->date_added,
					'opencart_version' => (string)$product->opencart_version,
					'latest_version'   => (string)$product->latest_version,
					'version_compare'  => version_compare($this->_version, (string)$product->latest_version),
					'features'         => (in_array($language, array('ru','uk')) && $product->{'features_'.$language}) ? (string)$product->{'features_'.$language} : (string)$product->features
				);
			}

			$sort_order = array();

      foreach ($catalog as $key => $value) {
        $sort_order[$key] = strtotime($value['date_added']);
      }

      array_multisort($sort_order, SORT_DESC, $catalog);
		}

		return $catalog;
	}

	public function getOCdevSupportInfo() {
		$catalog = array();

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://ocdevwizard.com/support/support.xml');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, 0);

		$response_data = curl_exec($curl);
		$httpcode_data = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		$results = simplexml_load_string($response_data);

		if ($httpcode_data == 200 && !empty($response_data) && $results !== false) {
		  $language = substr($this->request->server['HTTP_ACCEPT_LANGUAGE'], 0, 2);

			$catalog = array(
				'general'       => (in_array($language, array('ru','uk')) && $results->{'general_'.$language}) ? (string)$results->{'general_'.$language} : (string)$results->general,
				'terms'         => (in_array($language, array('ru','uk')) && $results->{'terms_'.$language}) ? (string)$results->{'terms_'.$language} : (string)$results->terms,
				'faq'           => (in_array($language, array('ru','uk')) && $results->{'faq_'.$language}) ? (string)$results->{'faq_'.$language} : (string)$results->faq,
				'license'       => (string)$results->license,
				'license_error' => (string)$results->license_error
			);
		}

		return $catalog;
	}

	public function getLanguageByCode($code) {
		return $this->db->query("SELECT language_id FROM ".DB_PREFIX."language WHERE code = '".(string)$code."'")->row['language_id'];
	}
}
