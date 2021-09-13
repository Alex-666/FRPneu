<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'GDPR Indstillinger';
$_['text_gdpr_report']             = 'GDPR Forespørgselshistorie';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Gem';
$_['button_cancel'] = 'Annuller';

// Entry
$_['entry_admin']      = 'Kun Adminbrugere';
$_['entry_status']     = 'Status';
$_['entry_name'] = 'Navn';
$_['entry_message_text'] = 'Besked der vises';
$_['entry_date_start'] = 'Start Dato (ÅÅÅÅ-MM-DD)';
$_['entry_date_end'] = 'Slut Dato (ÅÅÅÅ-MM-DD)';
$_['entry_status'] = 'Status';

$_['entry_email_footer'] = 'GDPR E-mail Fodtekst';
$_['entry_email_header'] = 'GDPR E-mail Hovedtekst';
$_['entry_locations_of_other_data'] = 'Andre steder/tjenester hvor du gemmer personlige data';
$_['entry_locations_of_servers'] = 'Fysisk lokation af servere hvor du hoster din hjemmeside og andre data.';
$_['entry_max_requests_day'] = 'Maks. forespørgsler per dag';
$_['entry_pending_status'] = 'Afventende Status Tekst';
$_['entry_confirmed_status'] = 'Bekræftet Status Tekst';
$_['entry_emailed_status'] = 'E-mailet Status Tekst';
$_['entry_account_deleted_status'] = 'Konto Slettet Status Tekst';
$_['entry_data_sent'] = 'Data Sendt';
$_['entry_unpaid'] = 'Ubetalt Tekst';
$_['entry_free'] = 'Gratis Tekst';
$_['entry_rejected'] = 'Afvist Tekst';
$_['entry_fairuse'] = 'Fair Brug';
$_['entry_max_days_process'] = 'Maks. Antal Dage til at Svare';
$_['entry_right_to_be_forgotten'] = 'Aktiver Retten til at Blive Glemt Formular';

// Help
$_['help_pending_status'] = 'Navn på statussen af en GDPR forespørgsel, der endnu ikke er blevet bekræftet af kunden. Dette statusnavn vil blive vist i admin GDPR forespørgselsrapport og vist til kunden i historikken over dens GDPR forespørgsler.';
$_['help_confirmed_status'] = 'Navn på statussen af en GDPR forespørgsel, der er blevet bekræftet af kunden ved brug af e-mail konfirmationslink, men endnu ikke blevet udført (Rapport e-mailen er endnu ikke sendt). Dette statusnavn vil blive vist i admin GDPR forespørgselsrapport og vist til kunden i historikken over dens GDPR forespørgsler.';
$_['help_emailed_status'] = 'Navn på statussen af en GDPR forespørgsel der er blevet udført (Rapport e-mailen er sendt til kunden). Dette statusnavn vil blive vist i admin GDPR forespørgselsrapport og vist til kunden i historikken over dens GDPR forespørgsler.';
$_['help_account_deleted_status'] = 'Navn på statussen af en GDPR konto-fjernelsesforespørgsel der er blevet udført. Dette statusnavn vil blive vist i admin GDPR forespørgselsrapport.';
$_['help_locations_of_other_data'] = 'Nævn her alle andre lokationer og web services hvor du gemmer dine kundedata. F.eks.: Mailchimp, Google Docs, osv. Denne information vil blive inkluderet i GDPR e-mail rapporten sendt til kunden.';
$_['help_locations_of_servers'] = 'Nævn alle relevante informationer om din hosting- og serverlokation (F.eks. serverlokationsland, hostingudbyder, er hostingudbyderne GDPR-kompatibel? osv.).';
$_['help_max_requests_day'] = 'Antal forespørgsler kunden har lov til at sende pr. dag. Dette bør være et lavt nummer, for at forhindre spam-forespørgsler, angreb og unødvendig belastning af din web-server. Vi anbefaler 3-5.';
$_['help_right_to_be_forgotten'] = 'Dette vil aktivere en \'Retten til at blive glemt\'-formular hvor kunder kan automatisk slette deres konto og anonymisere deres personline data steder hvor de ikke kan slettes';

// Error
$_['error_permission'] = 'Advarsel: Du har ikke tilladelse til at ændre GDPR-modulet!';

// Text
$_['text_module']      = 'Moduler';
$_['text_success']     = 'Succes: Du har ændret GDPR-modulet!';
$_['text_edit']        = 'Rediger GDPR-modulet';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Store Policy Acceptance';
$_['entry_forms_are_private'] = 'GDPR Forms Require Login';

$_['help_store_policy_acceptance'] = 'If set to yes, every time customer accepts your terms on the registration page or in the checkout, this will be recorded in the database. IMPORTANT: if you set it to yes please make sure you checkout is working correctly!';
$_['help_forms_are_private'] = 'If set to yes, GDPR request can only be submitted by a logged in customer.';
