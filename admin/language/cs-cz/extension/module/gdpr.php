<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'Nastavení GDPR';
$_['text_gdpr_report']             = 'Historie GDPR požadavků';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Uložit';
$_['button_cancel'] = 'Zrušit';

// Entry
$_['entry_admin']      = 'Pouze administrátorské účty';
$_['entry_status']     = 'Stav';
$_['entry_name'] = 'Jméno';
$_['entry_message_text'] = 'Zpráva k zobrazení';
$_['entry_date_start'] = 'Datum začátku (YYYY-MM-DD)';
$_['entry_date_end'] = 'Datum ukončení (YYYY-MM-DD)';
$_['entry_status'] = 'Stav';

$_['entry_email_footer'] = 'GDPR text patičky emailu';
$_['entry_email_header'] = 'GDPR text hlavičku emailu';
$_['entry_locations_of_other_data'] = 'Další umístění/služby, kde uchovávate osobní data zákazníků';
$_['entry_locations_of_servers'] = 'Fyzické adresy serverů, na kterých hostujete aplikaci a data';
$_['entry_max_requests_day'] = 'Maximální počet požadavků za den';
$_['entry_pending_status'] = 'Čeká na vyřízení';
$_['entry_confirmed_status'] = 'Potvrzeno';
$_['entry_emailed_status'] = 'Email odeslán';
$_['entry_account_deleted_status'] = 'Účet smazán';
$_['entry_data_sent'] = 'Odesláno';
$_['entry_unpaid'] = 'Neuhrazeno';
$_['entry_free'] = 'Volný text';
$_['entry_rejected'] = 'Odmítnuto';
$_['entry_fairuse'] = 'Spravedlivé užití';
$_['entry_max_days_process'] = 'Maximální počet dnů do odpovědi';
$_['entry_right_to_be_forgotten'] = 'Zapnout formulář Právo být zapomenut';

// Help
$_['help_pending_status'] = 'Název stavu GDPR požadavku, který ještě nebyl potvrzen zákazníkem. Tento stav bude zobrazen administrátorovi v hlášení GDPR požadavků. Rovněž bude uveden v seznamu historických GDPR požadavků zákazníka.';
$_['help_confirmed_status'] = 'Název stavu GDPR požadavku´, který již byl potvrzen zákazníkem, ale ještě nebyl zprácován (email se seznamem nebyl odeslán zákazníkovi). Tento stav bude zobrazen administrátorovi v hlášení GDPR požadavků. Rovněž bude uveden v seznamu historických GDPR požadavků zákazníka.';
$_['help_emailed_status'] = 'Název stavu GDPR požadavku, který byl zpracován/dokončen (email s reportem byl odeslán na zákazníkův email). Tento stav bude zobrazen administrátorovi v hlášení GDPR požadavků. Rovněž bude uveden v seznamu historických GDPR požadavků zákazníka.';
$_['help_account_deleted_status'] = 'Název stavu GDPR požadavku pro odstranění účtu zákazníka, který již byl zprácován/dokončen. Tento stav bude zobrazen administrátorovi v hlášení GDPR požadavků.';
$_['help_locations_of_other_data'] = 'Vyplňte seznam všech lokací a webových služeb, kde pracujete s osobními údaji zákazníků. Příklad: Mailchimp, Google Docs, atd. Tato informace bude odeslána jako součást hlášení, které se odesílá zákazníkovi na email.';
$_['help_locations_of_servers'] = 'Vyplňte seznam všech relavantních informacích o vašem hostingu a umístění serverů (Například: název země, kde jsou servery fyzicky umístěny, jména poskytovatele hostingu a zda vyhovují podmínkám GDPR, atd.).';
$_['help_max_requests_day'] = 'Počet požadavků, které zákazník může odeslat za den. Tato hodnota by měla být nastavena nízko, aby se zamezilo spamovým požadavkům, útokům a zbytečnému vytížení serveru. Doporučujeme hodnotu mezi 3-5 požadavky za den.';
$_['help_right_to_be_forgotten'] = 'Zapne formulář \'Právo být zapomenut\', který umožní zákazníkům automaticky smazat osobní údaje a anonymizovat tam, kde toto není možné odstranit.';


// Error
$_['error_permission'] = 'Varování: Nemáte dostatečná oprávnění pro úpravy GDPR modulu!';

// Text
$_['text_module']      = 'Moduly';
$_['text_success']     = 'Úprava modulu GDPR byla úspěšně provedena';
$_['text_edit']        = 'Upravit GDPR modul';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Store Policy Acceptance';
$_['entry_forms_are_private'] = 'GDPR Forms Require Login';

$_['help_store_policy_acceptance'] = 'If set to yes, every time customer accepts your terms on the registration page or in the checkout, this will be recorded in the database. IMPORTANT: if you set it to yes please make sure you checkout is working correctly!';
$_['help_forms_are_private'] = 'If set to yes, GDPR request can only be submitted by a logged in customer.';
