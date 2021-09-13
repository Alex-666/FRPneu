<?php
class ModelExtensionPaymentFdl extends Model {

    public function getMethod($address, $total) {

        $this->load->language('payment/fdl');

        $status = true;
        if ($this->config->get('payment_fdl_total') > 0 && $this->config->get('payment_fdl_total') > $total) {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'fdl',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('payment_fdl_sort_order')
            );
        }

        return $method_data;
    }

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
     * Save FDL TRANSACTION_ID in order
     *
     * @param int $order_id
     * @param string $trans_id
     */
    public function saveTransId($order_id, $trans_id) {
        $this->db->query("
            INSERT INTO
                `" . DB_PREFIX . "fdl`
            SET
                `order_id` = '" . (int)$order_id . "',
                `transaction_id` = '" . $trans_id . "',
                `date` = now()"
        );
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

        if($status == 'success') {
            $this->db->query("UPDATE " . DB_PREFIX . "order SET is_paid = 1 WHERE order_id = '" . (int)$order_id . "' LIMIT 1");
        }
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

        $this->load->model('checkout/order');

        $trans = $this->getTrans($order_id);

        $order = $this->model_checkout_order->getOrder($order_id);

        if (!$trans) {
            return $order;
        }

        return array_merge($order, array('transaction' => $trans[0]));
    }

}
