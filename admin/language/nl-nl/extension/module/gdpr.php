<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'GDPR instellingen';
$_['text_gdpr_report']             = 'GDPR aanvraag geschiedenis';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Opslaan';
$_['button_cancel'] = 'Annuleer';

// Entry
$_['entry_admin']      = 'Alleen beheerders';
$_['entry_status']     = 'Status';
$_['entry_name'] = 'Naam';
$_['entry_message_text'] = 'Bericht om weer te geven';
$_['entry_date_start'] = 'Start datum (JJJJ-MM-DD)';
$_['entry_date_end'] = 'Eind datum (JJJJ-MM-DD)';
$_['entry_status'] = 'Status';

$_['entry_email_footer'] = 'GDPR email voet text';
$_['entry_email_header'] = 'GDPR email kop text';
$_['entry_locations_of_other_data'] = 'Andere locaties/ diensten waar u persoonlijke gegevens opslaat';
$_['entry_locations_of_servers'] = 'Fysieke locaties van servers waarop u uw website en andere gegevens host.';
$_['entry_max_requests_day'] = 'Maximaal aantal aanvragen per dag';
$_['entry_pending_status'] = 'Status "In behandeling" tekst';
$_['entry_confirmed_status'] = 'Status "Bevestigd" tekst';
$_['entry_emailed_status'] = 'Status "Verzonden via email" tekst';
$_['entry_account_deleted_status'] = 'Status "Account verwijderd" tekst';
$_['entry_data_sent'] = 'Gegevens verzonden';
$_['entry_unpaid'] = 'Onbetaald tekst';
$_['entry_free'] = 'Vrije text';
$_['entry_rejected'] = 'Geweigerd tekst';
$_['entry_fairuse'] = 'Fair use';
$_['entry_max_days_process'] = 'Maximum aantal dagen om te reageren';
$_['entry_right_to_be_forgotten'] = 'Schakel het "Recht om te worden vergeten" formulier in';

// Help
$_['help_pending_status'] = 'Naam van de status van een GDPR-aanvraag die nog niet door de klant is bevestigd. Deze statusnaam wordt weergegeven in het GDPR-aanvraagrapport van de beheerder en aan de klant getoond in de geschiedenis van hun GDPR-verzoeken.';
$_['help_confirmed_status'] = 'Naam van de status van een GDPR-aanvraag die is bevestigd door de klant via de link voor het bevestigen van de e-mail, maar die nog niet is verwerkt (de e-mail van het rapport is nog niet verzonden). Deze statusnaam wordt weergegeven in het GDPR-aanvraagrapport van de beheerder en aan de klant wordt getoond in de geschiedenig van hun GDPR-verzoeken.';
$_['help_emailed_status'] = 'Naam van de status van een GDPR-aanvraag die is verwerkt/ voltooid (de rapportmail is naar de klant verzonden). Deze statusnaam wordt weergegeven in het GDPR-aanvraagrapport van de beheerder en aan de klant getoond in de geschiedenis van hun GDPR-verzoeken.';
$_['help_account_deleted_status'] = 'Naam van de status van een verwijderingsverzoek van een GDPR-account die zijn verwerkt/ voltooid. Deze statusnaam wordt weergegeven in het GDPR-verzoekrapport van de beheerder.';
$_['help_locations_of_other_data'] = 'Maak een lijst van alle andere locaties en webservices waar u persoonlijke gegevens van klanten opslaat. Voorbeelden: Mailchimp, Google documenten, etc. Deze informatie wordt opgenomen in het GDPR-e-mailrapport dat naar de klant wordt verzonden.';
$_['help_locations_of_servers'] = 'Maak een lijst van alle relevante informatie over uw hosting- en serverlocaties (bijvoorbeeld: serverlocatielanden, hostingproviders, zijn de hostingproviders GDPR-compliant? Etc.).';
$_['help_max_requests_day'] = 'Aantal verzoeken dat de klant per dag mag indienen. Dit moet op een laag getal worden ingesteld om spamaanvragen, aanvallen en onnodige belasting van uw webserver te voorkomen. We raden een waarde tussen 3-5 aan.';
$_['help_right_to_be_forgotten'] = 'Hierdoor wordt een \'Recht om te worden vergeten\'-formulier ingeschakeld, waarin klanten automatisch hun account kunnen verwijderen en hun persoonlijke gegevens kunnen anonimiseren wanneer deze niet kunnen worden verwijderd.';

// Error
$_['error_permission'] = 'Waarschuwing: U heeft geen rechten om de instellingen van de GDPR module te wijzigen!';

// Text
$_['text_module']      = 'Modules';
$_['text_success']     = 'Succes: Instellingen gewijzigd!';
$_['text_edit']        = 'Bewerk';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Acceptatie privacy beleid';
$_['entry_forms_are_private'] = 'GDPR-formulieren vereisen inloggen';

$_['help_store_policy_acceptance'] = 'Indien ingesteld op \'Ja\', wordt iedere keer dat de klant op de registratiepagina of in de winkelwagen uw voorwaarden accepteert dit vastgelegd in de database. BELANGRIJK: als u het instelt op \'Ja\', controleer dan of de afrekenpagina correct werkt!';
$_['help_forms_are_private'] = 'Indien ingesteld op \'Ja\' dan kan het GDPR-verzoek alleen worden ingediend door een ingelogde klant.';
