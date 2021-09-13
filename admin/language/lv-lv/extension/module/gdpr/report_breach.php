<?php

// Buttons
$_['button_add_breach'] = 'Pievienojiet Datu Drošības Pārkāpuma Incidentu';
$_['button_download_pdf'] = 'Lejupielādējiet paziņojuma vēstuli PDF formātā';
$_['button_email'] = 'Sūtīt e-pastus';
$_['button_generate_customer_notifications'] = 'Izveidot paziņojumus Klientiem';
$_['button_preview_customer_notification'] = 'Skatīt';
$_['button_save_breach'] = 'Saglabāt';
$_['button_send'] = 'Sūtīt paziņojuma vēstuli uz e-pastu';

// Columns
$_['column_action'] = 'Darbība';
$_['column_bcc_to'] = 'BCC';
$_['column_breach_id'] = 'Pārkāpuma ID';
$_['column_customer_email'] = 'E-pasts';
$_['column_customer_id'] = 'Klienta ID';
$_['column_customer_name'] = 'Vārds';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'Pievienošanas Datums';
$_['column_date_of_breach']	= 'Pārkāpuma Datums';
$_['column_date_of_discovery'] = 'Pārkāpuma Atklāšanas Datums';
$_['column_date_updated'] = 'Pēdējās izmaiņas';
$_['column_number_of_accounts_affected'] = 'Pārkāpuma skarto kontu skaits';
$_['column_breach_name'] = 'Pārkāpuma Nosaukums';
$_['column_sent_to'] = 'Nosūtīts';
$_['column_status'] = 'Datu Drošības Iestādes Paziņojuma Stāvoklis';
$_['column_status_customers'] = 'Paziņojuma Klientiem Stāvoklis';
$_['column_status_notification'] = 'Klienta e-pasta Stāvoklis';

// Heading
$_['heading_title']        = 'Paziņojums par Datu Drošības Pārkāpumu';
$_['heading_title_customers']        = 'Paziņojums par Datu Drošības Pārkāpumu';
$_['heading_title_customers_send_emails'] = 'Sūtīt Paziņojumus uz Klientu e-pastiem';
$_['heading_detailed'] = 'Paziņojums par Datu Drošības Pārkāpumu Datu Drošības Iestādei';
$_['heading_detailed_customers'] = 'Datu Drošības Pārkāpuma Paziņošana Klientiem';
$_['heading_detailed_customers_send_emails'] = 'Sūtīt Paziņojumus Klientiem manuāli partijās. Šī metode nav ieteicama, ja jums ir liels klientu skaits.';

// Text
$_['text_breach_email_subject'] = 'Paziņojums par Personas Datu Pārkāpumu';
$_['text_commissioner'] = 'Datu Drošības Iestāde';
$_['text_customers'] = 'Klienti';
$_['text_data_breach'] = 'Datu Pārkāpumu Pārskati';
$_['text_data_breach_commissioner_list'] = 'Ieraktītie Incidenti';
$_['text_data_breach_commissioner_form'] = 'Pievienot Jaunu Incidentu';
$_['text_data_breach_customer_list'] = 'Izveidot Paziņojumus';
$_['text_data_breach_customer_emails'] = 'Ierakstītie e-pasta Paziņjumi';
$_['text_data_breach_customer_emails_send'] = 'Sūtīt e-pastus';
$_['text_data_breach_customer_csv'] = 'Lejupielādēt Klientu Sarakstu';

$_['text_email_commissioner_body'] = 'Lūdzu, pievienojiet paziņojumu par pārkāpumu.';
$_['text_email_commissioner_subject'] = 'Paziņojums par Datu Drošības Pārkāpumu';
$_['text_section_commissioner'] = 'Informācija Datu Drošības Iestādei';
$_['text_section_customer'] = 'Informācija Klientiem';
$_['text_section_general'] = 'Pamatinformācija';
$_['text_success']         = 'Jūsu ziņa ir veiksmīgi nosūtīta!';
$_['text_success_saved_record']         = 'Jūsu pārkāpuma paziņojumu ieraksts ir veiksmīgi saglabāts. Lūdzu, ņemiet vērā, ka tas vēl nav nosūtīts, lai to izdarītu, lūdzu, lejupielādējiet vēstules PDF versiju, izmantojot pogu \'Nosūtīt e-pastu Datu Drošības Iestādei \'';
$_['text_success_generate_customer_notifications']         = 'Jūsu klientu paziņojumi ir izveidoti (lūdzu, ņemiet vērā, ka nekas vēl nav nosūtīts uz e-pastiem)!';
$_['text_sent']            = 'Jūsu ziņa ir veiksmīgi nosūtīta %s no %s klientiem!';
$_['text_notifications_emailed'] = 'Jūsu Noūtītie Paziņojumi uz e-pastiem:';
$_['text_notifications_pending'] = 'Jūsu Neapstiprinātie Paziņojumi:';
$_['text_total_customers'] = 'Kopējais klientu kontu skaits jūsu sistēmā ir';


$_['text_from']         = 'No:';
$_['text_to']         = 'Kam:';
$_['text_date']         = 'Datums';
$_['text_default']         = 'Noklusējums';
$_['text_email'] = 'Sūtīt e-pastus';
$_['text_header_cron'] = 'CRON Uzstādījumi (ieteicams)';
$_['text_header_log'] = 'Log';
$_['text_hour'] = 'stunda';
$_['text_hours'] = 'stundas';
$_['text_instructions']    = 'Noklusējums';
$_['text_instructions_customers_send_emails'] = 'Šī veidlapa ļauj jums manuāli sūtīt e-pasta paziņojumus pakās saviem klientiem. Jums nepieciešams izvēlēties iestatījumus, kas ir piemēroti jūsu hostingam / serverim. Lūdzu, ņemiet vērā, ka, ja servera ierobežojumi e-pasta ziņojumu sūtīšanai ir zemi, tas var aizņemt daudz laika, lai pabeigtu. Tāpēc mēs iesakām iestatīt cron uzdevumu, kas to automātiski palaiž (informācija pieejama zemāk). Šis skripts darbosies tikai ar paziņojumiem, kas vēl nav nosūtīti. Katrs paziņojums, kas klientam tiek nosūtīts pa e-pastu, tiks atzīmēts kā nosūtīts datu bāzē, lai jūs varētu izsekot, kuri paziņojumi tika nosūtīti.';
$_['text_instructions_log'] = 'E-pasta ziņojumu sūtīšana klientiem ir atrodama Opencart log mapē:';
$_['text_instructions_cron'] = 'Lai ļautu serverim automātiski nosūtīt e-pasta ziņojumus, lūdzu, konfigurējiet cron uzdevumu, izmantojot šādu URL, kur \'max_runtime \' ir maksimālais laiks ko serverim ir atļauts palaist skriptu minūtēs, un \'batch_size \' ir maksimālais e-pasta ziņojumu skaits ko var nosūtīt vienā stundā:';
$_['text_minutes'] = 'minūtes';
$_['text_newsletter']      = 'Visi, kas abonējuši Jaunumus';
$_['text_customer_all']    = 'Visi Klienti';
$_['text_list'] = 'Ierakstīti Datu Pārkāpumi (Datu Drošības Iestāde)';
$_['text_list_customers'] = 'Ierakstīti Datu Pārkāpumi (Klienti)';
$_['text_product']         = 'Preces';
$_['text_add_breach']         = 'Pievienojiet Datu Pārkāpumu Incidentu';
$_['text_return'] = 'Atpakaļ';
$_['text_save_breach']         = 'Saglabāt';
$_['text_signature'] = 'Ar cieņu,';
$_['text_status_generated']         = 'Izveidots';
$_['text_status_pending']         = 'Procesā';
$_['text_status_sent']         = 'Nosūtīts';
$_['text_status_unknown']         = 'Nezināms';
$_['text_status_failed']         = 'Nepareizs E-pasts';
$_['text_success_email_batch'] = 'E-pastu tagad nosūtīs jūsu serveris. Lūdzu, pārbaudiet paziņojumu sarakstu:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'Ar šo mēs ziņojam par datu aizsardzības pārkāpuma gadījumiem, kas var ietvert personisku informāciju.';
$_['text_report_to_commissioner_contact'] = 'Mūsu kontapersona saistībā ar šo incidentu ir:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = '%s mēs esam atklājuši datu pārkāpumu, kas aprakstīts zemāk:';
$_['text_report_to_commissioner_data_exposed'] = 'Skartie dati var ietvert personisko informāciju, piemēram:';
$_['text_report_to_commissioner_actions_taken'] = 'Līdz šim mēs esam veikuši šādus pasākumus, lai novērstu šo incidentu:';
$_['text_report_to_commissioner_ending'] = 'Mēs veicam potenciāli skarto sistēmu visaptverošu pārskatu un paziņosim Datu Drošības Iestādei, ja ir kādi nozīmīgi notikumi. Mēs īstenojam papildu drošības pasākumus kas paredzēti, lai novērstu šāda uzbrukuma atkārtošanos un aizsargātu mūsu klientu privātumu';

$_['text_send_breach_notification'] = 'Sūtīt Pārkāpuma Paziņojumu';

// Entry
$_['entry_address_commissioner'] = 'Datu Drošības Iestādes (Pasta Adrese)';
$_['entry_address_store'] = 'Veikals (Pasta Adrese)';
$_['entry_batch_size'] = 'Partijas lielums';
$_['entry_breach_notification_status'] = 'Stāvoklis';
$_['entry_breach_customer_email_notification_status'] = 'E-pasta paziņojuma statuss';
$_['entry_contact_details_of_person_reporting']       = 'Kontaktinformācija Datu Drošības Iestādei';
$_['entry_contact_email']       = 'E-pasts kontaktiem (priekš klientiem)';
$_['entry_customer_email']       = 'E-pasts';
$_['entry_customer_group'] = 'Pircēju grupa';
$_['entry_customer']       = 'Pircējs';
$_['entry_date_added']       = 'Pievienošanas Datums';
$_['entry_date_of_breach']       = 'Pārkāpuma Datums(i) (ja zināms)';
$_['entry_date_of_discovery']       = 'Datums kad incidents tika atklāts';
$_['entry_email_bcc']       = 'BCC';
$_['entry_email_commissioner'] = 'Datu Drošības Iestāde (e-pasts)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Maksimālais izpildes laiks';
$_['entry_message_action']        = 'Īss apraksts par jebkurām darbībām kopš pārkāpums tika atklāts (Datu Drošības Iestādei)';
$_['entry_message_action_customers']        = 'Īss apraksts par veiktajām darbībām (Klientiem)';
$_['entry_message_incident']       = 'Īss pārkāpuma apraksts (Datu Drošības Iestādei)';
$_['entry_message_incident_customers']       = 'Īss pārkāpuma apraksts (Klientiem)';
$_['entry_name']       = 'Incidenta nosaukums (jūsu atsaucei)';
$_['entry_number_of_accounts_affected']       = 'Skarto kontu skaits (ja zināms)';
$_['entry_store']          = 'No';
$_['entry_subject']        = 'Tēma (Datu Drošības Iestādes E-pastam)';
$_['entry_subject_customers']        = 'Tēma (Klientu E-pastiem)';
$_['entry_to']             = 'Kam';

// Help
$_['help_address_commissioner'] = 'Datu Drošības Iestādes pilna pasta adrese. Tas tiks izdrukāts pārkāpuma paziņojuma vēstules galvenē';
$_['help_address_store'] = 'Jūsu veikala pilnā pasta adrese kas ziņo par pārkāpumu. Tas tiks drukāts vēstules galvenē';
$_['help_batch_size'] = 'E-pasta ziņojumu skaits, kurus var nosūtīt vienas stundas laikā. Tas ir atkarīgs no jūsu servera konfigurācijas. Visbiežāk kopīgotie serveri to ievērojami ierobežos, tāpēc jums netiks atļauts sūtīt vairāk kā 100-200 e-pasta ziņojumu stundā.';
$_['help_contact_details_of_person_reporting'] = 'Pievienojiet atbildigās personas e-pasta adresi un / vai tālruņa numuru pa kuru var sazināties Datu Drošības Iestādi';
$_['help_contact_email'] = 'Pievienojiet e-pasta adresi pa kuru jūsu klienti var sazināties saistībā ar datu pārkāpumu.';
$_['help_data_commissioner']       = 'E-pasta adrese Datu Drošības Iestādei, kas atrodas Jūsu jurisdikcijā ';
$_['help_date_of_breach'] = 'Ja Jūs nezināt precīzu datumu, lūdzu norādiet aptuveno laika diapazonu';
$_['help_date_of_discovery'] = 'Datums kad atklājāt, ka jūsu sistēma ir noticis pārkāpums';
$_['help_email_bcc']       = 'E-pasta adreses, kuras tiks pārkopētas uz paziņojumu par pārkāpumu';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Maksimālais laiks, e-pasta sūtīšanas skripta darbībai.';
$_['help_message_action'] = 'Aprakstiet visas darbības, kuras esat veicis kopš pārkāpuma atklāšanas. Šis teksts tiks iekļauts vēstulē Datu Drošības Iestādei.';
$_['help_message_action_customers'] = 'Aprakstiet visas darbības kuras esat veicis kopš pārkāpuma atklāšanas, kas attiecas uz jūsu Klientiem. Šis teksts tiks iekļauts e-pasta ziņojumos klientiem.';
$_['help_message_incident'] = 'Sīki raksturojiet pārkāpumu, kas piekļuva datiem, kā viņi to izdarīja, kā to atklāja utt. Šis teksts tiks iekļauts vēstulē Datu Drošības Iestādei.';
$_['help_message_incident_customers'] = 'Aprakstiet pārkāpumu ar informāciju, kas attiecas uz jūsu Klientiem. Šis teksts tiks iekļauts e-pasta ziņojumos Klientiem.';
$_['help_name'] = 'Šis nosaukums attiecas tikai uz veikala īpašnieka / administratora atsauci, tas netiks atklāts VDI vai Klientiem';
$_['help_number_of_accounts_affected'] = 'Norādiet cik daudz kontu (Klienti / Datu subjekti) pārkāpums ir ietekmējis';
$_['help_subject']       = 'Paziņojuma Tēma Datu Drošības Iestādei e-pastam/vēstulei';
$_['help_subject_customers']       = 'Paziņojuma Tēma Klientu e-pastiem';

// Error
$_['error_address_commissioner']        = 'Nepieciešama Datu Drošības Iestādes adrese!';
$_['error_address_store']        = 'Nepieciešama Veikala adrese!';
$_['error_contact_details_of_person_reporting']        = 'Personas, kas nosūta ziņojumu, kontaktinformācija ir obligāta!';
$_['error_customer_notification_add'] = 'Nevarēja pievienot paziņojumu klientiem';
$_['error_customer_notification_existing'] = 'Šim datu incidentam jau ir paziņojums';
$_['error_data_commissioner']        = 'Nepieciešama Datu Drošības Iestādes e-pasta adrese!';
$_['error_date_of_breach']        = 'Pārkāpuma datums ir obligāts!';
$_['error_date_of_discovery']        = 'Pārkāpuma atklāšanas datums ir obligāts!';
$_['error_email_batch'] = 'Jūsu e-pasta paziņijumi nav nosūtīti, lūdzu mēģiniet vēlreiz.';
$_['error_permission']     = 'UZMANĪBU: Jums nav tiesību sūtīt Pārkāpuma Paziņojumu!';
$_['error_subject']        = 'Pārkāpuma Paziņojuma e-pasta Tēma ir obligāta';
$_['error_mail_bcc'] = 'E-pasta formāts ir nepareizs, lūdzu norādiet e-pasta adreses atdalītas ar komatu';
$_['error_mail_commissioner'] = 'Nepareizs e-pasta formāts Datu Drošības Iestādei.';
$_['error_message_action']        = 'Kopsavilkums par darbībām, kas veiktas pēc pārkāpuma, ir obligāts!';
$_['error_message_incident']        = 'Apraksts par incidentu/pārkāpumu ir obligāts!';
$_['error_missing_commissioner_email'] = 'Datu Drošības Iestādes e-pasta lauks ir obligāts!';
$_['error_saving_breach_notification_failed'] = 'Mēs nevarējām saglabāt jūsu pārkāpuma paziņojumu ierakstu, lūdzu, mēģiniet vēlreiz';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'datu_parkapuma_pazinojums';
