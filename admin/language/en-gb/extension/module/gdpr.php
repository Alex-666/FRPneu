<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'GDPR Settings';
$_['text_gdpr_report']             = 'GDPR Requests History';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Save';
$_['button_cancel'] = 'Cancel';

// Entry
$_['entry_admin']      = 'Admin Users Only';
$_['entry_status']     = 'Status';
$_['entry_name'] = 'Name';
$_['entry_message_text'] = 'Message to display';
$_['entry_date_start'] = 'Start Date (YYYY-MM-DD)';
$_['entry_date_end'] = 'End Date (YYYY-MM-DD)';
$_['entry_status'] = 'Status';

$_['entry_email_footer'] = 'GDPR Email Footer Text';
$_['entry_email_header'] = 'GDPR Email Header Text';
$_['entry_locations_of_other_data'] = 'Other locations/services where you store personal data';
$_['entry_locations_of_servers'] = 'Physical locations of servers where you host your website and other data.';
$_['entry_max_requests_day'] = 'Max Requests per Day';
$_['entry_pending_status'] = 'Pending Status Text';
$_['entry_confirmed_status'] = 'Confirmed Status Text';
$_['entry_emailed_status'] = 'Emailed Status Text';
$_['entry_account_deleted_status'] = 'Account Deleted Status Text';
$_['entry_data_sent'] = 'Data Sent';
$_['entry_unpaid'] = 'Unpaid Text';
$_['entry_free'] = 'Free Text';
$_['entry_rejected'] = 'Rejected Text';
$_['entry_fairuse'] = 'Fair Usage';
$_['entry_max_days_process'] = 'Max Number of Days to Respond';
$_['entry_right_to_be_forgotten'] = 'Enable Right to Be Forgotten Form';

// Help
$_['help_pending_status'] = 'Name of the status of a GDPR request that has not been yet confirmed by the customer. This status name will be displayed in the admin GDPR request report and revealed to the customer in the historical record of their GDPR requests.';
$_['help_confirmed_status'] = 'Name of the status of a GDPR request that have been confirmed by the customer using the email confirmation link but has not been processed yet (the report email has not been sent yet). This status name will be displayed in the admin GDPR request report and revealed to the customer in the historical record of their GDPR requests.';
$_['help_emailed_status'] = 'Name of the status of a GDPR request that has been processed/completed (the report email has been sent to the customer). This status name will be displayed in the admin GDPR request report and revealed to the customer in the historical record of their GDPR requests.';
$_['help_account_deleted_status'] = 'Name of the status of a GDPR account removal request that have been processed/completed. This status name will be displayed in the admin GDPR request report.';
$_['help_locations_of_other_data'] = 'List all other locations and web services where you store customer personal data here. Examples: Mailchimp, Google Docs, etc. This information will be included in the GDPR email report sent to the customer.';
$_['help_locations_of_servers'] = 'List all relevant information about your hosting and server locations (For example: server locations countries, hosting providers, are the hosting providers GDPR compliant? etc.).';
$_['help_max_requests_day'] = 'Number of requests the customer is allowed to submit per day. This should be set to a low number to prevent spam requests, attacks and unnecessary load on your web server. We recommend a value between 3-5 at the most.';
$_['help_right_to_be_forgotten'] = 'This will enable a \'Right to be Forgotten\' form where customers can automatically delete their account and anynymize their personal data where it cannot be deleted';

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify GDPR module!';
$_['error_name'] = 'Name is not valid, it has to be at least one character long!';
$_['error_text'] = 'Message text is not valid, it can be maximum 5000 characters long!';
$_['error_date_start'] = 'Start date is not valid!';
$_['error_date_end'] = 'End date is not valid!';

// Text
$_['text_module']      = 'Modules';
$_['text_success']     = 'Success: You have modified GDPR module!';
$_['text_edit']        = 'Edit GDPR Module';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Store Policy Acceptance';
$_['entry_forms_are_private'] = 'GDPR Forms Require Login';

$_['help_store_policy_acceptance'] = 'If set to yes, every time customer accepts your terms on the registration page or in the checkout, this will be recorded in the database. IMPORTANT: if you set it to yes please make sure you checkout is working correctly!';
$_['help_forms_are_private'] = 'If set to yes, GDPR request can only be submitted by a logged in customer.';

// Added in v1.6
$_['text_restricted_processing'] = 'Restricted Processing';
// When translating please do not alter <a href="customer_id=%d">%s</a> which will be replaced with a link to customer account.
$_['text_activity_gdpr_restriction_of_processing'] = '<a href="customer_id=%d">%s</a> restricted processing of personal data.';
