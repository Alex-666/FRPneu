<?php
class ModelExtensionModuleFastcommentstatus extends Model {
    public function add($data) {
        $sql = "INSERT INTO " .DB_PREFIX . "fast_comment SET name = '" . $this->db->escape($data['template_name']) . "'";
        $this->db->query($sql);
        $fast_comment_id = $this->db->getLastId();
        foreach ($data['template_comment'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "fast_comment_description SET fast_comment_id = '" . (int)$fast_comment_id . "', language_id = '" . (int)$language_id . "',
            template_comment = '" . $this->db->escape($value) . "'");
        }
    }

    public function edit($comment_id, $data) {
        $sql = "UPDATE " .DB_PREFIX . "fast_comment SET name = '" . $this->db->escape($data['template_name']) . "' WHERE fast_comment_id = " . (int)$comment_id ;
        $this->db->query($sql);
        $sql = "DELETE FROM `" .DB_PREFIX . "fast_comment_description` WHERE  fast_comment_id = '" . (int)$comment_id . "'";
        $this->db->query($sql);

        foreach ($data['template_comment'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "fast_comment_description SET fast_comment_id = '" . (int)$comment_id . "', language_id = '" . (int)$language_id . "',
            template_comment = '" . $this->db->escape($value) . "'");
        }
    }

    public function deleteComment($comment_id) {
        $sql = "DELETE FROM `" .DB_PREFIX . "fast_comment_description` WHERE  fast_comment_id = '" . (int)$comment_id . "'";
        $this->db->query($sql);
        $sql = "DELETE FROM `" .DB_PREFIX . "fast_comment` WHERE  fast_comment_id = '" . (int)$comment_id . "'";
        $this->db->query($sql);
    }
    public function getComments($data = array()) {
        $sql = "SELECT * FROM `" .DB_PREFIX . "fast_comment` ORDER BY name";
        $result = $this->db->query($sql);
        return($result->rows);

    }

    public function getDescriptions($comment_id) {
        $sql = "SELECT *
        FROM `" .DB_PREFIX . "fast_comment_description`
            WHERE fast_comment_id = '" . (int)$comment_id . "'";

        $query = $this->db->query($sql);
        $template_description_data = array();
        foreach ($query->rows as $result) {
            $template_description_data[$result['language_id']] = array(
                'template_comment'      => $result['template_comment'],
            );
        }

        return $template_description_data;

    }

    public function getDescription($comment_id) {
        $sql = "SELECT *
        FROM `" .DB_PREFIX . "fast_comment_description`
            WHERE fast_comment_id = '" . (int)$comment_id . "'
            AND language_id = '" . $this->config->get('config_language_id') . "'
            ";

        $query = $this->db->query($sql);

        return $query->row;

    }

    public function getComment($comment_id) {
        $sql = "SELECT * FROM `" .DB_PREFIX . "fast_comment`
            WHERE fast_comment_id = '" . (int)$comment_id . "'";
        $result = $this->db->query($sql);
        return($result->row);

    }

    public function getTemplate($comment_id, $language_id) {
        $sql = "SELECT * FROM `" .DB_PREFIX . "fast_comment_description
            WHERE fast_comment_id = '" . (int)$comment_id . "' AND language_id = '" . (int)$language_id . "'";
        $result = $this->db->query($sql);
        return($result->row);

    }

    public function getTotalComments($data=array()) {
        $sql = "SELECT COUNT(*) as total FROM `" .DB_PREFIX . "fast_comment`";
        $result = $this->db->query($sql);
        return($result->row['total']);
    }

}
