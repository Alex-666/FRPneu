<?php

// Buttons
$_['button_add_breach'] = 'Aggiungi Incidente di Violazione Dati.';
$_['button_download_pdf'] = 'Scarica la lettera di notifica in formato PDF';
$_['button_email'] = 'Invia le email';
$_['button_generate_customer_notifications'] = 'Genera Notifiche Clienti';
$_['button_preview_customer_notification'] = 'Anteprima';
$_['button_save_breach'] = 'Salva';
$_['button_send'] = 'Invia lettera di notifica per email';

// Columns
$_['column_action'] = 'Azione';
$_['column_bcc_to'] = 'in CCN a';
$_['column_breach_id'] = 'ID Violazione';
$_['column_customer_email'] = 'Email';
$_['column_customer_id'] = 'ID Cliente';
$_['column_customer_name'] = 'Nome';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'Data di Aggiunta';
$_['column_date_of_breach']	= 'Data della Violazione';
$_['column_date_of_discovery'] = 'Date della Scoperta';
$_['column_date_updated'] = 'Ultimo Aggiornamento';
$_['column_number_of_accounts_affected'] = 'Nmero di Account Interessati';
$_['column_breach_name'] = 'Nome della Violazione';
$_['column_sent_to'] = 'Inviata per email a';
$_['column_status'] = 'Stato di Notifica del Responsabile';
$_['column_status_customers'] = 'Stato di Notifica dei Clienti';
$_['column_status_notification'] = 'Stato email del Cliente';

// Heading
$_['heading_title']        = 'Notifica di Violazione della Sicurezza dei Dati';
$_['heading_title_customers']        = 'Notifica di Violazione della Sicurezza dei Dati';
$_['heading_title_customers_send_emails'] = 'Invia email di Notifica ai Clienti';
$_['heading_detailed'] = 'Notifica di Violazione della Sicurezza dei Dati al Responsabile per la Protezione dei Dati';
$_['heading_detailed_customers'] = 'Notifica di Violazione della Sicurezza dei Dati ai Clienti';
$_['heading_detailed_customers_send_emails'] = 'Invia Notifiche ai Clienti manualmente in blocchi. Questo metodo non &egrave; consigliato se hai un numero elevato di clienti.';

// Text
$_['text_breach_email_subject'] = 'Notifica di Violazione dei Dati Personali';
$_['text_commissioner'] = 'Responsabile della Protezione dei Dati';
$_['text_customers'] = 'Clienti';
$_['text_data_breach'] = 'Segnalazione di Violazione dei Dati';
$_['text_data_breach_commissioner_list'] = 'Incidenti Registrati';
$_['text_data_breach_commissioner_form'] = 'Aggiungi Nuovo Incidente';
$_['text_data_breach_customer_list'] = 'Genera Notifiche';
$_['text_data_breach_customer_emails'] = 'Notifiche Email Registrate';
$_['text_data_breach_customer_emails_send'] = 'Ivia le Email';
$_['text_data_breach_customer_csv'] = 'Scarica Lista Clienti';

$_['text_email_commissioner_body'] = 'Si prega di considerare la Lettera di Notifica di Violazione dei Dati Allegata.';
$_['text_email_commissioner_subject'] = 'Notifica di Violazione della Sicurezza dei Dati';
$_['text_section_commissioner'] = 'Informazioni per il Responsabile della Protezione dei Dati';
$_['text_section_customer'] = 'Informazioni per i Clienti';
$_['text_section_general'] = 'General Information';
$_['text_success']         = 'Il tuo messaggio è stato inviato con successo!';
$_['text_success_saved_record']         = 'Il tuo record di notifica di violazione &egrave; stato salvato con successo. Si prega di notare che non &egrave; stato ancora inviato, per farlo &egrave; necessario scaricare una versione PDF della lettera o utilizzare il pulsante \'Invia Email al Responsabile\'';
$_['text_success_generate_customer_notifications']         = 'Le notifiche dei tuoi clienti sono state generate (tieni presente che non è stato ancora inviato un messaggio di posta elettronica)!';
$_['text_sent']            = 'Il tuo messaggio è stato inviato correttamente a %s di %s destinatari!';
$_['text_notifications_emailed'] = 'Le tue notifiche via e-mail:';
$_['text_notifications_pending'] = 'Le tue notifiche in sospeso:';
$_['text_total_customers'] = 'Il numero totale di account cliente nel tuo sistema è';


$_['text_from']         = 'Da:';
$_['text_to']         = 'A:';
$_['text_date']         = 'Data';
$_['text_default']         = 'Predefinito';
$_['text_email'] = 'Ivia le email';
$_['text_header_cron'] = 'Impostazione CRON (raccomandato)';
$_['text_header_log'] = 'Log';
$_['text_hour'] = 'ora';
$_['text_hours'] = 'ore';
$_['text_instructions']    = 'Prdefinito';
$_['text_instructions_customers_send_emails'] = 'Questo modulo ti consente di inviare manualmente blocchi di notifiche email ai tuoi clienti. Devi selezionare le impostazioni appropriate per il tuo hosting / server. Tieni presente che se i limiti del server per l\'invio di email sono bassi, potrebbe essere necessario molto tempo per il completamento. Questo &egrave; il motivo per cui ti consigliamo di impostare un cron job che verr&agrave; eseguito automaticamente per te (dettagli disponibili sotto). Questo script funziona solo su notifiche che non sono ancora state inviate. Ogni notifica inviata via e-mail a un cliente verr&agrave; contrassegnata come inviata nel database, in modo da poter tenere traccia delle notifiche inviate.';
$_['text_instructions_log'] = 'Il registro delle email inviate ai clienti può essere trovato nella cartella dei log di Opencart:';
$_['text_instructions_cron'] = 'Per consentire al server di inviare automaticamente le e-mail, si prega di configurare un cron job utilizzando il seguente URL dove \'max_runtime \' &egrave; il tempo massimo in cui il server può eseguire lo script in pochi minuti e \'batch_size \' &egrave; il numero massimo di e-mail che può essere inviato in un\'ora:';
$_['text_minutes'] = 'minuti';
$_['text_newsletter']      = 'Tutti gli Abbonati alla Newsletter';
$_['text_customer_all']    = 'Tutti i Clienti';
$_['text_list'] = 'Violazioni dei Dati Registrati (Responsabile per i Dati)';
$_['text_list_customers'] = 'Violazioni dei Dati Registrati (Clienti)';
$_['text_product']         = 'Prodotti';
$_['text_add_breach']         = 'Aggiungi Incidente di Violazione Dati';
$_['text_return'] = 'Return';
$_['text_save_breach']         = 'Salva';
$_['text_signature'] = 'Cordiali saluti,';
$_['text_status_generated']         = 'Generati';
$_['text_status_pending']         = 'Sospesi';
$_['text_status_sent']         = 'Inviati';
$_['text_status_unknown']         = 'Sconosciuti';
$_['text_status_failed']         = 'E-mail non valida';
$_['text_success_email_batch'] = 'Le email verranno ora inviate dal tuo server. Si prega di rivedere l\'elenco delle notifiche in:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'Con la presente segnaliamo un incidente di violazione della sicurezza dei dati che potrebbe comportare informazioni personali.';
$_['text_report_to_commissioner_contact'] = 'Il nostro punto di contatto per quanto riguarda questo incidente è:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = 'Su %s abbiamo scoperto una violazione dei dati come descritto di seguito:';
$_['text_report_to_commissioner_data_exposed'] = 'I dati consultati potrebbero aver incluso informazioni personali come:';
$_['text_report_to_commissioner_actions_taken'] = 'Abbiamo preso le seguenti misure per rimediare a questo incidente fino ad ora:';
$_['text_report_to_commissioner_ending'] = 'Stiamo conducendo una revisione approfondita dei sistemi potenzialmente interessati e notificheremo al responsabile della protezione dei dati l\'eventuale presenza di sviluppi significativi. Stiamo implementando ulteriori misure di sicurezza volte a prevenire il ripetersi di un simile attacco e a proteggere la privacy dei nostri clienti';

$_['text_send_breach_notification'] = 'Invia Notifica di Violazione';

// Entry
$_['entry_address_commissioner'] = 'Responsabile dei Dati (Indirizzo Postale)';
$_['entry_address_store'] = 'Negozio (Indirizzo Postale)';
$_['entry_batch_size'] = 'Dimensione Blocco';
$_['entry_breach_notification_status'] = 'Status';
$_['entry_breach_customer_email_notification_status'] = 'Stato delle email di Notifica';
$_['entry_contact_details_of_person_reporting']       = 'Dati di Contatto per il Responsabile dei Dati';
$_['entry_contact_email']       = 'Email di Contatto per i clienti';
$_['entry_customer_email']       = 'Email';
$_['entry_customer_group'] = 'Gruppo Cliente';
$_['entry_customer']       = 'Cliente';
$_['entry_date_added']       = 'Data di Aggiunta';
$_['entry_date_of_breach']       = 'Data(e) della violazione (se conosciuta/e)';
$_['entry_date_of_discovery']       = 'Data di scoperta dell\'incidente';
$_['entry_email_bcc']       = 'CCN';
$_['entry_email_commissioner'] = 'Responsabile dei Dati (email)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Runtime massimo';
$_['entry_message_action']        = 'Breve descrizione di ogni azione da quando è stata scoperta la violazione (Responsabile)';
$_['entry_message_action_customers']        = 'Breve descrizione delle azioni intraprese (Clienti)';
$_['entry_message_incident']       = 'Breve descrizione della violazione (Responsabile)';
$_['entry_message_incident_customers']       = 'Breve descrizione della violazione (Clienti)';
$_['entry_name']       = 'Nome dell\'Incidente (per Vostro Riferimento)';
$_['entry_number_of_accounts_affected']       = 'Numero di account interessati (se noto)';
$_['entry_store']          = 'Da';
$_['entry_subject']        = 'Oggetto (E-mail Responsabile)';
$_['entry_subject_customers']        = 'Oggetto (E-mail dei Clienti)';
$_['entry_to']             = 'A';

// Help
$_['help_address_commissioner'] = 'Indirizzo postale completo del responsabile della protezione dei dati. Questo verr&agrave; stampato nell\'intestazione per la lettera di notifica di violazione';
$_['help_address_store'] = 'Indirizzo postale completo del tuo negozio che segnala la violazione. Questo verr&agrave stampato nell\'intestazione della lettera';
$_['help_batch_size'] = 'Un numero di email che possono essere inviate in un\'ora. Questo dipender&agrave; dalla configurazione del tuo server. La maggior parte dei server condivisi la limiter&agrave; pesantemente, quindi non ti sar&agrave; permesso di inviare più di 100-200 email all\'ora.';
$_['help_contact_details_of_person_reporting'] = 'Aggiungi l\'indirizzo email e / o il numero di telefono di una persona che pu&ograve; essere contattata dall\'Ufficio del Responsabile della Protezione dei Dati';
$_['help_contact_email'] = 'Aggiungi l\'indirizzo email che i tuoi clienti possono contattare in merito alla violazione dei dati.';
$_['help_data_commissioner']       = 'Indirizzo email del responsabile dei dati nella tua giurisdizione';
$_['help_date_of_breach'] = 'Se non si conosce la data esatta, si prega di fornire un intervallo di tempo stimato';
$_['help_date_of_discovery'] = 'Data in cui hai scoperto che il tuo sistema &egrave; stato violato';
$_['help_email_bcc']       = 'Gli indirizzi e-mail che devono essere copiati sulla notifica di violazione';
$_['help_max_runtime'] = 'Tempo massimo per cui &egrave; necessario eseguire lo script di invio di posta elettronica.';
$_['help_message_action'] = 'Descrivi tutte le azioni intraprese da quando &egrave; stata scoperta la violazione. Questo testo sar&agrave; incluso nella lettera al Responsabile per la Protezione dei Dati.';
$_['help_message_action_customers'] = 'Descrivi tutte le azioni intraprese da quando &egrave; stata scoperta la violazione pertinenti per i tuoi clienti. Questo testo sar&grave; incluso nelle e-mail ai clienti.';
$_['help_message_incident'] = 'Descrivi la violazione in dettaglio, chi ha avuto accesso ai dati, come l\'hanno fatto, come &egrave; stato scoperto ecc. Questo testo sar&agrave; incluso nella lettera al Responsabile della Protezione dei Dati.';
$_['help_message_incident_customers'] = 'Descrivi la violazione con qualsiasi dettaglio rilevante per i tuoi clienti. Questo testo sar&agrave; incluso nelle e-mail ai clienti.';
$_['help_name'] = 'Questo nome &egrave; solo per riferimento per il proprietario/amministratore del negozio, non verrà rivelato al Responsabile dei Dati o ai Clienti';
$_['help_number_of_accounts_affected'] = 'Indicare quanti account (clienti / dati interessati) sono stati interessati dalla violazione';
$_['help_subject']       = 'Oggetto della email/lettera di notifica al Responsabile della Protezione dei Dati';
$_['help_subject_customers']       = 'Oggetto delle e-mail di notifica ai clienti';

// Error
$_['error_address_commissioner']        = 'L\Indirizzo del Responsabile della Protezione dei Dati &egrave; richiesto!';
$_['error_address_store']        = 'L\'Indirizzo del negozio &egrave; richiesto!';
$_['error_contact_details_of_person_reporting']        = 'I dettagli di contatto della persona che invia il rapporto sono richiesti!';
$_['error_customer_notification_add'] = 'Impossibile aggiungere le notifiche dei clienti';
$_['error_customer_notification_existing'] = 'Esistono gi&agrave; notifiche per questa violazione dei dati';
$_['error_data_commissioner']        = 'l\'email del Responsabile della Protezione dei Dati &egrave; richiesta!';
$_['error_date_of_breach']        = 'La Data della Violazione &egrave; richiesta!';
$_['error_date_of_discovery']        = 'La Data di scoperta della Violazione &egrave; richiesta!';
$_['error_email_batch'] = 'Le email di notifica non possono essere inviate, ti preghiamo di provare ancora.';
$_['error_permission']     = 'Attenzione: non hai il permesso di inviare le E-Mail di Notifica della Violazione!';
$_['error_subject']        = 'L\'Oggetto dell\'email di Notifica della Violazione &egrave; richiesto!';
$_['error_mail_bcc'] = 'Formato E-mail format non corretto, si prega di fornire gli indirizzi email come lista separata da virgola';
$_['error_mail_commissioner'] = 'L\'E-mail per il Responsabile dei Dati non &egrave; nel formato corretto.';
$_['error_message_action']        = 'Il riepilogo delle azioni intraprese dopo la violazione &egrave; richiesto!';
$_['error_message_incident']        = 'La Descrizione dell\'incidente/violazione &egrave; richiesta!';
$_['error_missing_commissioner_email'] = 'L\'email del Responsabile &egrave; un campo obbligatorio!';
$_['error_saving_breach_notification_failed'] = 'Non &egrave; stato possibile salvare il record di notifica delle violazioni, per favore riprova';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'notifica_violazione_dati';
