<?php
    class ModelExtensionShippingPrCategoryDPD extends Model {

        public function getZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategorydpd_zone WHERE prcategorydpd_zone_id = " . (int)$zone_id)->row;
        }

        public function getZones() {

            return $this->db->query("SELECT * FROM prcategorydpd_zone ORDER BY country_id")->rows;
        }

        public function getCategoriesForZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategorydpd_zone_category zc LEFT JOIN prcategorydpd_tariff t ON zc.tariff_id = t.prcategorydpd_tariff_id WHERE zc.prcategorydpd_zone_id = " . (int)$zone_id . " ORDER BY zc.zone_category_id ASC")->rows;
        }

        public function getGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategorydpd_geozone WHERE prcategorydpd_geozone_id = " . (int)$zone_id)->row;
        }

        public function getGeoZones() {

            return $this->db->query("SELECT * FROM prcategorydpd_geozone")->rows;
        }

        public function getCategoriesForGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategorydpd_geozone_category zc LEFT JOIN prcategorydpd_tariff t ON zc.tariff_id = t.prcategorydpd_tariff_id WHERE zc.prcategorydpd_geozone_id = " . (int)$zone_id . " ORDER BY zc.geozone_category_id ASC")->rows;
        }

        public function editPrZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE prcategorydpd_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `prcategorypdp_zone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM prcategorydpd_tariff WHERE prcategorydpd_tariff_id IN (SELECT tariff_id FROM prcategorydpd_zone_category WHERE prcategorydpd_zone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM prcategorydpd_zone_category WHERE prcategorydpd_zone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO prcategorydpd_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO prcategorydpd_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO prcategorydpd_zone_category SET `prcategorydpd_zone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function editPrGeoZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE prcategorydpd_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `prcategorydpd_geozone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM prcategorydpd_tariff WHERE prcategorydpd_tariff_id IN (SELECT tariff_id FROM prcategorydpd_geozone_category WHERE prcategorydpd_geozone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM prcategorydpd_geozone_category WHERE prcategorydpd_geozone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO prcategorydpd_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO prcategorydpd_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO prcategorydpd_geozone_category SET `prcategorydpd_geozone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function removePrZone($pr_zone_id) {

            $this->db->query("DELETE FROM prcategorydpd_zone WHERE `prcategorydpd_zone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM prcategorydpd_tariff WHERE prcategorydpd_tariff_id IN (SELECT tariff_id FROM prcategorydpd_zone_category WHERE prcategorydpd_zone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM prcategorydpd_zone_category WHERE prcategorydpd_zone_id = " . (int)$pr_zone_id);
        }

        public function removePrGeoZone($pr_zone_id) {

            $this->db->query("DELETE FROM prcategorydpd_geozone WHERE `prcategorydpd_geozone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM prcategorydpd_tariff WHERE prcategorydpd_tariff_id IN (SELECT tariff_id FROM prcategorydpd_geozone_category WHERE prcategorydpd_geozone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM prcategorydpd_geozone_category WHERE prcategorydpd_geozone_id = " . (int)$pr_zone_id);
        }
    }
