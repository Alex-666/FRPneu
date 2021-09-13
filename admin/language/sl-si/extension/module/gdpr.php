<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'GDPR nastavitve';
$_['text_gdpr_report']             = 'GDPR zgodovina zahtev';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Shrani';
$_['button_cancel'] = 'Prekliči';

// Entry
$_['entry_admin']      = 'Samo za administratorje';
$_['entry_status']     = 'Stanje';
$_['entry_name'] = 'Ime';
$_['entry_message_text'] = 'Sporočilo za prikaz';
$_['entry_date_start'] = 'Začetni datum (LLLL-MM-DD)';
$_['entry_date_end'] = 'Končni datum (LLLL-MM-DD)';
$_['entry_status'] = 'Status';

$_['entry_email_footer'] = 'Besedilo v nogi GDPR e-sporočila';
$_['entry_email_header'] = 'Besedilo v glavi GDPR e-sporočila';
$_['entry_locations_of_other_data'] = 'Druge lokacije/servisi kjer so shranjeni osebni podatki';
$_['entry_locations_of_servers'] = 'Fizična lokacija strežnika kjer gostuje spletna stran in ostali podatki.';
$_['entry_max_requests_day'] = 'Maksimalno število DGPR zahtev na dan';
$_['entry_pending_status'] = 'Besedilo statusa Čakanje';
$_['entry_confirmed_status'] = 'Besedilo statusa Potrjeno';
$_['entry_emailed_status'] = 'Besedilo statusa Odposlano';
$_['entry_account_deleted_status'] = 'Besedilo statusa Račun izbrisan';
$_['entry_data_sent'] = 'Odposlano';
$_['entry_unpaid'] = 'Neplačano';
$_['entry_free'] = 'Brezplačno';
$_['entry_rejected'] = 'Zavrnjeno';
$_['entry_fairuse'] = 'Poštena uporaba';
$_['entry_max_days_process'] = 'Maksimalno število dni za odziv';
$_['entry_right_to_be_forgotten'] = 'Omogoči pravico za izbris/anonimizacijo';

// Help
$_['help_pending_status'] = 'Ime statusa GDPR zahteve, ki še ni bila potrjena z strani kupca. Ta status bo prikazan na administratorskem GDPR poročilu o zahtevah in na kupčevem poročilu o GDPR zahtevah.';
$_['help_confirmed_status'] = 'Ime statusa GDPR zahteve, ki je že bila potrjena z strani kupca z uporabo potrditvene povezave v e-mailu, vendar še ni bila obdelana (e-mail z poročilo še ni bil poslan). Ta status bo prikazan na administratorskem GDPR poročilu o zahtevah in na kupčevem poročilu o GDPR zahtevah.';
$_['help_emailed_status'] = 'Ima statusa GDPR zahteve, ki je že obdelana/dokončana (poročilo je že bilo poslano kupcu). Ta status bo prikazan na administratorskem GDPR poročilu o zahtevah in na kupčevem poročilu o GDPR zahtevah.';
$_['help_account_deleted_status'] = 'Ima statusa GDPR zahteve za izbris/anonimizacijo, ki je bila obdelana/dokončana. Ta status bo prikazan na administratorskem GDPR poročilu o zahtevah.';
$_['help_locations_of_other_data'] = 'Seznam vseh lokacij and spletnih servisov, kjer shranjujete osebne podatek kupcev. Primer: Mailchimp, Google Docs, ... . Ta informacija bo vključena v GDPR poročilo, ki se pošilja kupcem.';
$_['help_locations_of_servers'] = 'Seznam vseh pomembnih informacij povezanih z spletnim gostovanjem in lokacijo strečnik (primer: lokacija strežnika, ponudnik gostovanja, ali je ponudnik GDPR skladen, ...).';
$_['help_max_requests_day'] = 'Število zahtev, ki jih lahko pozamezen kupec izvede v 24 urah. Vrednost naj bo nastavljena nizko, kar bo preprečilo lažne zahteve, napade in nepotrebno obremenitev strežnika. Priporočamo vrednost med 3 in 5 .';
$_['help_right_to_be_forgotten'] = 'Omogoči \'GDPR zahteva za izbris/anonimizacijo podatkov\' kjer lahko kupci samostojno izbrišejo svoj račun in anonimizirajo podatke, kjer le-ti ne morejo biti izbrisani.';

// Error
$_['error_permission'] = 'POZOR: Nimate pravice za urejanje GDPR modula!';

// Text
$_['text_module']      = 'Modul';
$_['text_success']     = 'USPEH: Spremenili ste GDPR nastavitve!';
$_['text_edit']        = 'Uredi GDPR nastavitve';
// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Sprejeta pravila poslovanja';
$_['entry_forms_are_private'] = 'GDPR zahteva prijavo';

$_['help_store_policy_acceptance'] = 'Če je vključeno bo vsako sprejetje vaših poslovnih pogojev zabeleženo (tako na strani z košarico, kot tudi na strani za registracijo) v bazo. POMEMBNO: če je nastavitev vključena se prepričajte, da vaša košarica deluje pravilno!';
$_['help_forms_are_private'] = 'Če je vključeno se lahko GDPR zahteveve izvedejo le če jih je zahteval prijavljen uporabnik.'; 