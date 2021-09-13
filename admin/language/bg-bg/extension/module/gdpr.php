<?php
// Module
$_['text_gdpr']             = 'GDPR';
$_['text_gdpr_settings']             = 'GDPR Настройки';
$_['text_gdpr_report']             = 'История на запитване по GDPR';

// Heading
$_['heading_title']    = 'GDPR';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = 'Запази';
$_['button_cancel'] = 'Отмени';

// Entry
$_['entry_admin']      = 'Само за Администратори';
$_['entry_status']     = 'Статус';
$_['entry_name'] = 'Име';
$_['entry_message_text'] = 'Съобщение за показване';
$_['entry_date_start'] = 'Начална дата (YYYY-MM-DD)';
$_['entry_date_end'] = 'Крайна дата (YYYY-MM-DD)';
$_['entry_status'] = 'Статус';

$_['entry_email_footer'] = 'GDPR Email футър текст';
$_['entry_email_header'] = 'GDPR Email хедър текст';
$_['entry_locations_of_other_data'] = 'Други местоположения/услуги, където съхранявате лични данни';
$_['entry_locations_of_servers'] = 'Физически местоположения на сървъри, на които хоствате вашия уеб сайт и други данни.';
$_['entry_max_requests_day'] = 'Максимален брой на запитвания на ден';
$_['entry_pending_status'] = 'Текст при статус на изчакване';
$_['entry_confirmed_status'] = 'Текст при потвърден статус';
$_['entry_emailed_status'] = 'Текст при статус "Изпратено по мейл"';
$_['entry_account_deleted_status'] = 'Текст при статус Изтрит акаунт';
$_['entry_data_sent'] = 'Данните са изпратени';
$_['entry_unpaid'] = 'Неплатен текст';
$_['entry_free'] = 'Безплатен текст';
$_['entry_rejected'] = 'Отказан текст';
$_['entry_fairuse'] = 'Слаба употреба';
$_['entry_max_days_process'] = 'Максимален брой дни за отговор';
$_['entry_right_to_be_forgotten'] = 'Включване на формата за право да бъдеш забравен';

// Help
$_['help_pending_status'] = 'Име на статуса на искането по GDPR, което не е потвърдено от потребителя. Този отчет за състоянието на искането по GDPR ще бъде показан на администратора и ще бъде показан на потребителя в историческите записи на техните запитвания по GDPR.';
$_['help_confirmed_status'] = 'Име на статуса на искане по GDPR, потвърдено от потребителя чрез връзката за потвърждаване на имейл, но все още не е обработено (все още не е изпратен отчет на електронната поща). Този отчет за състоянието на искането по GDPR ще бъде показан на администратора и ще бъде показан на потребителя в историческите записи на техните запитвания по GDPR.';
$_['help_emailed_status'] = 'Име на искането по GDPR, което е обработено / завършено (имейлът е изпратен на потребителя). Този отчет за състоянието на искането по GDPR ще бъде показан на администратора и ще бъде показан на потребителя в историческите записи на техните запитвания по GDPR.';
$_['help_account_deleted_status'] = 'Име на искането за премахване на профила по GDPR, което е обработено / завършено. Този отчет за заявката по GDPR ще бъде показан на администратора.';
$_['help_locations_of_other_data'] = 'Посочете всички други местоположения и уеб услуги, където съхранявате личните данни на клиентите тук. Примери: Mailchimp, Google Документи и др. Тази информация ще бъде включена в имейл съобщението по GDPR, изпратено до потребителя.';
$_['help_locations_of_servers'] = 'Посочете цялата свързана информация за хостинга и сървъра (например държави, където се намират хостинг сървърите, хостинг доставчици, доставчиците на хостинг услуги дали отговарят на GDPR и т.н.).';
$_['help_max_requests_day'] = 'Брой заявки, които клиентът може да изпраща на ден. Това трябва да бъде настроено на малък брой, за да се предотвратят заявки за спам, атаки и ненужно зареждане на вашия уеб сървър. Препоръчваме стойност между най-много 3-5.';
$_['help_right_to_be_forgotten'] = 'Това ще позволи формуляра \"Право да бъдеш забравен\", при който клиентите могат автоматично да изтрият своят профил и да анонимизират лични данни, когато не могат да бъдат изтрити ';

// Error
$_['error_permission'] = 'Внимание: Вие нямате права да променяте GDPR модула!';

// Text
$_['text_module']      = 'Модули';
$_['text_success']     = 'Успешно променихте GDPR модула!';
$_['text_edit']        = 'Редакция на GDPR модул';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Store Policy Acceptance';
$_['entry_forms_are_private'] = 'GDPR Forms Require Login';

$_['help_store_policy_acceptance'] = 'If set to yes, every time customer accepts your terms on the registration page or in the checkout, this will be recorded in the database. IMPORTANT: if you set it to yes please make sure you checkout is working correctly!';
$_['help_forms_are_private'] = 'If set to yes, GDPR request can only be submitted by a logged in customer.';
