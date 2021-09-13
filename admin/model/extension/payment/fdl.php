<?php
class ModelExtensionPaymentFdl extends Model {

    /**
     * Map Oxid currency code to ISO number, used by FDL
     *
     * @param string $code
     * @return int|boolean
     */
    public function mapCurrency($code) {

        $currency = array(
            'GBP' => 826,
            'USD' => 840,
            'EUR' => 978,
            'CZK' => 203,
        );

        if (array_key_exists($code, $currency)) {
            return $currency[$code];
        } else {
            return false;
        }
    }

    /**
     * Save FDL status in order
     *
     * @param int $order_id
     * @param string $status
     */
    public function saveStatus($order_id, $status) {
        $this->db->query("
            UPDATE
                `" . DB_PREFIX . "fdl`
            SET
                `status` = '" . $this->db->escape($status) . "',
                `date` = NOW()
            WHERE
                `order_id` = '" . (int)$order_id . "' LIMIT 1"
        );
    }

    /**
     * Gets transactions made for order
     *
     * @param int $order_id
     */
    public function getTrans($order_id) {
        $query = $this->db->query("
            SELECT * FROM
                `" . DB_PREFIX . "fdl`
            WHERE
                `order_id` = " . (int)$order_id);

        if (!$query->num_rows) {
            return false;
        }

        return $query->rows;
    }

    /**
     * Gets order with transaction if exists
     *
     * @param int $order_id
     */
    public function getOrder($order_id) {

        $this->load->model('sale/order');

        $trans = $this->getTrans($order_id);

        $order = $this->model_sale_order->getOrder($order_id);

        if (!$trans) {
            return $order;
        }

        return array_merge($order, array('transaction' => $trans[0]));
    }

}
