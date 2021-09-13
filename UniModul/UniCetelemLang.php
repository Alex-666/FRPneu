<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-14-g21587217:2020-02-29#

$dict['cs']['payment_method_name'] = 'Hello bank! - nákup na splátky';
$dict['en']['payment_method_name'] = 'Hello bank! - installment purchase';

$dict['cs']['orderDetailGwOrdNum'] = 'Číslo autorizace nebo pracovní Hello bank!';
$dict['en']['orderDetailGwOrdNum'] = 'Hello bank! authorization or work number';

$dict['cs']['amountTooLow'] = 'Placená částka nedosahuje požadováného minima ve výši 3 000 Kč.';
$dict['en']['amountTooLow'] = 'Amount is below required minimum 3 000 CZK.';


// chyby

$dict['cs']['Cetelem_E_SUCCESS'] = 'Nákup na splátky prostřednictvím Hello bank! byl schválen.';
$dict['en']['Cetelem_E_SUCCESS'] = 'Installment purchase using Hello bank! was approved online.';

$dict['cs']['Cetelem_E_PENDING'] = 'Nákup na splátky prostřednictvím Hello bank! nebyl schválen online, bude posouzen manuálně.';
$dict['en']['Cetelem_E_PENDING'] = 'Installment purchase using Hello bank! was not approved online. It is waiting for manual approval.';


// admin

// změna nazvu std ke stavum obj

$dict['cs']['orderStatusSuccessfull'] = 'Stav objednávky po schválení úvěru online';
$dict['en']['orderStatusSuccessfull'] = 'Order state after loan online approval';
unset($dict['sk']['orderStatusSuccessfull']);
unset($dict['ru']['orderStatusSuccessfull']);
unset($dict['es']['orderStatusSuccessfull']);

$dict['cs']['orderStatusPending'] = 'Stav objednávky při čekání na návrat zákazníka z brány Hello bank!';
$dict['en']['orderStatusPending'] = 'Order state when waiting for customer return from Hello bank!';
unset($dict['sk']['orderStatusPending']);
unset($dict['ru']['orderStatusPending']);
unset($dict['es']['orderStatusPending']);

$dict['cs']['orderStatusFailed'] = 'Stav objednávky při neschválení úvěru online';
$dict['en']['orderStatusFailed'] = 'Order state after loan was not approved online';
unset($dict['sk']['orderStatusFailed']);
unset($dict['ru']['orderStatusFailed']);
unset($dict['es']['orderStatusFailed']);



$dict['cs']['kodProdejce'] = 'Kód prodejce Hello bank!';
$dict['en']['kodProdejce'] = 'Seller code Hello bank!';

$dict['cs']['cetelemUrl'] = 'Url brány Hello bank!';
$dict['en']['cetelemUrl'] = 'Url of Hello bank!';

$dict['cs']['gwOrderNumberOffset'] = 'Číslo první platby na platební bráně (nastavit na 1000 a pak už neměnit)';
$dict['en']['gwOrderNumberOffset'] = 'Gateway order number offset (recommended value 1000, do not change after it is once set)';



