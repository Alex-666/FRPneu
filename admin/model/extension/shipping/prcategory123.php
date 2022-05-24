<?php
    class ModelExtensionShippingPrCategory123 extends Model {

        public function getZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory123_zone WHERE prcategory123_zone_id = " . (int)$zone_id)->row;
        }

        public function getZones() {

            return $this->db->query("SELECT * FROM prcategory123_zone ORDER BY country_id")->rows;
        }

        public function getCategoriesForZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory123_zone_category zc LEFT JOIN prcategory123_tariff t ON zc.tariff_id = t.prcategory123_tariff_id WHERE zc.prcategory123_zone_id = " . (int)$zone_id . " ORDER BY zc.zone_category_id ASC")->rows;
        }

        public function getGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory123_geozone WHERE prcategory123_geozone_id = " . (int)$zone_id)->row;
        }

        public function getGeoZones() {

            return $this->db->query("SELECT * FROM prcategory123_geozone")->rows;
        }

        public function getCategoriesForGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory123_geozone_category zc LEFT JOIN prcategory123_tariff t ON zc.tariff_id = t.prcategory123_tariff_id WHERE zc.prcategory123_geozone_id = " . (int)$zone_id . " ORDER BY zc.geozone_category_id ASC")->rows;
        }

        public function editPrZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE prcategory123_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `prcategory123_zone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM prcategory123_tariff WHERE prcategory123_tariff_id IN (SELECT tariff_id FROM prcategory123_zone_category WHERE prcategory123_zone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM prcategory123_zone_category WHERE prcategory123_zone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO prcategory123_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO prcategory123_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO prcategory123_zone_category SET `prcategory123_zone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function editPrGeoZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE prcategory123_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `prcategory123_geozone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM prcategory123_tariff WHERE prcategory123_tariff_id IN (SELECT tariff_id FROM prcategory123_geozone_category WHERE prcategory123_geozone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM prcategory123_geozone_category WHERE prcategory123_geozone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO prcategory123_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO prcategory123_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO prcategory123_geozone_category SET `prcategory123_geozone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function removePrZone($pr_zone_id) {

            $this->db->query("DELETE FROM prcategory123_zone WHERE `prcategory123_zone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM prcategory123_tariff WHERE prcategory123_tariff_id IN (SELECT tariff_id FROM prcategory123_zone_category WHERE prcategory123_zone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM prcategory123_zone_category WHERE prcategory123_zone_id = " . (int)$pr_zone_id);
        }

        public function removePrGeoZone($pr_zone_id) {

            $this->db->query("DELETE FROM prcategory123_geozone WHERE `prcategory123_geozone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM prcategory123_tariff WHERE prcategory123_tariff_id IN (SELECT tariff_id FROM prcategory123_geozone_category WHERE prcategory123_geozone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM prcategory123_geozone_category WHERE prcategory123_geozone_id = " . (int)$pr_zone_id);
        }
    }
