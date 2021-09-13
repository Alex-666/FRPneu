<?php

class ModelLocalisationCurrency extends Model
{
    public function getCurrencyByCode($currency)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

        return $query->row;
    }

    public function getCurrencies()
    {
        $currency_data = $this->cache->get('currency');

        if (!$currency_data) {
            $currency_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

            foreach ($query->rows as $result) {

                $currency_data[$result['code']] = array(
                    'currency_id' => $result['currency_id'],
                    'title' => $result['title'],
                    'code' => $result['code'],
                    'symbol_left' => $result['symbol_left'],
                    'symbol_right' => $result['symbol_right'],
                    'decimal_place' => $result['decimal_place'],
                    'value' => $result['value'],
                    'status' => $result['status'],
                    'date_modified' => $result['date_modified']
                );
            }

            $this->cache->set('currency', $currency_data);
        }
        $data['action'] = $this->url->link('common/currency/currency', '', $this->request->server['HTTPS']);
        If (strripos($data['action'], 'frpneu.sk')) {
            $allowed = ["EUR"];
            $currency_data = array_filter(
                $currency_data,
                function ($key) use ($allowed) {
                    return in_array($key, $allowed);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        /*
        If (strripos($data['action'], 'frpneu.sk')) {
            foreach ($currency_data as $key => $result) {
                If ($result["currency_id"] !== "3") {
                    $currency_data[$key]['status'] = "0";
                }
            }

        }
        */

        return $currency_data;
    }
}