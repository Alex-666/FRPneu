<?php
class ModelExtensionReportGdpr extends Model {

	/**
	 * Get GDPR Policy Acceptance Records
	 * @param  array  $data [filters]
	 * @return array
	 */
	public function getGdprPolicyRecords($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "gdpr_policy_accepted";

		if (!empty($data['filter_customer_email'])) {
			$sql .= " WHERE customer_email = '" . $data['filter_customer_email'] . "'";
		} else {
			$sql .= " WHERE 1";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_accepted) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_accepted) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= " ORDER BY policy_acceptance_id DESC";

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
	}

	/**
	 * Get GDPR Requests
	 * @param  array  $data [filters]
	 * @return array
	 */
	public function getGdprRequests($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "gdpr_request";

		if (!empty($data['filter_customer_email'])) {
			$sql .= " WHERE email = '" . $data['filter_customer_email'] . "'";
		} else {
			$sql .= " WHERE 1";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(request_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(request_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= " ORDER BY request_id DESC";

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
	}

	/**
	 * Get a record of the customer accepting store policy
	 * @param  [int] $policy_acceptance_id
	 * @return [array] $policy_acceptance_record
	 */
	public function getPolicyAcceptanceRecord($policy_acceptance_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gdpr_policy_accepted WHERE policy_acceptance_id = '" . (int)$policy_acceptance_id . "'");

		$policy_acceptance_record = $query->rows;

		if(!empty($policy_acceptance_record)) {
			return $policy_acceptance_record;
		} else {
			return false;
		}
	}

	/**
	 * Get Total Policy Acceptance Records
	 * @param  array  $data [filters]
	 * @return array
	 */
	public function getTotalGdprPolicyRecords($data = array()) {

		$sql = "SELECT COUNT(policy_acceptance_id) as 'total' FROM " . DB_PREFIX . "gdpr_policy_accepted";

		if (!empty($data['filter_customer_email'])) {
			$sql .= " WHERE customer_email = '" . $data['filter_customer_email'] . "'";
		} else {
			$sql .= " WHERE 1";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_accepted) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_accepted) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		if(!empty($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}

	}

	/**
	 * Get Total GDPR Requests
	 * @param  array  $data [filters]
	 * @return array
	 */
	public function getTotalGdprRequests($data = array()) {

		$sql = "SELECT COUNT(request_id) as 'total' FROM " . DB_PREFIX . "gdpr_request";

		if (!empty($data['filter_customer_email'])) {
			$sql .= " WHERE email = '" . $data['filter_customer_email'] . "'";
		} else {
			$sql .= " WHERE 1";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(request_time) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(request_time) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		if(!empty($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}

	}
}
