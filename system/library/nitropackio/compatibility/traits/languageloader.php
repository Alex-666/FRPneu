<?php

namespace nitropackio\compatibility\traits;

trait LanguageLoader {
    public function getLanguages() {
        $result = array();

        $languages = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status=1")->rows;

        foreach ($languages as $language) {
            $result[$language['language_id']] = $language;
        }

        return $result;
    }
}
