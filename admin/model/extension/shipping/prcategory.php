<?php
    class ModelExtensionShippingPrCategory extends Model {

        public function getZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory_zone WHERE prcategory_zone_id = " . (int)$zone_id)->row;
        }

        public function getZones() {

            return $this->db->query("SELECT * FROM prcategory_zone ORDER BY country_id")->rows;
        }

        public function getCategoriesForZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory_zone_category zc LEFT JOIN prcategory_tariff t ON zc.tariff_id = t.prcategory_tariff_id WHERE zc.prcategory_zone_id = " . (int)$zone_id . " ORDER BY zc.zone_category_id ASC")->rows;
        }

        public function getGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory_geozone WHERE prcategory_geozone_id = " . (int)$zone_id)->row;
        }

        public function getGeoZones() {

            return $this->db->query("SELECT * FROM prcategory_geozone")->rows;
        }

        public function getCategoriesForGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM prcategory_geozone_category zc LEFT JOIN prcategory_tariff t ON zc.tariff_id = t.prcategory_tariff_id WHERE zc.prcategory_geozone_id = " . (int)$zone_id . " ORDER BY zc.geozone_category_id ASC")->rows;
        }

        public function editPrZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE prcategory_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `prcategory_zone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM prcategory_tariff WHERE prcategory_tariff_id IN (SELECT tariff_id FROM prcategory_zone_category WHERE prcategory_zone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM prcategory_zone_category WHERE prcategory_zone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO prcategory_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO prcategory_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO prcategory_zone_category SET `prcategory_zone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function editPrGeoZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE prcategory_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `prcategory_geozone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM prcategory_tariff WHERE prcategory_tariff_id IN (SELECT tariff_id FROM prcategory_geozone_category WHERE prcategory_geozone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM prcategory_geozone_category WHERE prcategory_geozone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO prcategory_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO prcategory_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO prcategory_geozone_category SET `prcategory_geozone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function removePrZone($pr_zone_id) {

            $this->db->query("DELETE FROM prcategory_zone WHERE `prcategory_zone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM prcategory_tariff WHERE prcategory_tariff_id IN (SELECT tariff_id FROM prcategory_zone_category WHERE prcategory_zone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM prcategory_zone_category WHERE prcategory_zone_id = " . (int)$pr_zone_id);
        }

        public function removePrGeoZone($pr_zone_id) {

            $this->db->query("DELETE FROM prcategory_geozone WHERE `prcategory_geozone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM prcategory_tariff WHERE prcategory_tariff_id IN (SELECT tariff_id FROM prcategory_geozone_category WHERE prcategory_geozone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM prcategory_geozone_category WHERE prcategory_geozone_id = " . (int)$pr_zone_id);
        }
    }
