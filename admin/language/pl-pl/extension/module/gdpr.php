<?php
// Module
$_['text_gdpr']             = 'RODO';
$_['text_gdpr_settings']             = 'Ustawienia modułu RODO';
$_['text_gdpr_report']             = 'Historia wniosków RODO';

// Heading
$_['heading_title']    = 'RODO';
$_['module_name'] = 'RODO';

// Buttons etc.
$_['button_save'] = 'Zapisz';
$_['button_cancel'] = 'Anuluj';

// Entry
$_['entry_admin']      = 'Tylko administratorzy';
$_['entry_status']     = 'Status';
$_['entry_name'] = 'Nazwa';
$_['entry_message_text'] = 'Wiadomość do wyświetlenia';
$_['entry_date_start'] = 'Od (YYYY-MM-DD)';
$_['entry_date_end'] = 'Do (YYYY-MM-DD)';
$_['entry_status'] = 'Status';

$_['entry_email_footer'] = 'Tekst w stopce raportu RODO';
$_['entry_email_header'] = 'Tekst w nagłówku raportu RODO';
$_['entry_locations_of_other_data'] = 'Inne serwisy gdzie przechowywane są dane klientów (np. Mailchimp)';
$_['entry_locations_of_servers'] = 'Lokalizacje serwerowni gdzie przechowywane są dane klientów';
$_['entry_max_requests_day'] = 'Liczba dozwolonych wniosków w jednym dniu';
$_['entry_pending_status'] = 'Nazwa statusu "oczekujący"';
$_['entry_confirmed_status'] = 'Nazwa statusu "potwierdzony"';
$_['entry_emailed_status'] = 'Nazwa statusu "wysłany"';
$_['entry_account_deleted_status'] = 'Nazwa statusu "konto usunięte"';
$_['entry_data_sent'] = 'Dane wysłane';
$_['entry_rejected'] = 'Wniosek odrzucony';
$_['entry_right_to_be_forgotten'] = 'Pozwalaj na usuwanie kont.';

// Help
$_['help_pending_status'] = 'Nazwa statusu wniosku RODO, który nie został jeszcze potwierdzony przez użytkownika. Nazwa ta bedzie wyświetlona w raporcie dla admnistratora oraz w raporcie RODO dla użytkownika.';
$_['help_confirmed_status'] = 'Nazwa statusu wniosku RODO, który został potwierdzony przez użytkownika, ale nie został jeszcze wysłany. Nazwa ta bedzie wyświetlona w raporcie dla admnistratora oraz w raporcie RODO dla użytkownika.';
$_['help_emailed_status'] = 'Nazwa statusu wniosku RODO, który został zakończony i wysłany do użytkownika. Nazwa ta bedzie wyświetlona w raporcie dla admnistratora oraz w raporcie RODO dla użytkownika.';
$_['help_account_deleted_status'] = 'Nazwa statusu wniosku RODO o usunięcie konta, który został zakończony (konto użytkownika zostało usunięte). Status ten będzie wyświetlany w raporcie dla admnistratora.';
$_['help_locations_of_other_data'] = 'Lista wszytkich dodatkowych miejsc i serwisów gdzie dane osobowe użytkowników są przechowywane. Przykłady: Mailchimp, Dokumenty Google, Facebook itd. Zawartość tego pola będzię dołączona do raportu RODO wysyłanego do użytkowników.';
$_['help_locations_of_servers'] = 'Lista wszystkich fizycznych lokalizacji, gdzie przechowywane są dane osobowe użytkowników oraz powiązane informacje, np. czy serwerownia spełnia wymagania RODO. Zawartość tego pola będzię dołączona do raportu RODO wysyłanego do użytkowników.';
$_['help_max_requests_day'] = 'Liczba wniosków jaką użytkownik może złożyć w ciągu jednego dnia. Liczba ta nie powinna być zbyt wysoka, aby uniknąć spamu i nadmiernego obciążenia serwera. Sugerowana maksymalna liczba to 3-5 wniosków na dzień.';
$_['help_right_to_be_forgotten'] = 'Wybierz \'Tak\' aby umożliwić klientom automatyczne usuwanie ich konta. Niektóre dane klientów pozostaną w bazie danych ale zostaną zanonimizowane. Zamówienia pozostaną nienaruszone.';

// Error
$_['error_permission'] = 'Uwaga: nie masz uprawnień aby modyfikować moduł RODO!';
$_['error_name'] = 'Nazwa jest zbyt krótka, powinna zawierać co najmniej jeden znak!';
$_['error_text'] = 'Tekst wiadomości jest zbyt długi, nie powinien przekraczać 5000 znaków!';
$_['error_date_start'] = 'Data jest nieprawidłowa!';
$_['error_date_end'] = 'Data jest nieprawidłowa!';

// Text
$_['text_module']      = 'Moduły';
$_['text_success']     = 'Sukces: Zmodyfikowałeś moduł RODO!';
$_['text_edit']        = 'Edytuj';

// Added in 1.4
$_['entry_store_policy_acceptance'] = 'Zapisuj historię akceptacji polityki prywatności';
$_['entry_forms_are_private'] = 'Formularze wniosków wymagają zalogowania użytkownia?';

$_['help_store_policy_acceptance'] = 'Jeśli wybierzesz \'Tak\', za każdym razem kiedy klient zaakceptuje regulamin przy rejestracji lub w trakcie składania zamówienia, kopia regulaminu zostanie zapisana wraz z datą i adresem email klienta. ### UWAGA ### Jeśli wybierzesz \'Tak\' upewnij się że rejestracja i proces składania zamówienia w Twoim sklepie działa prafidłowo!';
$_['help_forms_are_private'] = 'Jeśli wybierzesz \'Tak\' wnioski będą mogłybyć składane tylko przez zalogowanych klientów';

// Added in 1.6 - Restriction of Processing
$_['entry_gdpr_restrict_processing'] = 'Ograniczenie Przetwarzania Danych';
$_['heading_gdpr_restrict_processing'] = 'Ogranicz dalsze przetwarzanie Twoich danych osobowych';
$_['help_gdpr_restrict_processing'] = 'Jeżeli chcesz ograniczyć przetwaranie Twoich danych osobowych wybierz \'Tak\'. Zachowujemy prawo to wykorzystania Twoich danych osobowych do zrealizownia złożonych przez Ciebie i nie zakończonych zamówień.';
$_['text_success_gdpr_restriction_of_processing'] = 'Preferencje ograniczenia przetwarzania danych osobowych zostały zmienione.';
