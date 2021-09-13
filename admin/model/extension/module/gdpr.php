<?php
class ModelExtensionModuleGdpr extends Model {

  /**
   * Store Commissioner Breach Notification record
   * @param  [array] $data
   * @return int
   */
  public function addBreachNotification($data) {
    $this->db->query("INSERT INTO " . DB_PREFIX . "gdpr_breach_notification SET
    store_id = '" . (int)$data['store_id'] . "',
    address_commissioner = '" . $this->db->escape($data['address_commissioner']) . "',
    address_store = '" . $this->db->escape($data['address_store']) . "',
    date_of_breach = '" . $this->db->escape($data['date_of_breach']) . "',
    date_of_discovery = '" . $this->db->escape($data['date_of_discovery']) . "',
    contact_details_of_person_reporting = '" . $this->db->escape($data['contact_details_of_person_reporting']) . "',
    contact_email = '" . $this->db->escape($data['contact_email']) . "',
    number_of_accounts_affected = '" . $this->db->escape($data['number_of_accounts_affected']) . "',
    message_action = '" . $this->db->escape($data['message_action']) . "',
    message_action_customers = '" . $this->db->escape($data['message_action_customers']) . "',
    message_incident = '" . $this->db->escape($data['message_incident']) . "',
    message_incident_customers = '" . $this->db->escape($data['message_incident_customers']) . "',
    name = '" . $this->db->escape($data['name']) . "',
    subject = '" . $this->db->escape($data['subject']) . "',
    subject_customers = '" . $this->db->escape($data['subject_customers']) . "',
    status = '" . (int)$data['status'] . "',
    status_customers = '" . (int)$data['status_customers'] . "',
    date_added = '" . $this->db->escape($data['date_added']) . "'");

    $id = $this->db->getLastId();

    if(!empty($id)) {
      return $id;
    } else {
      return 0;
    }
  }

  /**
   * Add new customer notification record
   * @param [array] $customer
   * @param [array] $breach
   * @param [string] $store_email
   * @param [int] $status
   */
  public function addCustomerNotification($customer, $breach, $store_email, $status) {
    $query = $this->db->query("INSERT INTO " . DB_PREFIX . "gdpr_breach_notification_customers_emailed SET
    breach_id = '" . (int)$breach['breach_id'] . "',
    customer_id = '" . (int)$customer['customer_id'] . "',
    store_email = '" . $store_email . "',
    customer_email = '" . $customer['email'] . "',
    firstname = '" . $customer['firstname'] . "',
    lastname = '" . $customer['lastname'] . "',
    status = '" . $status . "',
    date_added = NOW(),
    date_updated = NOW()");

    $id = $this->db->getLastId();

    if(!empty($id)) {
      return $id;
    } else {
      return 0;
    }
  }

  /**
   * Add emails to breach notification record when sending notification
   * email from the system
   * @param [int] $breach_id
   * @param [string] $email_commissioner
   * @param [string] $email_bcc
   */
  public function addEmailsToBreachNotification($breach_id, $email_commissioner, $email_bcc) {
    $this->db->query("UPDATE " . DB_PREFIX . "gdpr_breach_notification SET
    email_commissioner = '" . $this->db->escape($email_commissioner) . "',
    email_bcc = '" . $this->db->escape($email_bcc) . "'
    WHERE breach_id = '" . (int)$breach_id . "'");
  }

  /**
   * Edit breach notification record
   * @param  [int] $id
   * @param  [array] $data
   * @return void
   */
  public function editBreachNotification($breach_id, $data) {
    $this->db->query("UPDATE " . DB_PREFIX . "gdpr_breach_notification SET
    store_id = '" . (int)$data['store_id'] . "',
    address_commissioner = '" . $this->db->escape($data['address_commissioner']) . "',
    address_store = '" . $this->db->escape($data['address_store']) . "',
    date_of_breach = '" . $this->db->escape($data['date_of_breach']) . "',
    date_of_discovery = '" . $this->db->escape($data['date_of_discovery']) . "',
    contact_details_of_person_reporting = '" . $this->db->escape($data['contact_details_of_person_reporting']) . "',
    contact_email = '" . $this->db->escape($data['contact_email']) . "',
    number_of_accounts_affected = '" . $this->db->escape($data['number_of_accounts_affected']) . "',
    message_action = '" . $this->db->escape($data['message_action']) . "',
    message_action_customers = '" . $this->db->escape($data['message_action_customers']) . "',
    message_incident = '" . $this->db->escape($data['message_incident']) . "',
    message_incident_customers = '" . $this->db->escape($data['message_incident_customers']) . "',
    name = '" . $this->db->escape($data['name']) . "',
    subject = '" . $this->db->escape($data['subject']) . "',
    subject_customers = '" . $this->db->escape($data['subject_customers']) . "',
    date_updated = '" . $this->db->escape($data['date_updated']) . "'
    WHERE breach_id = '" . (int)$breach_id . "'");

    return true;
  }

  /**
   * Get all customer records with emails and names
   * @return [array]
   */
  public function getAllCustomers() {
    $query = $this->db->query("SELECT customer_id, email, firstname, lastname FROM " . DB_PREFIX . "customer WHERE 1");

    return $query->rows;
  }

  /**
   * Get single record of breach notification for Data Protection Commissioner Office
   * @param  [int] $breach_id
   * @return [array]
   */
  public function getBreachNotification($breach_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gdpr_breach_notification WHERE breach_id ='" . (int)$breach_id . "'");

    if(!empty($query->row)) {
      return $query->row;
    } else {
      return false;
    }
  }

  /**
   * Get list of data breach notification for Data Protection Commissioner Office
   * @param  array  $data [array]
   * @return [array]
   */
  public function getBreachNotifications($data = array()) {

    $sql = "SELECT * FROM " . DB_PREFIX . "gdpr_breach_notification AS br";

		if (isset($data['filter_breach_notification_status'])) {
			$implode = array();

			$breach_statuses = explode(',', $data['filter_breach_notification_status']);

			foreach ($breach_statuses as $breach_status_id) {
				$implode[] = "br.status = '" . (int)$breach_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE br.status > '0'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(br.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'status',
			'br.date_added',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " order BY " . $data['sort'];
		} else {
			$sql .= " order BY br.breach_id";
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
	}

  /**
   * Get Breach Notifications by status
   * @param  [int] $status
   * @return [array]
   */
  public function getBreachCustomerEmailNotificationByStatus($status) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gdpr_breach_notification_customers_emailed WHERE status='" . $status . "'");

    return $query->rows;
  }

  /**
   * Get list of data breach customer email notification records
   * @param  array  $data [array]
   * @return [array]
   */
  public function getBreachCustomerEmailNotifications($data = array()) {

    $sql = "SELECT * FROM " . DB_PREFIX . "gdpr_breach_notification_customers_emailed AS brce";

		if (isset($data['filter_breach_customer_email_notification_status'])) {
			$implode = array();

			$email_notification_statuses = explode(',', $data['filter_breach_customer_email_notification_status']);

			foreach ($email_notification_statuses as $email_notification_id) {
				$implode[] = "brce.status = '" . (int)$email_notification_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE brce.status > '0'";
		}

    if (!empty($data['filter_customer_email'])) {
      $sql .= " AND customer_email LIKE '" . $data['filter_customer_email'] . "'";
    }

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(br.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'brce.status',
			'brce.date_added',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " order BY " . $data['sort'];
		} else {
			$sql .= " order BY brce.breach_id";
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
	}


  /**
   * Get count of customer emails notifications with a given status
   * @param  [int] $status
   * @return [int]
   */
  public function getCustomerEmailNotificationsCount($status) {
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gdpr_breach_notification_customers_emailed WHERE status = '" . (int)$status . "'");

    if(!empty($query->row['total'])) {
      return $query->row['total'];
    } else {
      return 0;
    }
  }

  /**
   * Get status of customer preference for restriction of processing.
   * @param  [int] $customer_id
   * @return [int]
   */
  public function getCustomerRestrictProcessingStatus($customer_id) {
    $query = $this->db->query("SELECT restriction_status FROM "  . DB_PREFIX . "gdpr_restriction_of_processing WHERE customer_id = '" . (int)$customer_id . "'");

    if(!empty($query->row['restriction_status'])) {
      return $query->row['restriction_status'];
    } else {
      return 0;
    }
  }

  /**
   * Get total number of data breach notification for Data Protection Commissioner Office
   * @param  array  $data [array]
   * @return [int]
   */
  public function getTotalBreachNotifications($data = array()) {
    $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gdpr_breach_notification AS br";

    if (isset($data['filter_breach_notification_status'])) {
			$implode = array();

			$breach_statuses = explode(',', $data['filter_breach_notification_status']);

			foreach ($breach_statuses as $breach_status_id) {
				$implode[] = "br.status = '" . (int)$breach_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE br.status > '0'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(br.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

    $query = $this->db->query($sql);

    if(!empty($query->row['total'])) {
      return $query->row['total'];
    } else {
      return 0;
    }
  }

  /**
   * Get total number of data breach customer email notifications records
   * @param  array  $data [array]
   * @return [int]
   */
  public function getTotalBreachCustomerEmailNotifications($data = array()) {
    $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gdpr_breach_notification_customers_emailed AS brce";

    if (isset($data['filter_breach_customer_email_notification_status'])) {
      $implode = array();

      $email_notification_statuses = explode(',', $data['filter_breach_customer_email_notification_status']);

      foreach ($email_notification_statuses as $notification_status_id) {
        $implode[] = "brce.status = '" . (int)$notification_status_id . "'";
      }

      if ($implode) {
        $sql .= " WHERE (" . implode(" OR ", $implode) . ")";
      }
    } else {
      $sql .= " WHERE brce.status > '0'";
    }

    if (!empty($data['filter_customer_email'])) {
      $sql .= " AND customer_email LIKE '" . $data['filter_customer_email'] . "'";
    }

    if (!empty($data['filter_date_added'])) {
      $sql .= " AND DATE(br.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
    }

    $query = $this->db->query($sql);

    if(!empty($query->row['total'])) {
      return $query->row['total'];
    } else {
      return 0;
    }

  }

  /**
   * Check if customer notification record exists in the database
   * @param  [int]  $customer_id
   * @param  [int]  $breach_id
   * @return boolean
   */
  public function isExistingCustomerNotification($customer_id, $breach_id) {
    $query = $this->db->query("SELECT customer_notification_id FROM " . DB_PREFIX . "gdpr_breach_notification_customers_emailed WHERE customer_id = '" . (int)$customer_id . "' AND breach_id = '" . (int)$breach_id . "'");

    if(!empty($query->row['customer_notification_id'])) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Update the status of Commissioner Breach Notification
   * @param  [int] $breach_id
   * @param  [int] $status
   * @return void
   */
  public function updateBreachNotificationStatus($breach_id, $status) {
    $this->db->query("UPDATE " . DB_PREFIX . "gdpr_breach_notification SET status = '" . (int)$status . "' WHERE 	breach_id = '" . (int)$breach_id . "'");
  }

  /**
   * Update the status of Commissioner Breach Notification
   * @param  [int] $breach_id
   * @param  [int] $status
   * @return void
   */
  public function updateBreachNotificationStatusCustomers($breach_id, $status_customers) {
    $this->db->query("UPDATE " . DB_PREFIX . "gdpr_breach_notification SET status_customers = '" . (int)$status_customers . "' WHERE 	breach_id = '" . (int)$breach_id . "'");
  }

}
