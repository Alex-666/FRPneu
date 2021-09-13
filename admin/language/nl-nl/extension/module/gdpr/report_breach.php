<?php

// Buttons
$_['button_add_breach'] = 'Voeg datalek incident toe';
$_['button_download_pdf'] = 'Download kennisgevingsbrief in PDF-formaat';
$_['button_email'] = 'E-mails verzenden';
$_['button_generate_customer_notifications'] = 'Genereer klantmeldingen';
$_['button_preview_customer_notification'] = 'Voorbeeld';
$_['button_save_breach'] = 'Opslaan';
$_['button_send'] = 'Stuur kennisgevingsbrief per e-mail';

// Columns
$_['column_action'] = 'Actie';
$_['column_bcc_to'] = 'BCC\'ed aan';
$_['column_breach_id'] = 'Lek ID';
$_['column_customer_email'] = 'Email';
$_['column_customer_id'] = 'Klant ID';
$_['column_customer_name'] = 'Naam';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'Datum toegevoegd';
$_['column_date_of_breach']	= 'Datum van lek';
$_['column_date_of_discovery'] = 'Datum van ontdekking';
$_['column_date_updated'] = 'Laatste update';
$_['column_number_of_accounts_affected'] = 'Aantal betrokken accounts';
$_['column_breach_name'] = 'Lek naam';
$_['column_sent_to'] = 'E-mail verzonden naar';
$_['column_status'] = 'functionaris voor gegevensbescherming Notification Status';
$_['column_status_customers'] = 'Klanten melding status';
$_['column_status_notification'] = 'klanten E-mail status';

// Heading
$_['heading_title']        = 'Melding van schending van gegevensbeveiliging';
$_['heading_title_customers']        = 'Melding van schending van gegevensbeveiliging';
$_['heading_title_customers_send_emails'] = 'Stuur kennisgevingen e-mails naar klanten';
$_['heading_detailed'] = 'Melding van gegevensbeveiliging schending aan de functionaris voor gegevensbescherming';
$_['heading_detailed_customers'] = 'Kennisgeving van schending van gegevensbeveiliging aan klanten';
$_['heading_detailed_customers_send_emails'] = 'Stuur berichten handmatig naar klanten in batches. Deze methode wordt niet aanbevolen als u een groot aantal klanten heeft.';

// Text
$_['text_breach_email_subject'] = 'Melding inbreuk op persoonsgegevens';
$_['text_commissioner'] = 'Functionaris voor gegevensbescherming';
$_['text_customers'] = 'klanten';
$_['text_data_breach'] = 'Datalek rapportage';
$_['text_data_breach_commissioner_list'] = 'Geregistreerde incidenten';
$_['text_data_breach_commissioner_form'] = 'Voeg nieuw incident toe';
$_['text_data_breach_customer_list'] = 'Genereer meldingen';
$_['text_data_breach_customer_emails'] = 'Geregistreerde e-mailmeldingen';
$_['text_data_breach_customer_emails_send'] = 'Stuur e-mails';
$_['text_data_breach_customer_csv'] = 'Download klantenlijst';

$_['text_email_commissioner_body'] = 'Hierbij vindt u een brief met betrekken tot het melden van een datalek.';
$_['text_email_commissioner_subject'] = 'Melding van schending van gegevensbeveiliging';
$_['text_section_commissioner'] = 'Informatie voor functionaris voor gegevensbescherming';
$_['text_section_customer'] = 'Informatie voor klanten';
$_['text_section_general'] = 'Algemene informatie';
$_['text_success']         = 'Uw bericht is succesvol verzonden!';
$_['text_success_saved_record']         = 'Uw datalek melding record is succesvol opgeslagen. Let op: het is nog niet verstuurd, download hiervoor een PDF-versie van de brief of gebruik de \'Email functionaris voor gegevensbescherming\' knop';
$_['text_success_generate_customer_notifications']         = 'Uw klantmeldingen zijn gegenereerd (houd er rekening mee dat er nog niets is gemaild)!';
$_['text_sent']            = 'Uw bericht is succesvol verzonden naar %s of %s ontvangers!';
$_['text_notifications_emailed'] = 'Gemailde meldingen:';
$_['text_notifications_pending'] = 'Meldingen in behandeling:';
$_['text_total_customers'] = 'Het totale aantal klantaccounts in uw systeem is';


$_['text_from']         = 'Van:';
$_['text_to']         = 'Aan:';
$_['text_date']         = 'Datum';
$_['text_default']         = 'Standaard';
$_['text_email'] = 'Verstuur e-mails';
$_['text_header_cron'] = 'CRON taak (aanbevolen)';
$_['text_header_log'] = 'Log';
$_['text_hour'] = 'uur';
$_['text_hours'] = 'uren';
$_['text_instructions']    = 'Standaard';
$_['text_instructions_customers_send_emails'] = 'Met dit formulier kunt u batches e-mailmeldingen handmatig naar uw klanten verzenden. U moet instellingen selecteren die geschikt zijn voor uw hosting / server. Houd er rekening mee dat als u serverlimieten voor het verzenden van e-mails laag zijn, dit lang kan duren om te voltooien. Daarom raden we aan om een cron-taak in te stellen die dit automatisch voor u uitvoert (details hieronder beschikbaar). Dit script werkt alleen voor meldingen die nog niet zijn verzonden. Elke melding die naar een klant wordt gemaild, wordt gemarkeerd als verzonden in de database, zodat u kunt bijhouden welke meldingen zijn verzonden.';
$_['text_instructions_log'] = 'Het logboek van e-mails die aan klanten zijn verzonden, is te vinden in de Opencart log:';
$_['text_instructions_cron'] = 'Om uw server toe te staan uw e-mails automatisch te verzenden, stelt u een cron-taak in met de volgende URL waarbij \'max_runtime\' de maximale tijd is waarop de server het script binnen enkele minuten mag uitvoeren en \'batch_size\' het maximale aantal e-mails is dat kan binnen een uur worden verzonden:';
$_['text_minutes'] = 'minuten';
$_['text_newsletter']      = 'Alle nieuwsbrief-abonnees';
$_['text_customer_all']    = 'Alle klanten';
$_['text_list'] = 'Opgeslagen datalekken (Functionaris voor gegevensbescherming)';
$_['text_list_customers'] = 'Opgeslagen datalekken (Klanten)';
$_['text_product']         = 'Producten';
$_['text_add_breach']         = 'Voeg datalek incident toe';
$_['text_return'] = 'Terug';
$_['text_save_breach']         = 'Opslaan';
$_['text_signature'] = 'Met vriendelijke groet,';
$_['text_status_generated']         = 'Gegenereerd';
$_['text_status_pending']         = 'Bezig';
$_['text_status_sent']         = 'Verzonden';
$_['text_status_unknown']         = 'Onbekend';
$_['text_status_failed']         = 'Ongeldige E-mail';
$_['text_success_email_batch'] = 'E-mails worden nu door uw server verzonden. Bekijk de lijst met meldingen in:';

// functionaris voor gegevensbescherming letter
$_['text_report_to_commissioner_intro'] = 'Hierbij melden we een inbreuk op de gegevensbeveiliging waarbij mogelijk persoonlijke gegevens zijn betrokken.';
$_['text_report_to_commissioner_contact'] = 'Ons aanspreekpunt met betrekking tot dit incident is:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = 'Op %s hebben we een datalek ontdekt zoals hieronder beschreven:';
$_['text_report_to_commissioner_data_exposed'] = 'De gegevens kunnen persoonlijke informatie bevatten zoals:';
$_['text_report_to_commissioner_actions_taken'] = 'We hebben tot nu toe de volgende stappen ondernomen om dit incident te verhelpen:';
$_['text_report_to_commissioner_ending'] = 'We voeren een grondig onderzoek uit van de mogelijk getroffen systemen en zullen de toezichthoudende autoriteit , indien er zich belangrijke ontwikkelingen voordoen, hiervan op de hoogte stellen. We implementeren aanvullende beveiligingsmaatregelen om herhaling van een dergelijke aanval te voorkomen en om de privacy van onze klanten te beschermen';

$_['text_send_breach_notification'] = 'Verzend melding van schending';

// Entry
$_['entry_address_commissioner'] = 'Functionaris voor gegevensbescherming (Postadres)';
$_['entry_address_store'] = 'Winkel (Postadres)';
$_['entry_batch_size'] = 'batchgrootte';
$_['entry_breach_notification_status'] = 'Status';
$_['entry_breach_customer_email_notification_status'] = 'Status e-mailkennisgeving';
$_['entry_contact_details_of_person_reporting']       = 'Contactgegevens voor functionaris voor gegevensbescherming';
$_['entry_contact_email']       = 'Contact e-mail voor klanten';
$_['entry_customer_email']       = 'E-mail';
$_['entry_customer_group'] = 'Klantengroep';
$_['entry_customer']       = 'Klanten';
$_['entry_date_added']       = 'Datum toegevoegd';
$_['entry_date_of_breach']       = 'Datum(s) van het datalek (indien bekend)';
$_['entry_date_of_discovery']       = 'Datum waarop het incident werd ontdekt';
$_['entry_email_bcc']       = 'BCC';
$_['entry_email_commissioner'] = 'Functionaris voor gegevensbescherming (e-mail)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Maximale looptijd';
$_['entry_message_action']        = 'Korte beschrijving van de ondernomen acties sinds inbreuk werd ontdekt (functionaris voor gegevensbescherming)';
$_['entry_message_action_customers']        = 'Korte beschrijving van de ondernomen acties (Klanten)';
$_['entry_message_incident']       = 'Korte beschrijving van de schending (functionaris voor gegevensbescherming)';
$_['entry_message_incident_customers']       = 'Korte beschrijving van de schending (Klanten)';
$_['entry_name']       = 'Naam incident (voor uw referentie)';
$_['entry_number_of_accounts_affected']       = 'Aantal getroffen accounts (wanneer bekend)';
$_['entry_store']          = 'Van';
$_['entry_subject']        = 'Onderwerp (functionaris voor gegevensbescherming E-mail)';
$_['entry_subject_customers']        = 'Onderwerp (Klanten E-mails)';
$_['entry_to']             = 'Aan';

// Help
$_['help_address_commissioner'] = 'Volledig postadres van de functionaris voor gegevensbescherming. Dit wordt afgedrukt in de koptekst voor de kennisgeving van een datalek';
$_['help_address_store'] = 'Volledig postadres van de winkel die de schending meldt. Dit wordt afgedrukt in de kop van de brief';
$_['help_batch_size'] = 'Het aantal e-mails dat binnen één uur kunnen worden verzonden. Dit is afhankelijk van uw serverconfiguratie. De meeste gedeelde servers zullen het verzenden van e-mail zwaar beperken, dus het is niet toegestaan om meer dan 100-200 e-mails per uur te verzenden.';
$_['help_contact_details_of_person_reporting'] = 'Voeg een e-mailadres en/ of telefoonnummer toe van een persoon waarmee contact kan worden opgenomen door de functionaris voor gegevensbescherming';
$_['help_contact_email'] = 'Voeg een e-mailadres toe waarmee uw klanten contact kunnen opnemen met betrekking tot het datalek.';
$_['help_data_commissioner']       = 'E-mailadres van de functionaris voor gegevensbescherming in uw rechtsgebied';
$_['help_date_of_breach'] = 'Als u de exacte datum niet weet, geef dan een geschat tijdsbestek op';
$_['help_date_of_discovery'] = 'Datum waarop u ontdekte dat uw systeem is geschonden';
$_['help_email_bcc']       = 'E-mail addresses to be carbon copied on the breach notification';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Maximale tijd waarbinnen het e-mailverzending script moet worden uitgevoerd.';
$_['help_message_action'] = 'Beschrijf alle acties die u hebt ondernomen sinds de inbreuk werd ontdekt. Deze tekst zal worden opgenomen in de brief aan de functionaris voor gegevensbescherming.';
$_['help_message_action_customers'] = 'Beschrijf alle acties die u hebt ondernomen sinds de inbreuk werd ontdekt die relevant zijn voor uw klanten. Deze tekst zal worden opgenomen in de e-mails aan klanten.';
$_['help_message_incident'] = 'Beschrijf de inbreuk in details, wie heeft toegang tot de gegevens, hoe hebben ze het gedaan, hoe is het ontdekt, etc. Deze tekst zal worden opgenomen in de brief aan de functionaris voor gegevensbescherming.';
$_['help_message_incident_customers'] = 'Beschrijf de schending met alle details die relevant zijn voor uw klanten. Deze tekst zal worden opgenomen in de e-mails aan klanten.';
$_['help_name'] = 'Deze naam is alleen voor de eigenaar van de winkel/ admin en wordt niet bekendgemaakt aan de functionaris voor gegevensbescherming of klanten';
$_['help_number_of_accounts_affected'] = 'Geef aan hoeveel accounts (klanten/ betrokkenen) zijn getroffen door de schending';
$_['help_subject']       = 'Onderwerp van de nofification e-mail/ brief aan de functionaris voor gegevensbescherming';
$_['help_subject_customers']       = 'Onderwerp van de e-mails met meldingen aan klanten';

// Error
$_['error_address_commissioner']        = 'Adres van de functionaris voor gegevensbescherming is verplicht!';
$_['error_address_store']        = 'Winkeladres is verplicht!';
$_['error_contact_details_of_person_reporting']        = 'Contactgegevens van de persoon die het rapport verzendt, zijn verplicht!';
$_['error_customer_notification_add'] = 'Kon geen klantmeldingen toevoegen';
$_['error_customer_notification_existing'] = 'Er is al een kennisgeving voor dit datalek!';
$_['error_data_commissioner']        = 'Functionaris voor gegevensbescherming e-mail is verplicht!';
$_['error_date_of_breach']        = 'Datum van overtreding is vereist!';
$_['error_date_of_discovery']        = 'Datum van ontdekking van de inbreuk is vereist!';
$_['error_email_batch'] = 'Uw e-mailmeldingen kunnen niet worden verzonden. Probeer het opnieuw.';
$_['error_permission']     = 'Waarschuwing: u bent niet gemachtigd om e-mails met betrekking tot datalekken te verzenden!';
$_['error_subject']        = 'Meldingsplicht voor e-mail vereist!';
$_['error_mail_bcc'] = 'E-mailformaat onjuist, geef e-mailadressen op als een door komma\'s gescheiden lijst';
$_['error_mail_commissioner'] = 'E-mail voor functionaris voor gegevensbescherming heeft een incorrect formaat.';
$_['error_message_action']        = 'Samenvatting van de acties die zijn ondernomen na de inbreuk is vereist!';
$_['error_message_incident']        = 'Beschrijving van het incident/ overtreding is vereist!';
$_['error_missing_commissioner_email'] = 'Functionaris voor gegevensbescherming email is een verplicht veld!';
$_['error_saving_breach_notification_failed'] = 'We kunnen uw melding voor een datalek niet opslaan. Probeer het opnieuw';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'data_breach_notification';
