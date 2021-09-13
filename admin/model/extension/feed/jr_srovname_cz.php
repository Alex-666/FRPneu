<?php
class ModelExtensionFeedJRSrovnamecz extends Model {

  public function search($filter_name)
  {
    $query = $this->db->query("SELECT * FROM ".DB_PREFIX."category_srovname WHERE name LIKE '%".$filter_name."%' OR fullname = '%".$filter_name."%' LIMIT 20");

    return $query->rows;
  }

  public function getCategoryMap() {
    $sql = "SELECT c.category_id, ch.category_id as srovname_id, ch.name, ch.fullname FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_srovname_map cm ON (cm.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_srovname ch ON (cm.srovname_id = ch.category_id)";
    $sql .= ' ORDER BY c.category_id';

    $query = $this->db->query($sql);

    return $query->rows;
  }

  public function getSrovnameCategory($srovname_id) {
    $sql = "SELECT * FROM ".DB_PREFIX."category_srovname WHERE category_id = '".$this->db->escape($srovname_id)."'";
    
    $query = $this->db->query($sql);

    return $query->row;
  }

  public function getSrovnameCategoryByProduct($product_id) {
    $sql = "SELECT ch.category_id, ch.name, ch.fullname FROM ".DB_PREFIX."category_srovname ch JOIN ".DB_PREFIX."product p ON(p.srovname_id = ch.category_id) WHERE p.product_id = '".$this->db->escape($product_id)."'";

    $query = $this->db->query($sql);

    return $query->row;
  }

  public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . ' &rarr; ' . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}

}
?>