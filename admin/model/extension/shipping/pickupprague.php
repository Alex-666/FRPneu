<?php
    class ModelExtensionShippingPickupPrague extends Model {

        public function getZone($zone_id) {

            return $this->db->query("SELECT * FROM pickupprague_zone WHERE pickupprague_zone_id = " . (int)$zone_id)->row;
        }

        public function getZones() {

            return $this->db->query("SELECT * FROM pickupprague_zone ORDER BY country_id")->rows;
        }

        public function getCategoriesForZone($zone_id) {

            return $this->db->query("SELECT * FROM pickupprague_zone_category zc LEFT JOIN pickupprague_tariff t ON zc.tariff_id = t.pickupprague_tariff_id WHERE zc.pickupprague_zone_id = " . (int)$zone_id . " ORDER BY zc.zone_category_id ASC")->rows;
        }

        public function getGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM pickupprague_geozone WHERE pickupprague_geozone_id = " . (int)$zone_id)->row;
        }

        public function getGeoZones() {

            return $this->db->query("SELECT * FROM pickupprague_geozone")->rows;
        }

        public function getCategoriesForGeoZone($zone_id) {

            return $this->db->query("SELECT * FROM pickupprague_geozone_category zc LEFT JOIN pickupprague_tariff t ON zc.tariff_id = t.pickupprague_tariff_id WHERE zc.pickupprague_geozone_id = " . (int)$zone_id . " ORDER BY zc.geozone_category_id ASC")->rows;
        }

        public function editPrZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE pickupprague_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `pickupprague_zone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM pickupprague_tariff WHERE pickupprague_tariff_id IN (SELECT tariff_id FROM pickupprague_zone_category WHERE pickupprague_zone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM pickupprague_zone_category WHERE pickupprague_zone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO pickupprague_zone SET `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `enabled_cities` = '" . $this->db->escape($data['enabled_cities']) . "', `disabled_cities` = '" . $this->db->escape($data['disabled_cities']) . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO pickupprague_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO pickupprague_zone_category SET `pickupprague_zone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function editPrGeoZone($pr_zone_id, $data) {

            if ($pr_zone_id) {

                $this->db->query("UPDATE pickupprague_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "' WHERE `pickupprague_geozone_id` = " . (int)$pr_zone_id);

                $this->db->query("DELETE FROM pickupprague_tariff WHERE pickupprague_tariff_id IN (SELECT tariff_id FROM pickupprague_geozone_category WHERE pickupprague_geozone_id = " . (int)$pr_zone_id . ")");
                $this->db->query("DELETE FROM pickupprague_geozone_category WHERE pickupprague_geozone_id = " . (int)$pr_zone_id);
            }
            else {
                $this->db->query("INSERT INTO pickupprague_geozone SET `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "', `rate` = '" . $this->db->escape($data['rate']) . "', `cost` = '" . (int)$data['cost'] . "', `cost_basis` = '" . (int)$data['cost_basis'] . "'");
                $pr_zone_id = $this->db->getLastId();
            }

            foreach ($data['tariffs'] as $tariff) {

                if (!empty($tariff['categories'])) {

                    $this->db->query("INSERT INTO pickupprague_tariff SET `rate` = '" . $this->db->escape($tariff['rate']) . "', `cost` = '" . (int)$tariff['cost'] . "', `cost_basis` = '" . (int)$tariff['cost_basis'] . "'");
                    $tariff_id = $this->db->getLastId();

                    foreach ($tariff['categories'] as $category_id) {
                        $this->db->query("INSERT INTO pickupprague_geozone_category SET `pickupprague_geozone_id` = " . (int)$pr_zone_id . ", `category_id` = '" . (int)$category_id . "', `tariff_id` = '" . $tariff_id . "'");
                    }
                }
            }
        }

        public function removePrZone($pr_zone_id) {

            $this->db->query("DELETE FROM pickupprague_zone WHERE `pickupprague_zone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM pickupprague_tariff WHERE pickupprague_tariff_id IN (SELECT tariff_id FROM pickupprague_zone_category WHERE pickupprague_zone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM pickupprague_zone_category WHERE pickupprague_zone_id = " . (int)$pr_zone_id);
        }

        public function removePrGeoZone($pr_zone_id) {

            $this->db->query("DELETE FROM pickupprague_geozone WHERE `pickupprague_geozone_id` = " . (int)$pr_zone_id);
            $this->db->query("DELETE FROM pickupprague_tariff WHERE pickupprague_tariff_id IN (SELECT tariff_id FROM pickupprague_geozone_category WHERE pickupprague_geozone_id = " . (int)$pr_zone_id . ")");
            $this->db->query("DELETE FROM pickupprague_geozone_category WHERE pickupprague_geozone_id = " . (int)$pr_zone_id);
        }
    }
