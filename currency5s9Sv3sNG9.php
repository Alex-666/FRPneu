#!/usr/bin/php
<?php

require_once __DIR__ . '/config.php';

//  Берем курсы центробанка чехии
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://www.cnb.cz/cs/financni-trhy/devizovy-trh/kurzy-devizoveho-trhu/kurzy-devizoveho-trhu/denni_kurz.txt');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$content = curl_exec($curl);

//  Контента нет - ничего не делаем, выходим
if (empty($content)) exit;

//  Расщепляем на массив строк
$array = explode("\n", $content);

//  Если ошибка - ничего не делаем, выходим
if (!is_array($array)) exit;

//  Инициализация
$euro = '';

//  Ищем строку с евро
foreach ($array as $item) {

    $parts = explode("|", $item);

    //  Если ошибка - ничего не делаем, выходим
    if (!is_array($parts) || !count($parts)) exit;

    if (strtolower($parts[0]) == 'emu') {
        //  Берем значение, оно последнее
        $euro = end($parts);
        break;
    }
}

//  Если ошибка - ничего не делаем, выходим
if (empty($euro)) exit;

//  Заменяем запятую на точку
$euro = str_replace(",", ".", $euro);

//  Преобразовываем к типу флоат
$euro = floatval($euro);

//  Т.к. по умолчанию у нас используется чешская крона
//  то нужно найти соотношение сколько евро в одной кроне
$euro = 1 / $euro;

//  Количество знаков после запятой 8 (ограничение таблицы в базе)
$euro = number_format($euro, 8);

//  Время обновления
$date = date('Y-m-d H:i:s');

//  Обновляем курс евро
$db = new MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$db->set_charset('utf8');
$db->query("UPDATE " . DB_PREFIX . "currency SET value = '$euro', date_modified = '$date' WHERE code = 'EUR'");
$db->close();

exit;