<?php

namespace nitropackio\core;

class OrderHistory extends Library {
    private $stock = array();

    public function persistOrderStock($order_id) {
        $this->stock[$order_id] = $this->getOrderStocks($order_id);
    }

    public function getProductIdsWithDifference($order_id) {
        $result = array();

        if (isset($this->stock[$order_id])) {
            $this->load->model('checkout/order');

            $stocks = $this->getOrderStocks($order_id);

            foreach ($stocks as $order_product_id => $stock) {
                // We only care about the quantity availability, not the actual value
                if ((bool)$stock['quantity'] != (bool)$this->stock[$order_id][$order_product_id]['quantity']) {
                    $result[] = $stock['product_id'];
                }
            }
        }

        return $result;
    }

    protected function getOrderStocks($order_id) {
        $result = array();

        $sql = "SELECT op.order_product_id, p.quantity, op.product_id FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = op.product_id) WHERE p.subtract='1' AND op.order_id='" . (int)$order_id . "'";

        foreach ($this->db->query($sql)->rows as $row) {
            $result[(int)$row['order_product_id']] = array(
                'product_id' => (int)$row['product_id'],
                'quantity' => (int)$row['quantity']
            );
        }

        return $result;
    }
}