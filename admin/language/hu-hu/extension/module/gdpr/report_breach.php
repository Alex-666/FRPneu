<?php

// Buttons
$_['button_add_breach'] = 'Adatvédelmi Incidens hozzáadása';
$_['button_download_pdf'] = 'Értesítő levél letöltése PDF formátumban';
$_['button_email'] = 'E-mail küldése';
$_['button_generate_customer_notifications'] = 'Ügyfél Értesítés Létrehozása';
$_['button_preview_customer_notification'] = 'Előnézet';
$_['button_save_breach'] = 'Mentés';
$_['button_send'] = 'Értesítés küldése e-mailben';

// Columns
$_['column_action'] = 'Művelet';
$_['column_bcc_to'] = 'Titkos másolat';
$_['column_breach_id'] = 'Incidens azonosító';
$_['column_customer_email'] = 'E-mail';
$_['column_customer_id'] = 'Ügyfél azonosító';
$_['column_customer_name'] = 'Név';
$_['column_id'] = 'Azonosító';

$_['column_date_added'] = 'Létrehozás dátuma';
$_['column_date_of_breach']	= 'Incidens dátuma';
$_['column_date_of_discovery'] = 'Felfedezés dátuma';
$_['column_date_updated'] = 'Utolsó frissítés';
$_['column_number_of_accounts_affected'] = 'Érintett fiókok száma';
$_['column_breach_name'] = 'Incidens neve';
$_['column_sent_to'] = 'E-mailt kapott';
$_['column_status'] = 'A Adatvédelmi Hatóság Értesítési Állapota';
$_['column_status_customers'] = 'Ügyfek Értesítési Állapota';
$_['column_status_notification'] = 'Ügyfél E-mail Állapota';

// Heading
$_['heading_title']        = 'Adatvédelmi Incidens Értesítés ';
$_['heading_title_customers']        = 'Adatvédelmi Incidens Értesítés ';
$_['heading_title_customers_send_emails'] = 'Értesítő e-mail küldése az Ügyfelek részére';
$_['heading_detailed'] = 'Adatvédelmi Incidens Értesítés az Adatvédelmi Hatóság részére';
$_['heading_detailed_customers'] = 'Adatvédelmi Incidens Értesítés az Ügyfelek részére';
$_['heading_detailed_customers_send_emails'] = 'Értesítés küldés az Ügyfelek részére manuálisan, részletekben. Ez a módszer nem ajánlott nagy mennyiségű Ügyfél esetén.';

// Text
$_['text_breach_email_subject'] = 'Személyes Adatvédelmi Incidens Értesítés';
$_['text_commissioner'] = 'Adatvédelmi Hatóság';
$_['text_customers'] = 'Ügyfelek';
$_['text_data_breach'] = 'Adatvédelmi Incidens Jelentés';
$_['text_data_breach_commissioner_list'] = 'Rögzített Incidensek';
$_['text_data_breach_commissioner_form'] = 'Új Incidens rögzítése';
$_['text_data_breach_customer_list'] = 'Értesítés Létrehozása';
$_['text_data_breach_customer_emails'] = 'Rögzített E-mail Értesítések';
$_['text_data_breach_customer_emails_send'] = 'E-mail küldése';
$_['text_data_breach_customer_csv'] = 'Ügyfél Lista letöltése';

$_['text_email_commissioner_body'] = 'Mellékelve küldjük az Adatvédelmi Incidens Értesítő levelet.';
$_['text_email_commissioner_subject'] = 'Értesítés Adatvédelmi Incidensről ';
$_['text_section_commissioner'] = 'Információ az Adatvédelmi Hatóság részére';
$_['text_section_customer'] = 'Információ az Ügyfelek részére';
$_['text_section_general'] = 'Általános Információ';
$_['text_success']         = 'Az üzenetet sikeresen elküldted!';
$_['text_success_saved_record']         = 'Az Incidens Értesítőt sikeresen elmentetted. Ne feledd, hogy még nem lett elküldve, ehhez töltsd le a PDF verzóját a levélnek vagy használd az \'E-mail küldés\' gombot';
$_['text_success_generate_customer_notifications']         = 'Az Ügyfél Étesítések elkészültek (Ne feledd, hogy még nem lettek elküldve.)!';
$_['text_sent']            = 'Az üzenet sikeresen elküldésre került %s / %s címzettnek!';
$_['text_notifications_emailed'] = 'Az elküldött értesítések:';
$_['text_notifications_pending'] = 'A függő értesítések:';
$_['text_total_customers'] = 'A rendszerben található összes ügyfél száma';


$_['text_from']         = 'Feladó:';
$_['text_to']         = 'Címzett:';
$_['text_date']         = 'Dátum';
$_['text_default']         = 'Alapértelmezett';
$_['text_email'] = 'E-mailek küldése';
$_['text_header_cron'] = 'CRON beállítás (ajánlott)';
$_['text_header_log'] = 'Napló';
$_['text_hour'] = 'óra';
$_['text_hours'] = 'perc';
$_['text_instructions']    = 'Alapértelmezett';
$_['text_instructions_customers_send_emails'] = 'Ez az űrlap lehetővé teszi, hogy részletekben, manuálisan küld el az e-mail értesítést az Ügyfeleknek. A beállításokat a szerver szolgáltatódnak megfelelően kell beállítanod. Vedd figyelembe, hogy ha a szerver limit az e-mails küldésre alacsony, akkor sok időbe telhet a folyamat. Emiatt javasoljuk egy cron folyamat beállítását, ami automatikusan elvégzi a feladatot (részletek lent). Ez a script csak a még el nem küldött Értesítéseket teljesíti. Minden Ügyfélnek elküldött Értesítés bekerül az adatbázisba, mint "elküldött", így nyomon követhető mely Értesítések lettek elküldve.';
$_['text_instructions_log'] = 'Ez elküldött e-mailek naplója az Opencart (Hiba)napló menüpontban található:';
$_['text_instructions_cron'] = 'Ahhoz, hogy a szerver automatikusan kiküldje az e-maileket, az alábbi URL segítségével állítsd be a Cron feladatot, ahol a \'max_runtime\' a maximális idő percben megadva, amíg a szerver futtathatja a scriptet és a \'batch_size\' az óránként maximálisan elküldhető e-mailek száma:';
$_['text_minutes'] = 'percek';
$_['text_newsletter']      = 'Minden Hírlevél feliratkozó';
$_['text_customer_all']    = 'Minden Ügyfelek';
$_['text_list'] = 'Rögzített Adatvédelmi Incidensek (Adatvédelmi Hatóság)';
$_['text_list_customers'] = 'Rögzített Adatvédelmi Incidensek (Ügyfelek)';
$_['text_product']         = 'Termékek';
$_['text_add_breach']         = 'Adatvédelmi Incidens hozzáadása';
$_['text_return'] = 'Vissza';
$_['text_save_breach']         = 'mentés';
$_['text_signature'] = 'Üdvözlettel,';
$_['text_status_generated']         = 'Létrehozva';
$_['text_status_pending']         = 'Függőben';
$_['text_status_sent']         = 'Elküldött';
$_['text_status_unknown']         = 'Ismeretlen';
$_['text_status_failed']         = 'Hibás E-mail';
$_['text_success_email_batch'] = 'Az e-maileket hamarosan indítja a szerver. Az Értesítési listát megtekintheted:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'Ezúton küldjük el a jelentést egy Adatvédelmi Incidensről, amiben személyes adatok is érintettek lehetnek.';
$_['text_report_to_commissioner_contact'] = 'Az elérhetőségünk az incidenssel kapcsolatban:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = 'Az alábbi adatvédelmi incidensre derült fény az alábbi időpontban: %s:';
$_['text_report_to_commissioner_data_exposed'] = 'A hozzáférhető adatok között személyes adatok is lehetnek úgy mint:';
$_['text_report_to_commissioner_actions_taken'] = 'Az alábbi lépéseket tettük meg eddig az eset orvoslására:';
$_['text_report_to_commissioner_ending'] = 'A potenciálisan érintett rendszerek alapos felülvizsgálatát végezzük és értesítani fogjuk az Adatvédelmi Hatóságot, amennyiben lesznek említésre méltó fejlemények. További biztonsági intézkedéseket vezetünk be, hogy megakadályozzuk az ilyen támadások megismétlődését és hogy megvédjük az Ügyfeleink személyes adatait';

$_['text_send_breach_notification'] = 'Incidens Értesítő elküldése';

// Entry
$_['entry_address_commissioner'] = 'Adatvédelmi Hatóság (Postacím)';
$_['entry_address_store'] = 'Üzlet (Postacím)';
$_['entry_batch_size'] = 'Rész mérete';
$_['entry_breach_notification_status'] = 'Állapot';
$_['entry_breach_customer_email_notification_status'] = 'E-mail Értesítés állapota';
$_['entry_contact_details_of_person_reporting']       = 'Adatvédelmi Hatóság elérhetősége';
$_['entry_contact_email']       = 'E-mail elérhetőség az Ügyfelek részére';
$_['entry_customer_email']       = 'E-mail';
$_['entry_customer_group'] = 'Ügyfél csoport';
$_['entry_customer']       = 'Ügyfél';
$_['entry_date_added']       = 'Létrehozás dátuma';
$_['entry_date_of_breach']       = 'Az Incidens dátuma (ha ismert)';
$_['entry_date_of_discovery']       = 'Felfedezés dátuma';
$_['entry_email_bcc']       = 'Titkos másolat';
$_['entry_email_commissioner'] = 'Adatvédelmi Hatóság (e-mail)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Maximum futásidő';
$_['entry_message_action']        = 'Rövid összefoglaló az Incidens felfedezése óta elvégzett műveletkről (Adatvédelmi Hatóság)';
$_['entry_message_action_customers']        = 'Rövid összefoglaló az elvégzett műveletkről (Ügyfelek)';
$_['entry_message_incident']       = 'Rövid összefoglaló az Incidensről (Adatvédelmi Hatóság)';
$_['entry_message_incident_customers']       = 'Rövid összefoglaló az Incidensről (Ügyfelek)';
$_['entry_name']       = 'Incidens Neve (saját hivatkozás)';
$_['entry_number_of_accounts_affected']       = 'Érintett fiókok száma (ha ismert)';
$_['entry_store']          = 'Feladó';
$_['entry_subject']        = 'Tárgy (Adatvédelmi Hatóság E-mail)';
$_['entry_subject_customers']        = 'Tárgy (Ügyfelek E-mails)';
$_['entry_to']             = 'Címzett';

// Help
$_['help_address_commissioner'] = 'Teljes postacíme az Adatvédelmi Hatóságnak. Ez megjelenik az Incidens Értesítő levél fejlécében';
$_['help_address_store'] = 'Teljes postacíme az incidenst bejelentő üzletnek. Ez is megjelenik az Incidens Értesítő levél fejlécében';
$_['help_batch_size'] = 'Az óránként maximálisan elküldhető e-mailek száma number. Ez a szerver beállításaitól függő érték. A legtöbb szerver jelentősen kolátozza ezt a számot, ezért vélhetően nem tudsz 100-200 emailnél többet elküldeni óránként.';
$_['help_contact_details_of_person_reporting'] = 'A megadott e-mail cím és / vagy telefonszám, ahol eléri az érintett személyt az Adatvédelmi Hatóság';
$_['help_contact_email'] = 'Ezen az e-mail címen kereshet meg az Ügyfél az Adatvédelmi incidenssel kapcsolatban.';
$_['help_data_commissioner']       = 'Az illetékes Adatvédelmi Hatóság e-mail címe';
$_['help_date_of_breach'] = 'Ha nem ismert a pontos dátum, adj meg közelítő periódust';
$_['help_date_of_discovery'] = 'Az Incidens felfedezésének dátuma';
$_['help_email_bcc']       = 'Az e-mail címek, amik másolatot kapnak az Incidens Értesítőből';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Maximális idő, amíg az e-mail küldő script futni fog.';
$_['help_message_action'] = 'Az Incidens felfedezése óta elvégzett kapcsolódó műveletek tételes felsorolása. Ez a leírás az Adatvédelmi Hatóságnak megküldött levélben megjelenik.';
$_['help_message_action_customers'] = 'Az Incidens felfedezése óta elvégzett kapcsolódó műveletek tételes felsorolása az Ügyfelek részére. Ez a leírás az Ügyfeleknek megküldött levélben megjelenik.';
$_['help_message_incident'] = 'Az Incidens részletei, ki fért hozzá az adatokhoz, hogyan tette, hogyan derült ki stb. Ez a leírás az Adatvédelmi Hatóságnak megküldött levélben megjelenik.';
$_['help_message_incident_customers'] = 'Az Incidens fontos részleteinek a leírása az Ügyfelek részére. Ez a leírás az Ügyfeleknek megküldött levélben megjelenik.';
$_['help_name'] = 'Ez az elnevezés csak saját hivatkozás céljából kell, nem fogja látni az Adatvédelmi Hatóság vagy az Ügyfelek';
$_['help_number_of_accounts_affected'] = 'Pontosítsd, hogy mennyi fiók (Ügyfél) érintatt az Incidensben';
$_['help_subject']       = 'Subject of the nofification email/letter to Adatvédelmi Hatóság';
$_['help_subject_customers']       = 'Az Ügyfeleknek elküldött Értesítő e-mail tárgya';

// Error
$_['error_address_commissioner']        = 'Az Adatvédelmi Hatóság címének megadása kötelező!';
$_['error_address_store']        = 'Az üzelt címének megadása kötelező!';
$_['error_contact_details_of_person_reporting']        = 'A levél küldő elérhetőségeinek megadása kötelező!';
$_['error_customer_notification_add'] = 'Nem sikerült létrehozni az Ügyfél Értesítőt';
$_['error_customer_notification_existing'] = 'Már van Értesítés ehhez az Adatvéfelmi incidenshez';
$_['error_data_commissioner']        = 'Az Adatvédelmi Hatóság email címének megadása kötelező!';
$_['error_date_of_breach']        = 'Az Adatvéfelmi incidens dátumának megadása kötelező!';
$_['error_date_of_discovery']        = 'Az Adatvéfelmi incidens felfedezés dátumának megadása kötelező!';
$_['error_email_batch'] = 'Az e-mail Értesítést nem sikerült elküldeni, kérlek, próbáld újra.';
$_['error_permission']     = 'Figyelem: nincs jogosultságod Incidens Értesítés e-mail küldésre!';
$_['error_subject']        = 'Az Incidens Értesítés e-mail tárgyának megadása kötelező!';
$_['error_mail_bcc'] = 'Nem megfelelő e-mail formátum, kérlek, add meg a címeket vesszővel elválasztva';
$_['error_mail_commissioner'] = 'Az Adatvédelmi Hatóság email címének formátuma nem megfelelő.';
$_['error_message_action']        = 'Az Incidens óta tett lépések összefoglalójának megadása kötelező!';
$_['error_message_incident']        = 'Az Incidens leírásának megadása kötelező!';
$_['error_missing_commissioner_email'] = 'Az adatvédelmi Hatóság e-mail címénak megadása kötelező!';
$_['error_saving_breach_notification_failed'] = 'Nem sikerült elmenteni az Incidens Értesítést, próbáld újra';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'adatvedelmi_incidens_ertesites';
