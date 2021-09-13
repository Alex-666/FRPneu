<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'GDPR Inställningar';
$_['text_gdpr_report']             = 'GDPR Begäran Historia';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Spara';
$_['button_cancel'] = 'Ångra';

// Entry
$_['entry_admin']      = 'Endast administratörer';
$_['entry_status']     = 'Status';
$_['entry_name'] = 'Namn';
$_['entry_message_text'] = 'Meddelande att visa ';
$_['entry_date_start'] = 'Start Datum (YYYY-MM-DD)';
$_['entry_date_end'] = 'Slut Datum (YYYY-MM-DD)';
$_['entry_status'] = 'Status';

$_['entry_email_footer'] = 'GDPR E-Post sidfot Text';
$_['entry_email_header'] = 'GDPR E-Post rubrik Text';
$_['entry_locations_of_other_data'] = 'Andra platser / tjänster där du lagrar personuppgifter';
$_['entry_locations_of_servers'] = 'Fysiska platser för servrar där du värd för din webbplats och annan information. ';
$_['entry_max_requests_day'] = 'Max antal förfrågningar per dag ';
$_['entry_pending_status'] = "Väntar statustext";
$_['entry_confirmed_status'] = 'Bekräftad statustext ';
$_['entry_emailed_status'] = 'E-postad Status Text';
$_['entry_account_deleted_status'] = 'Konto raderad statustext';
$_['entry_data_sent'] = 'Data Skickat';
$_['entry_unpaid'] = 'Obetald text';
$_['entry_free'] = 'Fri Text';
$_['entry_rejected'] = 'Avvisad Text';
$_['entry_fairuse'] = 'Rättvis användning';
$_['entry_max_days_process'] = 'Max antal dagar att svara';
$_['entry_right_to_be_forgotten'] = 'Aktivera rätt att glömma formuläret';

// Help
$_['help_pending_status'] = 'Namnet på statusen för en GDPR-förfrågan som ännu inte har bekräftats av kunden. Detta statusnamn visas i admin GDPR-förfrågningsrapporten och avslöjas för kunden i den historiska posten för sina GDPR-förfrågningar.';
$_['help_confirmed_status'] = 'Namnet på statusen för en GDPR-förfrågan som har bekräftats av kunden med länken för e-postbekräftelse men som inte har behandlats än (rapportens e-postmeddelande har ännu inte skickats). Detta statusnamn visas i admin GDPR-förfrågningsrapporten och avslöjas för kunden i den historiska posten för sina GDPR-förfrågningar.';
$_['help_emailed_status'] = 'Namnet på statusen för en GDPR-förfrågan som har bearbetats / slutförts (rapporten har skickats till kunden). Detta statusnamn visas i admin GDPR-förfrågningsrapporten och avslöjas för kunden i den historiska posten för sina GDPR-förfrågningar.';
$_['help_account_deleted_status'] = 'Namnet på statusen för en begäran om borttagning av GDPR-konto som har bearbetats / genomförts. Detta statusnamn visas i admin GDPR-förfrågningsrapporten.';
$_['help_locations_of_other_data'] = 'Lista alla andra platser och webbtjänster där du lagrar kundens personuppgifter här. Exempel: Mailchimp, Google Docs, etc. Denna information kommer att inkluderas i GDPR-e-postrapporten skickad till kunden.';
$_['help_locations_of_servers'] = 'Lista all relevant information om dina webbhotell och serverplatser (Till exempel: Servrar, Länder, Hosting-leverantörer, är värdleverantörerna GDPR-kompatibla? Etc.).';
$_['help_max_requests_day'] = 'Antal förfrågningar som kunden får lämna in per dag. Detta bör vara inställt på ett lågt antal för att förhindra spamförfrågningar, attacker och onödig belastning på din webbserver. Vi rekommenderar ett värde mellan 3-5 högst. ';
$_['help_right_to_be_forgotten'] = 'Detta kommer att möjliggöra en form \ "Rätt att vara glömd \" där kunder automatiskt kan ta bort sitt konto och anonymisera deras personuppgifter där den inte kan raderas';

// Error
$_['error_permission'] = 'Varning: Du har inte behörighet att ändra GDPR-modulen!';

// Text
$_['text_module']      = 'Moduler';
$_['text_success']     = 'Framgång: Du har ändrat GDPR-modulen!';
$_['text_edit']        = 'Redigera GDPR Modul';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Store Policy Acceptance';
$_['entry_forms_are_private'] = 'GDPR Forms Require Login';

$_['help_store_policy_acceptance'] = 'If set to yes, every time customer accepts your terms on the registration page or in the checkout, this will be recorded in the database. IMPORTANT: if you set it to yes please make sure you checkout is working correctly!';
$_['help_forms_are_private'] = 'If set to yes, GDPR request can only be submitted by a logged in customer.';
