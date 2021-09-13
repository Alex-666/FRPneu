<?php

// Buttons
$_['button_add_breach'] = 'Pridėti duomenų pažeidimo incidentą';
$_['button_download_pdf'] = 'Atsisiųskite pranešimo laišką PDF formatu';
$_['button_email'] = 'Siųsti laiškus';
$_['button_generate_customer_notifications'] = 'Kurti pranešimus klientams';
$_['button_preview_customer_notification'] = 'Peržiūra';
$_['button_save_breach'] = 'Saugoti';
$_['button_send'] = 'Siųskite pranešimus el. Paštu';

// Columns
$_['column_action'] = 'Veiksmas';
$_['column_bcc_to'] = 'BCC\' siuntimo būdas kam';
$_['column_breach_id'] = 'pažeidimo ID';
$_['column_customer_email'] = 'El. pašto adresas';
$_['column_customer_id'] = 'Kliento ID';
$_['column_customer_name'] = 'Vardas';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'Pridėjimo ID';
$_['column_date_of_breach']	= 'Pažeidimo data';
$_['column_date_of_discovery'] = 'Atradimo data';
$_['column_date_updated'] = 'Paskutinį kartą atnaujinta';
$_['column_number_of_accounts_affected'] = 'Paveiktų paskyrų skaičius';
$_['column_breach_name'] = 'Pažeidimo pavadinimas';
$_['column_sent_to'] = 'El. laiškai išsiųsti';
$_['column_status'] = 'Komisijos nario pranešimo būsena';
$_['column_status_customers'] = 'Klientų pranešimų būsena';
$_['column_status_notification'] = 'Klientų El. laiškų būsena';

// Heading
$_['heading_title']        = 'Pranešimas apie saugumo pažeidimą';
$_['heading_title_customers']        = 'Pranešimas apie saugumo pažeidimą';
$_['heading_title_customers_send_emails'] = 'Siųsti el. laiško pranešimą klientams';
$_['heading_detailed'] = 'Pranešimas apie duomenų saugumo pažeidimą duomenų apsaugos komisijos nariui';
$_['heading_detailed_customers'] = 'Pranešimas apie duomenų saugumo pažeidimą klientams';
$_['heading_detailed_customers_send_emails'] = 'Siųsti pranešimą klientams partijomis. Šis metodas nerekomenduojamas jeigu turite didelį kiekį klientų.';

// Text
$_['text_breach_email_subject'] = 'Asmeninis duomenų pažeidimo pranešimas';
$_['text_commissioner'] = 'Duomenų apsaugos komisijos narys';
$_['text_customers'] = 'Klientai';
$_['text_data_breach'] = 'Duomenų pažeidimų ataskaitų teikimas';
$_['text_data_breach_commissioner_list'] = 'Įrašyti incidentai';
$_['text_data_breach_commissioner_form'] = 'Įrašyti naują incidentą';
$_['text_data_breach_customer_list'] = 'Generuoti pranešimus';
$_['text_data_breach_customer_emails'] = 'Įrašyti el. laiškų pranešimai';
$_['text_data_breach_customer_emails_send'] = 'Siųsti laiškus';
$_['text_data_breach_customer_csv'] = 'Parsisiųsti klientų sąrašą';


$_['text_email_commissioner_body'] = 'Prašome rasti pridedamą pranešimą apie duomenų pažeidimą';
$_['text_email_commissioner_subject'] = 'pranešimas apie duomenų pažeidimą';
$_['text_section_commissioner'] = 'informacija duomenų apsaugos komisijos nariui';
$_['text_section_customer'] = 'informacija klientams';
$_['text_section_general'] = 'Pagrindinė informacija';
$_['text_success']         = 'Jūsų žinutė sėkmingai išsiųsta!';
$_['text_success_saved_record']= 'Jūsų pažeidimo pranešimų įrašas buvo sėkmingai išsaugotas. Atkreipkite dėmesį, kad pranešimas vis dar neišsiųstas, norėdami tai padaryti atsisiųsti laiško PDF versiją, arba naudokite mygtuką  \'siųsti laišką komisijos nariui\'';
$_['text_success_generate_customer_notifications']         = 'jūsų pranešimas klientams buvo sugeneruotas (prašome prisiminti, kad niekas dar nebuvo išsiųsta)!';
$_['text_sent']            = 'Jūsų žinutė buvo sėkmingai išsiusta %s iš %s gavėjų!';
$_['text_notifications_emailed'] = 'Jūsų išsiųsti pranešimai:';
$_['text_notifications_pending'] = 'Jūsų nepatvirtinti pranešimai:';
$_['text_total_customers'] = 'Bendras klientų paskyrų skaičius jūsų sistemoje yra';


$_['text_from']         = 'Nuo:';
$_['text_to']         = 'Kam:';
$_['text_date']         = 'Data';
$_['text_default']         = 'Numatytas';
$_['text_email'] = 'Siųsti laiškus';
$_['text_header_cron'] = 'CRON Setup (rekomenduojama)';
$_['text_header_log'] = 'Logas';
$_['text_hour'] = 'valanda';
$_['text_hours'] = 'valandos';
$_['text_instructions']    = 'numatytas';
$_['text_instructions_customers_send_emails'] = 'Ši forma leidžia jums rankiniu būdu siųsti el. Pašto pranešimus savo klientams. Jums reikia pasirinkti tinkamiausius jūsų hostingo/serverio nustatymus. Atminkite, kad jei serverio apribojimai siųsti laiškus yra mažai, tai gali užtrukti ganėtinai ilgai. Štai kodėl mes rekomenduojame nustatyti cron\'o užduotį, kuri bus automatiškai paleidžiama (išsamesnės informacijos rasite žemiau). Šis scenarijus bus taikomas tik pranešimams, kurie dar nebuvo išsiųsti. Kiekvienas klientui išsiųstas el. Laiškas bus pažymėtas kaip siunčiamas duomenų bazėje, kad galėtumėte stebėti, kurie pranešimai buvo išsiųsti.';
$_['text_instructions_log'] = 'El. Laiškų, išsiųstų klientams, sąrašas yra "Opencart" žurnalo aplanke:';
$_['text_instructions_cron'] = 'Kad jūsų serveris automatiškai išsiųstų jūsų el. Laiškus, nustatykite cron\'o užduotį naudodami šį URL adresą, kuriame \ "max_runtime \" yra maksimalus serverio leidžiamas laikas naudotis programa/scriptu, o \ "batch_size \" yra maksimalus el. Laiškų skaičius kurį galima siųsti per vieną valandą:';
$_['text_minutes'] = 'minutės';
$_['text_newsletter']      = 'Visi naujienlaiškio prenumeratoriai';
$_['text_customer_all']    = 'Visi klientai';
$_['text_list'] = 'Įrašyti įsilaužimai (Duomenų apsaugos komisijos nariui)';
$_['text_list_customers'] = 'Įrašyti įsilaužimai (klientams)';
$_['text_product']         = 'produktai';
$_['text_add_breach']         = 'Pridėti naują įsilaužimą';
$_['text_return'] = 'grįžti';
$_['text_save_breach']         = 'išsaugoti';
$_['text_signature'] = 'Su pagarba,';
$_['text_status_generated']         = 'Sugeneruota';
$_['text_status_pending']         = 'nepatvirtinta';
$_['text_status_sent']         = 'išsiųsta';
$_['text_status_unknown']         = 'nežinoma';
$_['text_status_failed']         = 'neteisingas el. paštas';
$_['text_success_email_batch'] = 'El. Laiškus dabar išsiųs jūsų serveris. Prašome peržiūrėti pranešimų sąrašą:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'We hereby report a data security breach incident that may involve personal information.';
$_['text_report_to_commissioner_intro'] = 'Šia žinute mes pranešame apie duomenų saugumo pažeidimo incidentą, kuris gali būti susijęs su Jūsų asmenine informacija.';
$_['text_report_to_commissioner_contact'] = 'Mūsų kontaktinis asmuo dėl šio incidento yra';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = '%s mes aptikome galimą duomenų įsilaužimą, kuris aprašytas žemiau:';
$_['text_report_to_commissioner_data_exposed'] = 'Duomenys kuriuos galėjo pasiekti:';
$_['text_report_to_commissioner_actions_taken'] = 'Mes ėmėmės šių priemonių šiam incidentui ištaisyti:';
$_['text_report_to_commissioner_ending'] = 'Atliekame išsamią potencialiai paveiktų sistemų apžiūrą ir informuosime Duomenų apsaugos komisijos narį apie esminius pokyčius. Mes įgyvendiname papildomas saugumo priemones, skirtas užkirsti kelią tokio išpuolio pasikartojimui ir apsaugoti mūsų klientų privatumą';

$_['text_send_breach_notification'] = 'Siųsti pažeidimo pranešimą';

// Entry
$_['entry_address_commissioner'] = 'Duomenų apsaugos komisijos narys (pašto adresas)';
$_['entry_address_store'] = 'parduotuvė (pašto adresas)';
$_['entry_batch_size'] = 'partijos dydis';
$_['entry_breach_notification_status'] = 'statusas';
$_['entry_breach_customer_email_notification_status'] = 'El laiškų pranešimo būklė/statusas';
$_['entry_contact_details_of_person_reporting']       = 'Kontaktai duomenų pažeidimo komisijos nariui';
$_['entry_contact_email']       = 'kontaktinis paštas klientams';
$_['entry_customer_email']       = 'el. paštas';
$_['entry_customer_group'] = 'klientų grupė';
$_['entry_customer']       = 'Klientas';
$_['entry_date_added']       = 'pridėjimo data';
$_['entry_date_of_breach']       = 'pažeidimo data(os) (jeigu žinomi)';
$_['entry_date_of_discovery']       = 'Data kai buvo aptiktas incidentas';
$_['entry_email_bcc']       = 'BCC';
$_['entry_email_commissioner'] = 'Duomenų apsaugos komisijos narys (el. pašto adresas)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Maximum runtime';
$_['entry_message_action']        = 'Trumpas bet kokio veiksmo apibūdinimas, kai pažeidimas buvo nustatytas (Komisijos nariui)';
$_['entry_message_action_customers']        = 'Trumpas atliktų veiksmų aprašymas (klientams)';
$_['entry_message_incident']       = 'Trumpas pažeidimo aprašymas (Komisijos nariui)';
$_['entry_message_incident_customers']       = 'Trumpas pažeidimo aprašymas (Klientams)';
$_['entry_name']       = 'Incidento vardas (jūsų naudojimui)';
$_['entry_number_of_accounts_affected']       = 'Kiek paskytų buvo paveikta (jeigu žinoma)';
$_['entry_store']          = 'Nuo';
$_['entry_subject']        = 'Tema (Duomenų pažeidimo komisijos nariui)';
$_['entry_subject_customers']        = 'Tema (Klientams)';
$_['entry_to']             = 'Kam';

// Help--------------------------------------------------------------------------------------------------
$_['help_address_commissioner'] = 'Visas duomenų apsaugos komisijos nario pašto adresas. Tai bus naudojama pranešimo apie pažeidimą laiške';
$_['help_address_store'] = 'Visas jūsų parduotuvės, kuri praneša apie pažeidimą, pašto adresas. Tai bus naudojama laiško antraštėje';
$_['help_batch_size'] = 'Skaičius el. Laiškų, kuriuos galima siųsti per vieną valandą. Tai priklausys nuo jūsų serverio konfigūracijos. Dauguma shared(pigių) serverių jį labai apribos, todėl jums nebus leidžiama siųsti daugiau kaip 100-200 laiškų per valandą.';
$_['help_contact_details_of_person_reporting'] = 'Įrašykite asmens, su kuriuo gali susisiekti Duomenų apsaugos komisijos narys, el. Pašto adresą ir (arba) telefono numerį';
$_['help_contact_email'] = 	'Pridėkite el. Pašto adresą, kuriuo klientai gali susisiekti dėl duomenų pažeidimo.';
$_['help_data_commissioner']       = 'Duomenų apsaugos komisijos įgaliotinio, esančio jūsų jurisdikcijoje, elektroninio pašto adresas';
$_['help_date_of_breach'] = 'Jei nežinote tikslios datos, nurodykite spėjamą laiko tarpą';
$_['help_date_of_discovery'] = 'Data, kai atradote, kad jūsų sistema buvo pažeista';
$_['help_email_bcc']       = 'Elektroninio pašto adresai kuuos norite įklijuoti į pranešimą apie pažeidimą.';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Maximalus elektroninių laišku siuntimo laikas.';
$_['help_message_action'] = 'Aprašykite visus veiksmus, kuriuos atlikote nuo pažeidimo atradimo. Šis tekstas bus įtrauktas į laišką duomenų apsaugos komisijos nariui.';
$_['help_message_action_customers'] = 'Aprašykite visus veiksmus, kuriuos atlikote nuo pažeidimo atradimo. Šis tekstas bus įtrauktas į laiškus siunčiamus klientams.';
$_['help_message_incident'] = 'Išsamiai apibūdinkite pažeidimą, kas paėmė duomenis, kaip jie tai padarė, kaip jis/jie buvo aptiktas(i) ir tt. Šis tekstas bus įtrauktas į laišką duomenų apsaugos komisijos nariui.';
$_['help_message_incident_customers'] = 'Apibūdinkite pažeidimą su bet kokia informacija, naudinga jūsų klientams. Šis tekstas bus įtrauktas į el. Laiškus klientams.';
$_['help_name'] = 'Šis pavadinimas skirtas tik parduotuvės savininkui / administratoriui, jis nebus atskleistas Duomenų komisarui ar Klientams';
$_['help_number_of_accounts_affected'] = 'Nurodykite, kiek paskyrų (klientai / duomenų subjektai) buvo pažeista pažeidimo metu';
$_['help_subject']       = 'laiško pavadinimas duomenų apsaugos komisijos nariui.';
$_['help_subject_customers']       = 'laiško pavadinimas Klientams';

// Error
$_['error_address_commissioner']        = 'Duomenų apsaugos komisijos nario adresas yra privalomas!';
$_['error_address_store']        = 'parduotuvės adresas yra privalomas!';
$_['error_contact_details_of_person_reporting']        = 'Žmogaus siunčiančio ataskaitą informacija yra privaloma!';
$_['error_customer_notification_add'] = 'Nepavyko pridėti pranešimų klientams';
$_['error_customer_notification_existing'] = 'jau egzistuoja pranešimas apie šį duomenų pažeidimą';
$_['error_data_commissioner']        = 'Duomenų apsaugos komisijos nario el. pašto adresas yra privalomas!';
$_['error_date_of_breach']        = 'pažeidimo data yra privaloma!';
$_['error_date_of_discovery']        = 'duomenų pažeidimo aptikimo data yra privaloma!';
$_['error_email_batch'] = 'Jūsų el. Pašto pranešimai nepavyko išsiųsti, bandykite dar kartą.';
$_['error_permission']     = 'Įspėjimas: neturite leidimo siųsti pranešimus apie pažeidimus elektroniniu paštu!';
$_['error_subject']        = 'Pranešimo apie pažeidimą el. pašto adresas yra reikalaujamas!';
$_['error_mail_bcc'] = 'El. Pašto formatas neteisingas, nurodykite el. Pašto adresų sąrašą atskirtą kableliais';
$_['error_mail_commissioner'] = 'El. laiškas duomenų apsaugos komisijos nariui yra neteisingu formatu.';
$_['error_message_action']        = 'Veiksmų, kurių buvo imtasi po pažeidimo, santrauka privaloma!';
$_['error_message_incident']        = 'Būtina aprašyti įvykio / pažeidimo aprašymą!';
$_['error_missing_commissioner_email'] = 'Duomenų apsaugos komisijos nario el. Pašto adresas yra privalomas laukelis!';
$_['error_saving_breach_notification_failed'] = 'Nepavyko išsaugoti pranešimo apie pažeidimą įrašo, bandykite dar kartą.';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'Duomenu_pazeidimo_pranesimas';
