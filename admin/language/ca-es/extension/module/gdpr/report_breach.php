<?php

// Buttons
$_['button_add_breach'] = 'Afegir Incident Vulneració Dades';
$_['button_download_pdf'] = 'Descarrega la carta de notificació en format PDF';
$_['button_email'] = 'Enviar E-mails';
$_['button_generate_customer_notifications'] = 'Generar Notificacions Clients';
$_['button_preview_customer_notification'] = 'Vista Prèvia';
$_['button_save_breach'] = 'Guardar';
$_['button_send'] = 'Enviar carta de notificació per e-mail';

// Columns
$_['column_action'] = 'Acció';
$_['column_bcc_to'] = 'Cco\'per a';
$_['column_breach_id'] = 'ID Vulneració';
$_['column_customer_email'] = 'E-mail';
$_['column_customer_id'] = 'ID Client';
$_['column_customer_name'] = 'Nom';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'Data Afegit';
$_['column_date_of_breach']	= 'Data Vulneració';
$_['column_date_of_discovery'] = 'Data Descobriment';
$_['column_date_updated'] = 'Última Actualizació';
$_['column_number_of_accounts_affected'] = 'Nombre Comptes Afectats';
$_['column_breach_name'] = 'Nom Vulneració';
$_['column_sent_to'] = 'Enviat E-mail';
$_['column_status'] = 'Estat Notificació AGPD';
$_['column_status_customers'] = 'Estat Notificació Clients';
$_['column_status_notification'] = 'Estat E-mail Client';

// Heading
$_['heading_title']        = 'Notificació de vulneració de seguretat de dades';
$_['heading_title_customers']        = 'Notificació de vulneració de seguretat de dades';
$_['heading_title_customers_send_emails'] = 'Enviar e-mails de notificació als clients';
$_['heading_detailed'] = 'Notificació de vulneració de seguretat de dades a l&#39;Agència General de Protecció de Dades';
$_['heading_detailed_customers'] = 'Notificació de vulneració de seguretat de dades als clients';
$_['heading_detailed_customers_send_emails'] = 'Envieu notificacions als clients manualment en lots. Aquest mètode no es recomana si teniu un gran nombre de clients';

// Text
$_['text_breach_email_subject'] = 'Notificació de Vulneració de Dades Personals';
$_['text_commissioner'] = 'Agència General de Protecció de Dades';
$_['text_customers'] = 'Clients';
$_['text_data_breach'] = 'Informe Vulneració de Dades';
$_['text_data_breach_commissioner_list'] = 'Incidents Registrats';
$_['text_data_breach_commissioner_form'] = 'Afegir Nou Incident';
$_['text_data_breach_customer_list'] = 'Generar Notificacions';
$_['text_data_breach_customer_emails'] = 'Notificacions d&#39;E-mail Registrades';
$_['text_data_breach_customer_emails_send'] = 'Enviar E-mails';
$_['text_data_breach_customer_csv'] = 'Descarregar Llistat de Clients';

$_['text_email_commissioner_body'] = 'Trobareu una carta de notificació de vulneració de dades adjunta.';
$_['text_email_commissioner_subject'] = 'Notificació de Vulneració de Dades';
$_['text_section_commissioner'] = 'Informació per a l&#39;Agència General de Protecció de Dades';
$_['text_section_customer'] = 'Informació per als Clients';
$_['text_section_general'] = 'Informació General';
$_['text_success']         = 'El vostre missatge s&#39;ha enviat correctament!';
$_['text_success_saved_record']         = 'El vostre registre de notificació de vulneració s&#39;ha desat correctament. Tingueu en compte que encara no s&#39;ha enviat, per fer-ho, descarregueu una versió PDF de la carta usant el botó \'E-mail AGPD \'';
$_['text_success_generate_customer_notifications']         = 'S&#39;han generat les vostres notificacions al client (tingueu en compte que encara no s&#39;ha enviat res per e-mail).';
$_['text_sent']            = 'El vostre missatge s&#39;ha enviat correctament a %s de %s destinataris!';
$_['text_notifications_emailed'] = 'Les vostres notificacions enviades:';
$_['text_notifications_pending'] = 'Les vostres notificacions pendents:';
$_['text_total_customers'] = 'El nombre total de comptes de client del vostre sistema és';


$_['text_from']         = 'De:';
$_['text_to']         = 'A:';
$_['text_date']         = 'Data';
$_['text_default']         = 'Per defecte';
$_['text_email'] = 'Enviar e-mails';
$_['text_header_cron'] = 'Configurar CRON (recomanat)';
$_['text_header_log'] = 'Registre';
$_['text_hour'] = 'hora';
$_['text_hours'] = 'hores';
$_['text_instructions']    = 'Per defecte';
$_['text_instructions_customers_send_emails'] = 'Aquest formulari us permet enviar manualment lots de notificacions per e-mail als vostres clients. Cal que seleccioneu els paràmetres adequats per al vostre allotjament/servidor. Tingueu en compte que si els límits del servidor per enviar e-mails són baixos, pot trigar molt a completar-se. Per això, us recomanem que configureu una tasca cron que funcionarà automàticament (detalls disponibles a continuació). Aquest script només funcionarà en les notificacions que encara no s&#39;han enviat. Totes les notificacions enviades per e-mail a un client es marcaran com enviades a la base de dades, de manera que es pugui seguir quines notificacions s&#39;han enviat.';
$_['text_instructions_log'] = 'El registre dels e-mails enviats als clients es pot trobar a la carpeta de registre Opencart:';
$_['text_instructions_cron'] = 'Per permetre que el vostre servidor enviï e-mails automàticament, configureu una tasca cron utilitzant l&#39;URL següent on \'max_runtime\' és el temps màxim al qual el servidor està autoritzat a executar l&#39;script en minuts i \'batch_size\' és la quantitat màxima d&#39;e-mails que es poden enviar en una hora:';
$_['text_minutes'] = 'minuts';
$_['text_newsletter']      = 'Tots els Subscriptors del Butlletí';
$_['text_customer_all']    = 'Tots els Clients';
$_['text_list'] = 'Vulneracions de Dades Registrades (Agència General de Protecció de Dades)';
$_['text_list_customers'] = 'Vulneracions de Dades registrades (Clients)';
$_['text_product']         = 'Productes';
$_['text_add_breach']         = 'Afegir Incident Vulneració de Dades';
$_['text_return'] = 'Tornar';
$_['text_save_breach']         = 'Guardar';
$_['text_signature'] = 'Salutacions,';
$_['text_status_generated']         = 'Generat';
$_['text_status_pending']         = 'Pendent';
$_['text_status_sent']         = 'Enviat';
$_['text_status_unknown']         = 'Desconegut';
$_['text_status_failed']         = 'E-mail no vàlid';
$_['text_success_email_batch'] = 'Els e-mails ara s&#39;enviaran pel vostre servidor. Reviseu la llista de notificacions a:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'Presentem un incident de vulneració de seguretat de dades que pot comportar informació personal.';
$_['text_report_to_commissioner_contact'] = 'El nostre punt de contacte sobre aquest incident és:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = 'A %s hem descobert una vulneració de dades tal com es detalla a continuació:';
$_['text_report_to_commissioner_data_exposed'] = 'Les dades a les que s&#39;ha accedit poden haver inclòs informació personal com ara:';
$_['text_report_to_commissioner_actions_taken'] = 'Fins ara em pres els passos següents per solucionar aquest incident:';
$_['text_report_to_commissioner_ending'] = 'Estem realitzant una revisió exhaustiva dels sistemes potencialment afectats i notificarem a l&#39;Agència General de Protecció de Dades si hi ha avenços significatius. Estem implementant mesures de seguretat addicionals dissenyades per prevenir la reincidència d&#39;un atac així com per protegir la privadesa dels nostres clients';

$_['text_send_breach_notification'] = 'Enviar Notificació Vulneració';

// Entry
$_['entry_address_commissioner'] = 'Agència General de Protecció de Dades (Adreça Postal)';
$_['entry_address_store'] = 'Botiga (Adreça Postal)';
$_['entry_batch_size'] = 'Mida Lot';
$_['entry_breach_notification_status'] = 'Estat';
$_['entry_breach_customer_email_notification_status'] = 'Estat de Notificació per E-mail';
$_['entry_contact_details_of_person_reporting']       = 'Dades de contacte de l&#39;Agència General de Protecció de Dades';
$_['entry_contact_email']       = 'E-mail de contacte per a clients';
$_['entry_customer_email']       = 'E-mail';
$_['entry_customer_group'] = 'Grup de Clients';
$_['entry_customer']       = 'Client';
$_['entry_date_added']       = 'Data Afegit';
$_['entry_date_of_breach']       = 'Data(es) de la vulneració (si es coneix)';
$_['entry_date_of_discovery']       = 'Data en la que es va descobrir l&#39;incident';
$_['entry_email_bcc']       = 'CCO';
$_['entry_email_commissioner'] = 'Agència General de Protecció de Dades (e-mail)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Temps d&#39;execució màxim';
$_['entry_message_action']        = 'Breu descripció de qualsevol acció des de que es va descobrir la vulneració (AGPD)';
$_['entry_message_action_customers']        = 'Breu descripció de les accions realitzades (Clients)';
$_['entry_message_incident']       = 'Breu descripció de la vulneració de seguretat (AGPD)';
$_['entry_message_incident_customers']       = 'Breu descripció de la vulneració de seguretat (Clients)';
$_['entry_name']       = 'Nom Incident (per la seva referència)';
$_['entry_number_of_accounts_affected']       = 'Nombre de comptes afectats (si es coneix)';
$_['entry_store']          = 'De';
$_['entry_subject']        = 'Assumpte (AGPD E-mail)';
$_['entry_subject_customers']        = 'Assumpte (E-mails Clients)';
$_['entry_to']             = 'A';

// Help
$_['help_address_commissioner'] = 'Adreça postal completa de l&#39;Agència General de Protecció de Dades. Això s&#39;imprimirà a la capçalera per obtenir la carta de notificació de la vulneració';
$_['help_address_store'] = 'Adreça postal completa de la vostra botiga que informa de la vulneració. Això s&#39;imprimirà al capçalera de la carta';
$_['help_batch_size'] = 'Quantitat d&#39;e-mails que es poden enviar en una hora. Això dependrà de la configuració del vostre servidor. La majoria dels servidors compartits la limitaran fortament, de manera que no podreu enviar més de 100 a 200 e-mails per hora.';
$_['help_contact_details_of_person_reporting'] = 'Afegiu e-mail i/o número de telèfon d&#39;una persona com a contacte amb l&#39;Agència General de Protecció de Dades';
$_['help_contact_email'] = 'Afegiu una adreça electrònica per a que els vostres clients puguin contactar pel que fa a la vulneració de dades.';
$_['help_data_commissioner']       = 'E-mail de l&#39;Agència General de Protecció de Dades de la seva zona';
$_['help_date_of_breach'] = 'Si no coneixeu la data exacta, indiqueu un interval de temps estimat';
$_['help_date_of_discovery'] = 'Data en què vau descobrir que el vostre sistema havia estat vulnerat';
$_['help_email_bcc']       = 'Adreces d&#39;e-mail que es copiaran a la notificació de la vulneració';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Temps màxim en que l&#39;script d&#39;enviamnet d&#39;e-mails s&#39;ha d&#39;ejecutar.';
$_['help_message_action'] = 'Descrigui totes les accions que ha pres des que es va descobrir la vulneració. Aquest text s&#39;inclourà a la carta  a l&#39;Agència General de Protecció de Dades.';
$_['help_message_action_customers'] = 'Descrigui totes les accions que ha pres des que es va descobrir la vulneració que són rellevants per als vostres clients. Aquest text s&#39;inclourà als e-mails als clients.';
$_['help_message_incident'] = 'Descrigui en detalls la vulneració de seguretat, qui va accedir a les dades, com ho va fer, com es va descobrir, etc. Aquest text serà inclòs en la carta a l&#39;Agècia General de Protecció de Dades.';
$_['help_message_incident_customers'] = 'Descrigui la vulneració de seguretat amb qualsevol detall rellevant per als seus clients. Aquest text s&#39;inclourà als e-mails als clients.';
$_['help_name'] = 'Aquest nom només és com a referència pel propietari/administrador de la botiga, no es revelarà a l&#39;Agència General de Protecció de Dades o als Clients';
$_['help_number_of_accounts_affected'] = 'Indiqueu quants comptes (clients/dades de persones) s&#39;han vist afectats per la vulneració';
$_['help_subject']       = 'Assumpte de l&#39;e-mail/carta de notificació a l&#39;Agència General de Protecció de Dades';
$_['help_subject_customers']       = 'Assumpte dels e-mails de notificació als clients';

// Error
$_['error_address_commissioner']        = 'Es requereix l&#39;adreça de l&#39;Agència General de Protecció de Dades!';
$_['error_address_store']        = 'Es requereix l&#39;adreça de la tenda!';
$_['error_contact_details_of_person_reporting']        = 'Es requereix les dades de contacte de la persona que envia l&#39;informe!';
$_['error_customer_notification_add'] = 'No s&#39;han pogut afegir les notificacions dels clients';
$_['error_customer_notification_existing'] = 'Ja hi ha notificació per a aquesta vulneració de dades';
$_['error_data_commissioner']        = 'Es requereix un e-mail de l&#39;Agència General de Protecció de Dades!';
$_['error_date_of_breach']        = 'Es requereix la data de la vulneració!';
$_['error_date_of_discovery']        = 'Es requereix la data del descobriment de l&#39;incident';
$_['error_email_batch'] = 'Les vostres notificacions per e-mail no s&#39;han pogut enviar, torneu-ho a provar.';
$_['error_permission']     = 'Advertència: no teniu permís per enviar e-mails de notificació d&#39;incidents.';
$_['error_subject']        = 'Es requereix Assumpte en l&#39;e-mail de notificació';
$_['error_mail_bcc'] = 'El format d&#39;e-mail és incorrecte, proporcioneu les adreces electròniques com una llista separada per comes';
$_['error_mail_commissioner'] = 'El format de l&#39;e-mail per a l&#39;Agència General de Protecció de dades és incoreccte';
$_['error_message_action']        = 'Es requereix un resum de les accions adoptades després de l&#39;incident.';
$_['error_message_incident']        = 'Es requereix la descripció de l&#39;incident/vulneració.';
$_['error_missing_commissioner_email'] = 'L&#39;e-mail de l&#39;Agència General de Protecció de Dades és un camp obligatori';
$_['error_saving_breach_notification_failed'] = 'No hem pogut desar el registre de notificació d&#39;incident, torneu-ho a provar';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'notificacio_vulneracio_dades';
