<?php
class ModelExtensionModuleSeoUrlGenerator extends Model
{
    public function generateCategories($template, $suffix, $overwrite, $transliterate)
    {
        $languages = $this->getLanguagesArray();
        foreach ($languages as $lang){
            $categories = $this->getCategories($overwrite,$lang['language_id']);
            $slugs = $this->getExistingSlugs();
            foreach ($categories as $category) {
                $tags = array('[category_name]' => $category['name'], '[category_id]' => $category['category_id'] );
				if ($suffix === $lang['code']) {$suffix2  =  '';} else {$suffix2  = '-' . $lang['language_id'];}
                $slug = $uniqueSlug = ($transliterate ? $this->makeSlugs(strtr($template, $tags), 0, true) : strtr($template, $tags)) . $suffix2;
                $index = $category['category_id'];
                while (in_array($uniqueSlug, $slugs)) {
                    $uniqueSlug = $slug . '-' . $index;
                }
                $slugs[] = $uniqueSlug;

                $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'category_id=" . (int)$category['category_id'] . "' AND language_id = '". $lang['language_id']."'");
               
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id='0', language_id='".$lang['language_id']."', query = 'category_id=" . (int)$category['category_id'] . "', keyword = '" . $this->db->escape($uniqueSlug) . "'");
            }
        }
    }

    public function generateProducts($template, $suffix, $overwrite, $transliterate)
    {
        $languages = $this->getLanguagesArray();
        foreach ($languages as $lang){
            $products = $this->getProducts($overwrite,$lang['language_id']);
            $slugs = $this->getExistingSlugs();
            foreach ($products as $product) {
                $tags = array('[product_name]' => $product['name'],
                    '[model_name]' => $product['model'],
                    '[product_id]' => $product['product_id']
                );
				
  	            if ($suffix === $lang['code']) {$suffix2  =  '';} else {$suffix2  = '-' . $lang['language_id'];}
				
                $slug = $uniqueSlug = ($transliterate ? $this->makeSlugs(strtr($template, $tags), 0, true) : strtr($template, $tags)) . $suffix2;
                $index = $product['product_id'];
                while (in_array($uniqueSlug, $slugs)) {
                    $uniqueSlug = $slug . '-' . $index;
                }
                $slugs[] = $uniqueSlug;

                $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product['product_id'] . "' AND language_id = '". $lang['language_id']."'");
               
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id='0', language_id='".$lang['language_id']."', query = 'product_id=" . (int)$product['product_id'] . "', keyword = '" . $this->db->escape($uniqueSlug) . "'");
            }
        }
    }

    public function generateManufacturers($template, $suffix, $overwrite, $transliterate)
    {
	$languages = $this->getLanguagesArray();
        foreach ($languages as $lang){
        $manufacturers = $this->getManufacturers($overwrite);
        $slugs = $this->getExistingSlugs();
        foreach ($manufacturers as $manufacturer) {
            $tags = array('[manufacturer_name]' => $manufacturer['name'], '[manufacturer_id]' => $manufacturer['manufacturer_id'] );
			if ($suffix === $lang['code']) {$suffix2  =  '';} else {$suffix2  = '-' . $lang['language_id'];}
            $slug = $uniqueSlug = ($transliterate ? $this->makeSlugs(strtr($template, $tags), 0, true) : strtr($template, $tags)) . $suffix2;
            $index = $manufacturer['manufacturer_id'];
            while (in_array($uniqueSlug, $slugs)) {
                $uniqueSlug = $slug . '-' . $index;
            }
            $slugs[] = $uniqueSlug;
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "' AND language_id = '". $lang['language_id']."'");
            
            $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id='0', language_id='".$lang['language_id']."', query = 'manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "', keyword = '" . $this->db->escape($uniqueSlug) . "'");
        }
		}
    }



    private function getCategories($overwrite,$language_id = 1)
    {
        $query = $this->db->query("SELECT c.category_id, cd.name, c.parent_id, cd.language_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "seo_url a ON (CONCAT('category_id=', c.category_id) = a.query) AND cd.language_id = a.language_id WHERE cd.language_id = " . (int)$language_id . ($overwrite ? '' : ' AND a.query IS NULL') . " ORDER BY c.sort_order, cd.name ASC");
        return $query->rows;
    }


    private function getProducts($overwrite,$language_id = 1)
    {
        $query = $this->db->query("SELECT p.product_id, pd.name, p.model, m.name as manufacturer_name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "seo_url a ON (CONCAT('product_id=', p.product_id) = a.query) AND pd.language_id = a.language_id WHERE pd.language_id = " . (int)$language_id . ($overwrite ? '' : ' AND a.query IS NULL') . " ORDER BY pd.name ASC");
        return $query->rows;
    }

    private function getManufacturers($overwrite)
    {
        $query = $this->db->query("SELECT m.manufacturer_id, m.name FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "seo_url a ON (CONCAT('manufacturer_id=', m.manufacturer_id) = a.query) AND a.language_id = ". (int)$this->config->get('config_language_id') . ($overwrite ? '' : ' WHERE a.query IS NULL') . " ORDER BY m.name ASC");
        return $query->rows;
    }



    public function getLanguages()
    {
        $query = $this->db->query("SELECT substring(code,1,2) as code, name FROM " . DB_PREFIX . "language");
        return $query->rows;
    }

    public function getExistingSlugs()
    {
        $slugs = array();
        $query = $this->db->query("SELECT query, keyword FROM " . DB_PREFIX . "seo_url");
        foreach ($query->rows as $row) {
            $slugs[$row['query']] = $row['keyword'];
        }
        return $slugs;
    }

    // Taken from http://code.google.com/p/php-slugs/
    private function my_str_split($string)
    {
        $slen = strlen($string);
        for ($i = 0; $i < $slen; $i++) {
            $sArray[$i] = $string{$i};
        }
        return $sArray;
    }


    /**
     * Transliterates UTF-8 encoded text to US-ASCII.
     *
     * Based on Mediawiki's UtfNormal::quickIsNFCVerify().
     *
     * @param $string
     *   UTF-8 encoded text input.
     * @param $unknown
     *   Replacement string for characters that do not have a suitable ASCII
     *   equivalent.
     * @param $source_langcode
     *   Optional ISO 639 language code that denotes the language of the input and
     *   is used to apply language-specific variations. If the source language is
     *   not known at the time of transliteration, it is recommended to set this
     *   argument to the site default language to produce consistent results.
     *   Otherwise the current display language will be used.
     * @return
     *   Transliterated text.
     */
    function _transliteration_process($string, $unknown = '?')
    {
        // ASCII is always valid NFC! If we're only ever given plain ASCII, we can
        // avoid the overhead of initializing the decomposition tables by skipping
        // out early.
        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        static $tailBytes;

        if (!isset($tailBytes)) {
            // Each UTF-8 head byte is followed by a certain number of tail bytes.
            $tailBytes = array();
            for ($n = 0; $n < 256; $n++) {
                if ($n < 0xc0) {
                    $remaining = 0;
                } elseif ($n < 0xe0) {
                    $remaining = 1;
                } elseif ($n < 0xf0) {
                    $remaining = 2;
                } elseif ($n < 0xf8) {
                    $remaining = 3;
                } elseif ($n < 0xfc) {
                    $remaining = 4;
                } elseif ($n < 0xfe) {
                    $remaining = 5;
                } else {
                    $remaining = 0;
                }
                $tailBytes[chr($n)] = $remaining;
            }
        }

        // Chop the text into pure-ASCII and non-ASCII areas; large ASCII parts can
        // be handled much more quickly. Don't chop up Unicode areas for punctuation,
        // though, that wastes energy.
        preg_match_all('/[\x00-\x7f]+|[\x80-\xff][\x00-\x40\x5b-\x5f\x7b-\xff]*/', $string, $matches);

        $result = '';
        foreach ($matches[0] as $str) {
            if ($str[0] < "\x80") {
                // ASCII chunk: guaranteed to be valid UTF-8 and in normal form C, so
                // skip over it.
                $result .= $str;
                continue;
            }

            // We'll have to examine the chunk byte by byte to ensure that it consists
            // of valid UTF-8 sequences, and to see if any of them might not be
            // normalized.
            //
            // Since PHP is not the fastest language on earth, some of this code is a
            // little ugly with inner loop optimizations.

            $head = '';
            $chunk = strlen($str);
            // Counting down is faster. I'm *so* sorry.
            $len = $chunk + 1;

            for ($i = -1; --$len;) {
                $c = $str[++$i];
                if ($remaining = $tailBytes[$c]) {
                    // UTF-8 head byte!
                    $sequence = $head = $c;
                    do {
                        // Look for the defined number of tail bytes...
                        if (--$len && ($c = $str[++$i]) >= "\x80" && $c < "\xc0") {
                            // Legal tail bytes are nice.
                            $sequence .= $c;
                        } else {
                            if ($len == 0) {
                                // Premature end of string! Drop a replacement character into
                                // output to represent the invalid UTF-8 sequence.
                                $result .= $unknown;
                                break 2;
                            } else {
                                // Illegal tail byte; abandon the sequence.
                                $result .= $unknown;
                                // Back up and reprocess this byte; it may itself be a legal
                                // ASCII or UTF-8 sequence head.
                                --$i;
                                ++$len;
                                continue 2;
                            }
                        }
                    } while (--$remaining);

                    $n = ord($head);
                    if ($n <= 0xdf) {
                        $ord = ($n - 192) * 64 + (ord($sequence[1]) - 128);
                    } elseif ($n <= 0xef) {
                        $ord = ($n - 224) * 4096 + (ord($sequence[1]) - 128) * 64 + (ord($sequence[2]) - 128);
                    } elseif ($n <= 0xf7) {
                        $ord = ($n - 240) * 262144 + (ord($sequence[1]) - 128) * 4096 + (ord($sequence[2]) - 128) * 64 + (ord($sequence[3]) - 128);
                    } elseif ($n <= 0xfb) {
                        $ord = ($n - 248) * 16777216 + (ord($sequence[1]) - 128) * 262144 + (ord($sequence[2]) - 128) * 4096 + (ord($sequence[3]) - 128) * 64 + (ord($sequence[4]) - 128);
                    } elseif ($n <= 0xfd) {
                        $ord = ($n - 252) * 1073741824 + (ord($sequence[1]) - 128) * 16777216 + (ord($sequence[2]) - 128) * 262144 + (ord($sequence[3]) - 128) * 4096 + (ord($sequence[4]) - 128) * 64 + (ord($sequence[5]) - 128);
                    }
                    $result .= $this->_transliteration_replace($ord, $unknown);
                    $head = '';
                } elseif ($c < "\x80") {
                    // ASCII byte.
                    $result .= $c;
                    $head = '';
                } elseif ($c < "\xc0") {
                    // Illegal tail bytes.
                    if ($head == '') {
                        $result .= $unknown;
                    }
                } else {
                    // Miscellaneous freaks.
                    $result .= $unknown;
                    $head = '';
                }
            }
        }
        return $result;
    }

    /**
     * Replaces a Unicode character using the transliteration database.
     *
     * @param $ord
     *   An ordinal Unicode character code.
     * @param $unknown
     *   Replacement string for characters that do not have a suitable ASCII
     *   equivalent.
     * @param $langcode
     *   Optional ISO 639 language code that denotes the language of the input and
     *   is used to apply language-specific variations.  Defaults to the current
     *   display language.
     * @return
     *   ASCII replacement character.
     */
    function _transliteration_replace($ord, $unknown = '?', $langcode = NULL)
    {
        static $map = array();

        $bank = $ord >> 8;

        if (!isset($map[$bank][$langcode])) {
            $file = dirname(__FILE__) . '/seo_url_generator_data/' . sprintf('x%02x', $bank) . '.php';
            if (file_exists($file)) {
                include $file;
                if ($langcode != 'en' && isset($variant[$langcode])) {
                    // Merge in language specific mappings.
                    $map[$bank][$langcode] = $variant[$langcode] + $base;
                } else {
                    $map[$bank][$langcode] = $base;
                }
            } else {
                $map[$bank][$langcode] = array();
            }
        }

        $ord = $ord & 255;

        return isset($map[$bank][$langcode][$ord]) ? $map[$bank][$langcode][$ord] : $unknown;
    }

    private function makeSlugs($string, $maxlen = 0, $noSpace = true)
    {
        global $session;
        $newStringTab = array();
        $string = strtolower($this->_transliteration_process(trim(html_entity_decode($string, ENT_QUOTES, "UTF-8")), '-'));
        if (function_exists('str_split')) {
            $stringTab = str_split($string);
        } else {
            $stringTab = $this->my_str_split($string);
        }
        $numbers = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "-");
        foreach ($stringTab as $letter) {
            if (in_array($letter, range("a", "z")) || in_array($letter, $numbers)) {
                $newStringTab[] = $letter;
            } elseif ($letter == " ") {
                if ($noSpace) {
                    $newStringTab[] = "-";
                } else {
                    $newStringTab[] = " ";
                }
            }
        }
        if (count($newStringTab)) {
            $newString = implode($newStringTab);
            if ($maxlen > 0) {
                $newString = substr($newString, 0, $maxlen);
            }
            $newString = $this->removeDuplicates('--', '-', $newString);
        } else {
            $newString = '';
        }
        return $newString;
    }

    private function removeDuplicates($sSearch, $sReplace, $sSubject)
    {
        $i = 0;
        do {
            $sSubject = str_replace($sSearch, $sReplace, $sSubject);
            $pos = strpos($sSubject, $sSearch);
            $i++;
            if ($i > 100) {
                die('removeDuplicates() loop error');
            }
        } while ($pos !== false);
        return $sSubject;
    }

    private function getLanguagesArray($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "language";

            $sort_data = array(
                'name',
                'code',
                'sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY sort_order, name";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $language_data = $this->cache->get('language');

            if (!$language_data) {
                $language_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

                foreach ($query->rows as $result) {
                    $language_data[$result['code']] = array(
                        'language_id' => $result['language_id'],
                        'name'        => $result['name'],
                        'code'        => $result['code'],
                        'locale'      => $result['locale'],
                        'image'       => $result['image'],
                        'directory'   => $result['directory'],
                        'sort_order'  => $result['sort_order'],
                        'status'      => $result['status']
                    );
                }

                $this->cache->set('admin.language', $language_data);
            }

            return $language_data;
        }
    }
}