<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'Setari GDPR';
$_['text_gdpr_report']             = 'Istoricul Cererilor GDPR';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Salveaza';
$_['button_cancel'] = 'Sterge';

// Entry
$_['entry_admin']      = 'Numai Administratori';
$_['entry_status']     = 'Status';
$_['entry_name'] = 'Nume';
$_['entry_message_text'] = 'Mesaj';
$_['entry_date_start'] = 'Data Start (YYYY-MM-DD)';
$_['entry_date_end'] = 'Data Sfarsit (YYYY-MM-DD)';
$_['entry_status'] = 'Status';

$_['entry_email_footer'] = 'GDPR Email Jos';
$_['entry_email_header'] = 'GDPR Email Sus';
$_['entry_locations_of_other_data'] = 'Alte locatii/servicii unde sunt stocate date personale';
$_['entry_locations_of_servers'] = 'Locatiile fizice ale serverelor in care este gazduit site-ul si alte date';
$_['entry_max_requests_day'] = 'Cereri maxime pe zi';
$_['entry_pending_status'] = 'In asteptare';
$_['entry_confirmed_status'] = 'Confirmat';
$_['entry_emailed_status'] = 'E-mail';
$_['entry_account_deleted_status'] = 'Contul a fost sters';
$_['entry_data_sent'] = 'Data Trimiterii';
$_['entry_unpaid'] = 'Neplatit';
$_['entry_free'] = 'Gratuit';
$_['entry_rejected'] = 'Respins';
$_['entry_fairuse'] = 'Utilizare Echitabila';
$_['entry_max_days_process'] = 'Numarul maxim de zile pentru raspuns';
$_['entry_right_to_be_forgotten'] = 'Activati dreptul de a fi uitat';

// Help
$_['help_pending_status'] = 'Statusul unei solicitari GDPR care nu a fost inca confirmat de client. Acest status va fi afisat in raportul de solicitare GDPR din admin si dezvaluit clientului in istoricul inregistrarii solicitarilor GDPR.';
$_['help_confirmed_status'] = 'Statusul unei solicitari GDPR confirmate de client utilizand linkul de confirmare a e-mailului, dar care nu a fost inca procesat (e-mailul pentru raport nu a fost inca trimis). Acest status va fi afisat in raportul de solicitare GDPR din admin si dezvaluit clientului in istoricul inregistrarii solicitarilor GDPR.';
$_['help_emailed_status'] = 'Statusul unei solicitari GDPR care a fost procesata/finalizata (e-mailul de raportare a fost trimis clientului). Acest status va fi afisat in raportul de solicitare GDPR din admin si dezvaluit clientului in istoricul inregistrarii solicitarilor GDPR.';
$_['help_account_deleted_status'] = 'Statusul unei solicitari de eliminare a contului GDPR care a fost procesata/finalizata. Acest status va fi afisat in raportul de solicitare GDPR al administratorului.';
$_['help_locations_of_other_data'] = 'Listati toate celelalte locatii si servicii web unde stocati datele personale ale clientilor. Exemple: Mailchimp, Google Docs, SmartSupp, etc. Aceste informatii vor fi incluse in raportul de e-mail GDPR trimis clientului.';
$_['help_locations_of_servers'] = 'Specificati toate informatiile relevante despre locatiile dvs. de gazduire si de server (de exemplu: tarile cu locatii de server, furnizorii de servicii de gazduire, furnizorii de servicii de gazduire conforme cu standardele GDPR, etc.).';
$_['help_max_requests_day'] = 'Numarul solicitarilor pe care clientul le poate trimite pe zi. Acest lucru ar trebui sa fie setat la un numar scazut pentru a preveni solicitarile de spam, atacuri si incarcare inutila pe serverul dvs. web. Va recomandam o valoare cuprinsa intre maxim 3-5 solicitari.';
$_['help_right_to_be_forgotten'] = 'Puteti folosi formularul "Dreptul de a fi uitat", in care clientii isi pot sterge automat contul si isi pot modifica datele personale atunci cand nu pot fi sterse';

// Error
$_['error_permission'] = 'Atentie: Nu aveti permisiunea de a modifica modulul GDPR!';

// Text
$_['text_module']      = 'Modul';
$_['text_success']     = 'Ati modificat cu succes modulul GDPR!';
$_['text_edit']        = 'Editati';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Store Policy Acceptance';
$_['entry_forms_are_private'] = 'GDPR Forms Require Login';

$_['help_store_policy_acceptance'] = 'If set to yes, every time customer accepts your terms on the registration page or in the checkout, this will be recorded in the database. IMPORTANT: if you set it to yes please make sure you checkout is working correctly!';
$_['help_forms_are_private'] = 'If set to yes, GDPR request can only be submitted by a logged in customer.';
