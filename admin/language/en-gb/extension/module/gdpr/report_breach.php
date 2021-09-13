<?php

// Buttons
$_['button_add_breach'] = 'Add Data Breach Incident';
$_['button_download_pdf'] = 'Download notification letter in PDF format';
$_['button_email'] = 'Send emails';
$_['button_generate_customer_notifications'] = 'Generate Customer Notifications';
$_['button_preview_customer_notification'] = 'Preview';
$_['button_save_breach'] = 'Save';
$_['button_send'] = 'Send notification letter by email';

// Columns
$_['column_action'] = 'Action';
$_['column_bcc_to'] = 'BCC\'ed to';
$_['column_breach_id'] = 'Breach ID';
$_['column_customer_email'] = 'Email';
$_['column_customer_id'] = 'Customer ID';
$_['column_customer_name'] = 'Name';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'Date Added';
$_['column_date_of_breach']	= 'Date of Breach';
$_['column_date_of_discovery'] = 'Date of Discovery';
$_['column_date_updated'] = 'Last Update';
$_['column_number_of_accounts_affected'] = 'Number of Accounts Affected';
$_['column_breach_name'] = 'Breach Name';
$_['column_sent_to'] = 'E-mailed to';
$_['column_status'] = 'Commissioner Notification Status';
$_['column_status_customers'] = 'Customers Notification Status';
$_['column_status_notification'] = 'Customer Email Status';

// Heading
$_['heading_title']        = 'Notification of Data Security Breach';
$_['heading_title_customers']        = 'Notification of Data Security Breach';
$_['heading_title_customers_send_emails'] = 'Send Notification E-mails to Customers';
$_['heading_detailed'] = 'Notification of Data Security Breach to Data Proctection Commissioner';
$_['heading_detailed_customers'] = 'Notification of Data Security Breach to Customers';
$_['heading_detailed_customers_send_emails'] = 'Send Notifications to Customers manually in batches. This method is not recommended if you have a large number of customers.';

// Text
$_['text_breach_email_subject'] = 'Personal Data Breach Notification';
$_['text_commissioner'] = 'Data Protection Commissioner';
$_['text_customers'] = 'Customers';
$_['text_data_breach'] = 'Data Breach Reporting';
$_['text_data_breach_commissioner_list'] = 'Recorded Incidents';
$_['text_data_breach_commissioner_form'] = 'Add New Incident';
$_['text_data_breach_customer_list'] = 'Generate Notifications';
$_['text_data_breach_customer_emails'] = 'Recorded Email Notifications';
$_['text_data_breach_customer_emails_send'] = 'Send Emails';
$_['text_data_breach_customer_csv'] = 'Download Customer List';

$_['text_email_commissioner_body'] = 'Please find a data breach notification letter attached.';
$_['text_email_commissioner_subject'] = 'Notification of Data Security Breach';
$_['text_section_commissioner'] = 'Information for Data Protection Commissioner';
$_['text_section_customer'] = 'Information for Customers';
$_['text_section_general'] = 'General Information';
$_['text_success']         = 'Your message has been successfully sent!';
$_['text_success_saved_record']         = 'Your breach notification record has been successfully saved. Please note it has not been sent yet, to do that please download a PDF version of the letter os use the \'Email Commissioner\' button';
$_['text_success_generate_customer_notifications']         = 'Your customer notifications have been generated (Please bear in mind nothing has been emailed yet)!';
$_['text_sent']            = 'Your message has been successfully sent to %s of %s recipients!';
$_['text_notifications_emailed'] = 'Your e-mailed notifications:';
$_['text_notifications_pending'] = 'Your pending notifications:';
$_['text_total_customers'] = 'Total number of customer accounts in your system is';


$_['text_from']         = 'From:';
$_['text_to']         = 'To:';
$_['text_date']         = 'Date';
$_['text_default']         = 'Default';
$_['text_email'] = 'Send emails';
$_['text_header_cron'] = 'CRON Setup (recommended)';
$_['text_header_log'] = 'Log';
$_['text_hour'] = 'hour';
$_['text_hours'] = 'hours';
$_['text_instructions']    = 'Default';
$_['text_instructions_customers_send_emails'] = 'This form allows you to manually send batches of email notifications to your customers. You need to select settings that are appropriate for your hosting/server. Please bear in mind that if you server limits for sending emails are low, this may take a long time to complete. That is why we recommend setting up a cron job that will run this for you automatically (details available below). This script will only work on notifications that have not been sent yet. Every notification that is e-mailed to a customer will be marked as sent in the database, so you can track which notifications were sent.';
$_['text_instructions_log'] = 'The log of emails sent customers can be found in the Opencart log folder:';
$_['text_instructions_cron'] = 'To allow your server to send your emails automatically please setup a cron job using the following URL where \'max_runtime\' is the maximum time the server is allowed to run the script in minutes and \'batch_size\' is the maximum number of emails that can be send in one hour:';
$_['text_minutes'] = 'minutes';
$_['text_newsletter']      = 'All Newsletter Subscribers';
$_['text_customer_all']    = 'All Customers';
$_['text_list'] = 'Recorded Data Breaches (Data Commissioner)';
$_['text_list_customers'] = 'Recorded Data Breaches (Customers)';
$_['text_product']         = 'Products';
$_['text_add_breach']         = 'Add Data Breach Incident';
$_['text_return'] = 'Return';
$_['text_save_breach']         = 'Save';
$_['text_signature'] = 'Kind regards,';
$_['text_status_generated']         = 'Generated';
$_['text_status_pending']         = 'Pending';
$_['text_status_sent']         = 'Sent';
$_['text_status_unknown']         = 'Unknown';
$_['text_status_failed']         = 'Invalid E-mail';
$_['text_success_email_batch'] = 'Emails will be now sent out by your server. Please review the list of notifications in:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'We hereby report a data security breach incident that may involve personal information.';
$_['text_report_to_commissioner_contact'] = 'Our point of contact regarding this incident is:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = 'On %s we have discovered a data breach as detailed below:';
$_['text_report_to_commissioner_data_exposed'] = 'The data accessed may have included personal information such as:';
$_['text_report_to_commissioner_actions_taken'] = 'We have taken the following steps to remedy this incident so far:';
$_['text_report_to_commissioner_ending'] = 'We are conducting a thorough review of the potentially affected systems, and will notify the Data Protection Commissioner Office if there are any significant developments. We are implementing additional security measures designed to prevent a recurrence of such an attack, and to protect the privacy of our customers';

$_['text_send_breach_notification'] = 'Send Breach Notification';

// Entry
$_['entry_address_commissioner'] = 'Data Commissioner (Postal Address)';
$_['entry_address_store'] = 'Store (Postal Address)';
$_['entry_batch_size'] = 'Batch Size';
$_['entry_breach_notification_status'] = 'Status';
$_['entry_breach_customer_email_notification_status'] = 'Email Notification Status';
$_['entry_contact_details_of_person_reporting']       = 'Contact details for Data Commissioner';
$_['entry_contact_email']       = 'Contact email for Customers';
$_['entry_customer_email']       = 'Email';
$_['entry_customer_group'] = 'Customer Group';
$_['entry_customer']       = 'Customer';
$_['entry_date_added']       = 'Date Added';
$_['entry_date_of_breach']       = 'Date(s) of breach (if known)';
$_['entry_date_of_discovery']       = 'Date incident was discovered';
$_['entry_email_bcc']       = 'BCC';
$_['entry_email_commissioner'] = 'Data Commissioner (email)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Maximum runtime';
$_['entry_message_action']        = 'Brief Description of any action since breach was discovered (Commissioner)';
$_['entry_message_action_customers']        = 'Brief Description of actions taken (Customers)';
$_['entry_message_incident']       = 'Brief description of the breach (Commissioner)';
$_['entry_message_incident_customers']       = 'Brief description of the breach (Customers)';
$_['entry_name']       = 'Incident Name (for your reference)';
$_['entry_number_of_accounts_affected']       = 'Number of accounts affected (if known)';
$_['entry_store']          = 'From';
$_['entry_subject']        = 'Subject (Commissioner E-mail)';
$_['entry_subject_customers']        = 'Subject (Customers E-mails)';
$_['entry_to']             = 'To';

// Help
$_['help_address_commissioner'] = 'Full postal address of the Data Protection Commissioner. This will be printed in the header for the breach notification letter';
$_['help_address_store'] = 'Full postal address of your store that is reporting the breach. This will be printed in the header of the letter';
$_['help_batch_size'] = 'A number of emails that can be sent in one hour. This will depend on your server configuration. Most shared servers will limit it heavily, so you will not be allowed to send more then 100-200 emails per hour.';
$_['help_contact_details_of_person_reporting'] = 'Add email address and/or phone number of a person that can be contacted by the Data Protection Commissioner Office';
$_['help_contact_email'] = 'Add email address that your customers can contact in regard to the data breach.';
$_['help_data_commissioner']       = 'Email Address of the Data Commisioner in your jurisdiction';
$_['help_date_of_breach'] = 'If you do not know the exact date, please give an estimated time range';
$_['help_date_of_discovery'] = 'Date when you discovered your system has been breached';
$_['help_email_bcc']       = 'E-mail addresses to be carbon copied on the breach notification';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Maximum time that e-mail sending script should be run for.';
$_['help_message_action'] = 'Describe all actions you have taken since the breach was discovered. This text will be included in the letter to the Data Protection Commissioner.';
$_['help_message_action_customers'] = 'Describe all actions you have taken since the breach was discovered that are relevant to your customers. This text will be included in the emails to customers.';
$_['help_message_incident'] = 'Describe the breach in details, who was accessing the data, how did they do it, how was it discovered etc. This text will be included in the letter to the Data Protection Commissioner.';
$_['help_message_incident_customers'] = 'Describe the breach with any details relevant to your customers. This text will be included in the emails to customers.';
$_['help_name'] = 'This name is for the store owner/admin reference only, it will not be revealed to Data Commissioner or Customers';
$_['help_number_of_accounts_affected'] = 'State how many accounts (customers / data subjects) have been affected by the breach';
$_['help_subject']       = 'Subject of the nofification email/letter to Data Protection Commissioner';
$_['help_subject_customers']       = 'Subject of the nofification emails to customers';

// Error
$_['error_address_commissioner']        = 'Data Protection Commissioner address is required!';
$_['error_address_store']        = 'Store address is required!';
$_['error_contact_details_of_person_reporting']        = 'Contact details of person sending the report are required!';
$_['error_customer_notification_add'] = 'Could not add customer notifications';
$_['error_customer_notification_existing'] = 'There are already existing notification for this data breach';
$_['error_data_commissioner']        = 'Data Protection Commissioner email is required!';
$_['error_date_of_breach']        = 'Date of breach is required!';
$_['error_date_of_discovery']        = 'Date of breach discovery is required!';
$_['error_email_batch'] = 'Your email notifications could not be sent, please try again.';
$_['error_permission']     = 'Warning: You do not have permission to send Breach Notification E-Mails!';
$_['error_subject']        = 'Breach Notification e-mail subject required!';
$_['error_mail_bcc'] = 'E-mail format incorrect, please provide email addresses as a comma separated list';
$_['error_mail_commissioner'] = 'E-mail for data commissioner is incorrect format.';
$_['error_message_action']        = 'Summary of actions taken after the breach is required!';
$_['error_message_incident']        = 'Description of the incident/breach is required!';
$_['error_missing_commissioner_email'] = 'Commissioner email is a required field!';
$_['error_saving_breach_notification_failed'] = 'We could not save your breach notification record, please try again';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'data_breach_notification';
