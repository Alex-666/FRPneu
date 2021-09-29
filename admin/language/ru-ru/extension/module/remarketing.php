<?php
// Heading
$_['heading_title']    = '<span style="color:red; font-weight:bold;"><i class="fa fa-rocket"></i> SP</span> Remarketing All In One Pro - v5.0';

// Text
$_['text_extension']    = 'Модули';
$_['text_success']      = 'Настройки модуля обновлены!';
$_['text_edit']         = 'Редактирование модуля';
$_['text_id']   	   	= 'ID товара';
$_['text_model']        = 'Модель';
$_['text_events']       = 'События';
$_['text_esputnik']       = 'eSputnik';
$_['text_tiktok']       = 'TikTok (Beta)';
$_['text_not_selected']       = 'Не выбрано';
$_['text_identifier']   = 'Идентификатор товара';
$_['text_google_remarketing']        = 'Ремаркетинг Google';
$_['text_facebook_remarketing']        = 'Ремаркетинг Facebook';
$_['text_mytarget_remarketing']        = 'Ремаркетинг Mytarget';
$_['text_vk_remarketing']        = 'Ретаргетинг VK';
$_['text_retail_rocket']        = 'Retail Rocket';
$_['text_ecommerce']        = 'Ecommerce Google и Яндекс';
$_['text_ecommerce_ga4']        = 'Ecommerce Google GA4';
$_['text_ecommerce_ga4_measurement']        = 'Ecommerce Google GA4 - Measurement Protocol ';
$_['text_ecommerce_measurement']        = 'Ecommerce - Measurement Protocol (без gtag.js и dataLayer)';
$_['text_counters']        = 'Счетчики';
$_['text_to_be_continued']        = 'Скоро будет еще что-то интересное';
$_['text_instruction']        = 'Возможности модуля и документация';
$_['text_help']        = 'Помощь и поддержка';
$_['text_summary']        = 'Сводка'; 
$_['text_reporting']      = 'Отчеты по заказам (Experimental)'; 
$_['text_feed']        = 'Фид для Google Merchant и Facebook'; 
$_['text_diagnostics']    = 'Основные Настройки и Диагностика'; 
$_['text_check_config']    = 'Сводка'; 
$_['text_version']    = 'Версия модуля'; 
$_['text_check_install']    = 'Диагностика установки модификатора'; 
$_['text_help_link']    = 'Ссылка на документацию'; 
$_['text_feed_merchant']    = 'Google Merchant:'; 
$_['text_feed_facebook']    = 'Facebook:'; 
$_['text_forum_documentation']    = 'Документация и ответы на частые вопросы'; 
$_['text_feed_help']    = 'К ссылкам можно добавлять GET-параметры языка и валюты<br><br>Как пример - language=ru-ru - фид принудительно будет на русском языке<br>currency=USD - фид будет в USD<br>Язык и валюта должны существовать в магазине и быть включенными.'; 
$_['text_category_google']    = 'Скачать список категорий Google'; 
$_['text_telegram']    = 'Telegram - Bonus, No Support'; 
$_['text_copy_to_category']    = 'Скопировать названия категорий в google_product_category'; 
$_['text_copy_to_product_type']    = 'Скопировать названия категорий в product_type'; 
$_['button_test_facebook']    = 'Протестировать соединение'; 
$_['entry_category'] = 'Категории';
$_['entry_manufacturer'] = 'Производители';
$_['entry_customer_group'] = 'Цена для группы покупателей';

$_['text_help_google']        = '<div style="color:red; font-size:20px; font-weight:bold;">Инструкция</div><br>
<b> Где взять AW-CONVERSION_ID?</b><br>
Инструкция - <a href="https://support.google.com/google-ads/answer/6095821?hl=ru" target="_blank"> ссылка</a><br>
Читаем, находим в примере - gtag(\'config\', \'AW-CONVERSION_ID\') - берем код для своего аккаунта<br><br>
<b> Где взять AW-CONVERSION_ID/AW-CONVERSION_LABEL?</b><br>
Инструкция - <a href="https://support.google.com/google-ads/answer/6095821?hl=ru" target="_blank"> ссылка</a><br>
Читаем, находим в примере -  gtag(\'event\', \'conversion\', {\'send_to\': \'AW-CONVERSION_ID/AW-CONVERSION_LABEL\', - берем код для своего аккаунта<br><br>
'; 
$_['text_help_facebook']        = '<div style="color:red; font-size:20px; font-weight:bold;">Инструкция</div><br>
<b> Где взять ID пикселя Facebook?</b><br>
Инструкция - <a href="https://www.facebook.com/business/help/952192354843755?id=1205376682832142" target="_blank"> ссылка</a><br>
Ищем в коде для вставки на сайт -   fbq(\'init\', \'<b>111111111111111111</b>\'); - это он<br><br>
'; 

// Entry
$_['entry_status']     = 'Статус';
$_['entry_bot_status']     = 'Выводить модуль для ботов (не рекомендуется)';
$_['entry_log']     = 'Включить логирование событий  (для отладки, если постоянно включено займет много дискового пространства со временем)';
$_['entry_feed_status']     = 'Статус простого фида';
$_['entry_feed_link']     = 'Ссылка на фид';
$_['entry_google_identifier']     = 'Идентификатор Google Adwords (AW-CONVERSION_ID)<br><div style="color:red">ВСТАВЛЯТЬ НАДО ВМЕСТЕ С AW-</div>';
$_['entry_google_gtag_status']     = 'Подключить тег Adwords gtag.js (если еще не подключен)';
$_['entry_facebook_script_status']     = 'Подключить пиксель Facebook (если еще не подключен)';
$_['entry_google_ads_identifier']     = 'Идентификатор конверсии Google Adwords для оформления заказа<br>
(AW-CONVERSION_ID/AW-CONVERSION_LABEL)'; 
$_['entry_google_ads_identifier_cart']     = 'Идентификатор конверсии Google Adwords для добавления в корзину<br>
(AW-CONVERSION_ID/AW-CONVERSION_LABEL)';
$_['entry_google_ads_identifier_cart_page']     = 'Идентификатор конверсии Google Adwords для открытия корзины<br>
(AW-CONVERSION_ID/AW-CONVERSION_LABEL)';
$_['entry_google_ads_ratio']     = 'Коэффициент ценности конверсии (0.7 - 70%, 1 - 100% от суммы и тп)';
$_['entry_facebook_identifier']     = 'ID пикселя Facebook ';
$_['entry_facebook_token']     = 'Маркер доступа для Consversions API';
$_['entry_facebook_api_ver']     = 'Версия API';
$_['entry_facebook_test_code']     = 'Код для тестирования';
$_['entry_facebook_pixel_status']     = 'События пикселя Facebook';
$_['entry_facebook_lead']     = 'Отправка события Lead при успешном заказе';
$_['entry_facebook_depth']     = 'Отслеживание глубины прокрутки (ViewContentCheckPoint)';
$_['entry_facebook_depth_params']     = 'Проценты глубины прокрутки через зпт в % (как пример - 10,50,90)';
$_['entry_mytarget_identifier']     = '№ списка в Mytarget';
$_['entry_vk_identifier']     = '№ списка в VK '; 
$_['entry_google_code']     = 'Код Adwords (если еще не установлен)';
$_['entry_facebook_code']   = 'Код пикселя Facebook (если еще не установлен)';
$_['entry_server_side']   = 'Включить Conversions API (отправка событий с сервера)';
$_['entry_mytarget_code']   = 'Код счетчика Mail.ru (если еще не установлен)';
$_['entry_currency']   = 'Валюта';
$_['entry_google_merchant_identifier']   = 'ID Google Merchant';
$_['entry_reviews_date']   = 'Прибавить дней к текущей дате';
$_['entry_reviews_country']   = 'Страна доставки (RU, UA)';
$_['entry_events_cart']   = 'Javascript cобытие открытия корзины';
$_['entry_events_cart_add']   = 'Javascript событие добавления товара в корзину';
$_['entry_events_purchase']   = 'Javascript событие оформления заказа<br>Можно использовать переменные: {order_id} и {order_total}';
$_['entry_events_wishlist']   = 'Javascript событие добавления в закладки';
$_['entry_esputnik_login']   = 'Логин в eSputnik';
$_['entry_esputnik_password']   = 'Пароль или ключ (лучше) API. Если ключ API - логин любой';
$_['entry_esputnik_address_format']   = 'Формат адреса (аналогично Opencart, {firstname}, {lastname}, {address_1} и тп)';
$_['entry_esputnik_ttn_field']   = 'Поле в котором содержится ТТН для отправки (должно быть доступно в $order_info), передается параметр ttn';
$_['entry_esputnik_initialized_status']   = 'Статус для INITIALIZED (новый заказ)';
$_['entry_esputnik_inprogress_status']   = 'Статус для IN_PROGRESS';
$_['entry_esputnik_delivered_status']   = 'Статус для DELIVERED';
$_['entry_esputnik_cancelled_status']   = 'Статус для CANCELLED';
$_['entry_ecommerce_selector']   = 'Javascript селектор карточки товара (например, .product-thumb)';
$_['entry_ecommerce_analytics_id']   = 'ID Google Analytics (UA-XXXXXXXXX)';
$_['entry_ecommerce_ga4_analytics_id']   = 'ID Google Analytics (G-XXXXXXXXX)';
$_['entry_ecommerce_ga4_identifier']   = 'ID Google Analytics (G-XXXXXXXXX)';
$_['entry_ecommerce_ga4_api_secret']   = 'Секретный ключ';
$_['entry_remarketing_ecommerce_ga4_send_status']   = 'Статусы для отправки заказа в GA 4';
$_['entry_remarketing_ecommerce_ga4_refund_status']   = 'Статусы для отправки Refund';
$_['entry_ecommerce_send_status']   = 'Статус заказа для отправки в аналитику';
$_['entry_facebook_send_status']   = 'Статус заказа для отправки в Facebook';
$_['entry_refund_status']   = 'Статус заказа для отправки Refund (возврат)';
$_['entry_resend_status']   = 'Статус заказа для "ручной" отправки в аналитику (если не попал по каким-либо причинам, иначе будет дубль!)';
$_['entry_delete_status']   = 'Статус заказа для "удаления" транзакции (формируется транзакция с отрицательной суммой заказа и кол-вом товара (!)на данный момент, т.е. если заказ был изменен в аналитике отменятся измененные данные)';
$_['entry_refund_ga4_status']   = 'Статус заказа для отправки Refund (возврат)';
$_['entry_retailrocket_identifier']   = 'Партнерский ID Retail Rocket'; 
$_['entry_retailrocket_email_field']   = 'Jquery селектор для полей с email'; 
$_['entry_counter1']   = 'Счетчики в хэдере';
$_['entry_counter2']   = 'Счетчики после открывающегося body';
$_['entry_counter3']   = 'Счетчики в подвале';
$_['entry_counter_bot']   = 'Счетчики которые видны ботам (проверки meta, скрипты, которые должны быть видны ботам)';
$_['entry_remarketing_feed_original_image_status']   = 'Выгружать оригиналы картинок (быстрее)';
$_['entry_remarketing_feed_key']   = 'Ключ защиты ссылок (рекомендуется)';
$_['entry_remarketing_feed_additional_images']   = 'Дополнительные картинки (медленнее)';
$_['entry_remarketing_feed_multiplier']   = 'Коэффициент наценки (1 - 100%, 0.7 - -30%, 1.2 - +20%)';
$_['entry_remarketing_feed_utm']   = 'UTM-метки. Переменные: {product_id}, {product_model}';
$_['entry_remarketing_feed_original_description']   = 'HTML-описание';
$_['entry_remarketing_feed_currency']   = 'Основная валюта фида';
$_['entry_remarketing_feed_custom_sql']   = 'Дополнительное условие SQL для выгрузки (пример AND p.export = 1 AND p.stock_status_id = 3). Использовать если знаете что делаете.';
$_['entry_remarketing_feed_special']   = 'Выгружать акционные цены';
$_['entry_remarketing_feed_min_price']   = 'Минимальная цена для выгрузки';
$_['entry_remarketing_feed_max_price']   = 'Максимальная цена для выгрузки';
$_['entry_remarketing_feed_gtin']   = 'Откуда брать GTIN (если используется). Пример: sku, upc, mpn и тп.';
$_['entry_remarketing_feed_mpn']   = 'Откуда брать MPN (если используется). Пример: sku, upc, mpn и тп.';
$_['entry_remarketing_feed_zero_quantity']   = 'Выгружать товары с количеством только больше 0';
$_['entry_remarketing_feed_condition']   = 'Condition (new - новый,refurbished - восстановленный, used - б/у)';
$_['entry_remarketing_feed_links']   = 'Ссылки на выгрузку';
$_['entry_remarketing_feed_tuning']   = 'Буду настраивать категории и понимаю что если у меня их много админка может немного тупить';
$_['entry_manual_send']   = 'Ручная отправка (использовать только если понимаете что делаете)<br><b style="color:red;">Экспериментальная функция!</b>';
$_['entry_telegram_bot_id']   = 'ID (токен) бота';
$_['entry_telegram_send_to_id']   = 'Id кому слать (можно несколько через зпт)';
$_['entry_telegram_send_status']   = 'Статусы при которых посылать уведомление';
$_['entry_telegram_message']   = 'Сообщение';
 
// Error
$_['error_permission'] = 'У вас нет прав для управления этим модулем!';

$_['text_credits']        = '
<b>Спасибо за покупку моего модуля!</b><br>
<div class="text-credits">
Если вам необходима поддержка, доработка этого модуля либо какого другого - обращайтесь, буду рад помочь.<br><br>
Связаться со мной можно следующими способами:<br>
1. Личное сообщение на opencartforum - <a href="https://opencartforum.com/profile/678128-spectre/" target="_blank" style="display: inline-block;border-radius: 2px;padding: 1px 5px;font-size: 90%;color: #fff;text-decoration: none !important;background: #3d6594;">@spectre</a><br>
2. Электронная почта - <a href="mailto:job@freelancer.od.ua">job@freelancer.od.ua</a><br><br>

Если хотите поблагодарить или угостить пивом когда читаете эту страницу:<br>
1. Яндекс-Деньги - 41001189848733<br>
2. QIWI - SPECTREAV<br>
3. Приватбанк - 5168742228748467<br><br>

<b style="font-size:18px;color:red;">Удачных продаж! <i class="fa fa-hand-peace-o"></i></b>
</div>
';
$_['text_google_reviews']        = 'Отзывы в Google Merchant';
$_['text_events_help']        = ' <b>Важно!</b><p>Здесь можно добавить события для метрики, VK и тп.</p>
<p>Например, yaCounterXXXXXX.reachGoal("addtocart") или gtag("event", "cart_add", {"event_category": "Cart"});</p>
<p>Рекомендую проверять подобным образом:<br>
 if (typeof yaCounterXXXXXX != \'undefined\') { yaCounterXXXXXX.reachGoal("addtocart") }</p>
 <p>sсriрt /sсriрt писать не надо!</p>
 ';
 $_['text_instructions']        = '';
