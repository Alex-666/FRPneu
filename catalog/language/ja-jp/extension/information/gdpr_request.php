<?php

// Buttons
$_['button_submit']  = 'GDPRレポートリクエスト'; // OC15
$_['button_continue']  = '継続する'; // OC15

// Heading
$_['heading_title']  = 'GDPR個人データリクエスト';

// Text
$_['text_instructions']   = '<p>GDPRレポートを受け取るには、以下のステップに従ってください：<br>1. お客様のメールアドレスを以下のフォームに入力し、送信してください。 <br>2. 確認メールがお客様に送られますので、メール内にある確認ボタン/リンクをクリックしてください。<br>3. GDPRレポートがメールでお客様に送られます。</p>';

$_['text_success']   = '<p>確認メールをお送りしました。メール内のリンクをクリックしてメールアドレスにアクセスしたことを証明してください。確認完了後、お客様のGDPRリクエストを処理致します。</p>';

// Entry
$_['entry_captcha']    = '以下のボックスにコードを入力してください：'; // OC15
$_['entry_email']    = 'メールアドレス';

// Errors
$_['error_captcha']  = 'キャプチャ認証してください。';
$_['error_email']    = 'メールアドレスが無効です。';
$_['error_email_not_existing']    = 'メールアドレスが弊社のデータベースに見当たりません。メールアドレスにスペースなどの間違いが含まれていないかどうかを確認してください。上記メールアドレスが正しく、弊社ストアに上記メールアドレスにリンクされたアカウントをお持ちの場合には、弊社カスタマーサービスまでお問い合わせください。 ';
$_['$error_max_requests_day']    = '１日の最大GDPRリクエスト回数を超えました。24時間後に再度リクエストしてください。';

// Confirmation Email
$_['confirmation_email_text_heading'] = 'GRPRリクエストを確認してください。';
$_['confirmation_email_text_instructions'] = 'お客様の個人データレポートをリクエストされた場合には、 以下のリンクをクリックし、お客様のメールアドレスを確認してリクエスト処理を行なってください。リクエストをされていないお客様は本メールは無視してください。';
$_['confirmation_email_text_confirm'] = '確認する';
$_['confirmation_email_text_smallprint'] = '早急にお客様の個人データレポートを作成し、メールでお送り致します。通常、リクエストの確認から30分以内にレポートが送信されます。確認から24時間経ってもレポートが受信できない場合には、再度リクエスト頂くか、またはカスタマーサポートまでご連絡ください。 ';

// GDPR Report Email
$_['gdpr_report_email_subject'] = 'お客様のGDPR個人データレポート';

// Request success
$_['request_success_heading_title'] = 'お客様のGDPRリクエストは確認されました。';
$_['request_success_text_message'] = 'GDPR リクエストをご確認頂きましてありがとうございます。早急にお客様の個人データレポートを作成し、メールでお送り致します。通常、リクエストの確認から30分以内にレポートが送信されます。確認から24時間経ってもレポートが受信できない場合には、再度リクエスト頂くか、またはカスタマーサポートまでご連絡ください。';

// Request failure
$_['request_failed_heading_title'] = 'お客様のGDPRリクエストは失敗しました。';
$_['request_failed_text_message'] = 'お客様のGDPRリクエストを確認できませんでした。正しい確認リンクを使用しているかどうかを確認してください。';
$_['request_failed_heading_disabled'] = 'GDPR モジュール機能停止';
$_['request_failed_disabled'] = 'GDPR モジュールは現在、機能停止しています。';

// Request processed
$_['request_processed_heading_title'] = 'お客様のGDPRリクエストは既に処理されています。';
$_['request_processed_text_message'] = '個人データレポートをリクエストされている場合、お客様のEメールアドレスにレポートを送信中です。確認から24時間経ってもレポートが受信できない場合には、再度リクエスト頂くか、またはカスタマーサポートまでご連絡ください。アカウントの削除をリクエストされた場合、お客様の個人データはすでに削除されています。';

// GDPR Report
$_['gdpr_report_empty_record'] = '---';
$_['gdpr_report_heading_title'] = 'GDPR個人データレポート';
$_['gdpr_report_locations_of_other_data'] = 'お客様の個人データが保管されているその他のサービス/場所';
$_['gdpr_report_locations_of_servers'] = 'お客様のデータが保管されているサーバの物理的な位置';
$_['gdpr_report_text_info'] = '以下にお客様の個人データレポートを添付しました。当社の保管するお客様のアカウントに関するすべての情報が記載されています。';
$_['gdpr_report_text_account_details'] = 'アカウント詳細';
$_['gdpr_report_text_addresses'] = 'アドレス';
$_['gdpr_report_text_cart_contents'] = '買い物カゴの中身';
$_['gdpr_report_text_wishlist_contents'] = 'ウィッシュリストの中身';

// GDPR Report - Customer
$_['cart'] = '買い物カゴ';
$_['email'] = 'Eメール';
$_['fax'] = 'Fax';
$_['firstname'] = '氏名（名）';
$_['lastname'] = '氏名（姓）';
$_['not_subscribed'] = '購読されていません';
$_['newsletter'] = 'ニュースレターの定期購読';
$_['subscribed'] = '定期購読済み';
$_['telephone'] = '電話番号';
$_['wishlist'] = 'ウィッシュリスト';

// GDPR Report General
$_['amount'] = '数量';
$_['customer_id'] = '顧客ID';
$_['data'] = 'データ';
$_['date_added'] = 'データ作成日';
$_['date_modified'] = 'データ変更日';
$_['description'] = '説明';
$_['email'] = 'メールアドレス';
$_['history'] = '履歴';
$_['id'] = 'ID';
$_['order_id'] = '注文ID';
$_['total'] = '合計';

// GDPR Report Info
$_['activities'] = 'アクテビティ';
$_['comment'] = 'コメント';
$_['ip'] = 'IP';
$_['ips'] = '記録IP';
$_['key'] = 'キー';
$_['logins'] = '記録されたログイン';
$_['online'] = 'オンラインプレゼンス記録';
$_['single_activity'] = 'アクテビティID';
$_['single_history'] = '記録ID';
$_['single_ip'] = 'IP ID';
$_['single_login'] = 'ログイン記録ID';
$_['referer'] = 'リファラ';
$_['url'] = 'URL';

// GDPR Report - Order
$_['address'] = 'アドレス';
$_['city'] = '市';
$_['country'] = '国';
$_['currency_code'] = '通貨';
$_['firstname'] = '名前';
$_['invoice_no'] = 'インボイス番号';
$_['ip'] = 'IPアドレス';
$_['not_subscribed'] = '購読していません';
$_['order'] = '注文ID';
$_['payment_address'] = 'お支払い住所';
$_['payment_method'] = 'お支払い方法';
$_['postcode'] = '郵便番号';
$_['products'] = '商品';
$_['shipping_address'] = '発送住所';
$_['shipping_method'] = '発送方法';
$_['store_name'] = 'ストア名';
$_['user_agent'] = 'ユーザー・エージェント';

// GDPR Report - Order Products
$_['quantity'] = '数量';
$_['model'] = '型番';
$_['name'] = '名称';
$_['price'] = '価格';
$_['reward'] = 'リワード';
$_['tax'] = '税';

// GDPR Report - GDPR Requests
$_['gdpr_email'] = 'メールアドレス';
$_['gdpr_http_accept_language'] = 'HTTP受容言語';
$_['gdpr_http_user_agent'] = 'HTTPユーザー・エージェント';
$_['gdpr_remote_addr'] = 'リモートADDR';
$_['gdpr_requests'] = 'GDPRリクエスト';
$_['gdpr_request_time'] = '日時';
$_['gdpr_server_addr'] = 'サーバーADDR';
$_['gdpr_single_request'] = 'GDPRリクエストID';
$_['gdpr_status'] = 'ステータス';

// GDPR Report Rewards
$_['rewards'] = 'リワード';
$_['reward_description'] = '説明';
$_['reward_points'] = 'ポイント';
$_['single_reward'] = 'リワードID';

// GDPR Report Transactions
$_['transactions'] = 'トランザクション';

// GDPR Report Wishlist
$_['wishlist'] = 'ウィッシュリスト';

// ############ RIGHT TO BE FORGOTTEN ############

// Heading
$_['heading_title_forget_me']  = 'GDPR忘れられる権利（Right to be Forgotten）';

// Text
$_['text_forget_me_instructions']   = '<p>弊社のウェブサイトからお客様のアカウントを削除するには以下の手順に従ってください。<br>1.以下のフォームからお客様の Eメールを送信してください。<br>2. 確認メールがお客様に送られますので、メール内の確認ボタン/リンクをクリックしてください。 <br>3. お客様のアカウント及び個人データは削除/匿名化されます。</p>';

// Confirmation Email
$_['forget_me_confirmation_email_text_heading'] = 'アカウント削除リクエストの確定';
$_['forget_me_confirmation_email_text_instructions'] = '弊社のウェブサイトからのお客様のアカウント及び個人データの削除をリクエストされた場合は、以下のリンクをクリックし、メールアドレスを確認してリクエスト手続きを続行してください。一度リクエストを実行すると、アカウント及び保管された情報（注文履歴など）にアクセスできなくなりますのでご注意ください。実行すると取り消しはできません。';
$_['forget_me_confirmation_email_text_confirm'] = 'アカウントを削除する';
$_['forget_me_confirmation_email_text_smallprint'] = 'お客様のデータは直ちに削除/匿名化されます。実行後はお客様にメールでお知らせすることができなくなります。税手続きの目的で弊社がご注文に関連するお客様の個人データの一部を保持しなければならない場合があることをご留意ください。';

// Forget me request success
$_['forget_me_request_success_heading_title'] = 'お客様のGDPRアカウント削除リクエストが登録されました。';
$_['forget_me_request_success_text_message'] = '確認メールをお送りしました。メール内のリンクをクリックし、メールアドレスへのアクセスを証明してください。確認の完了後、お客様のリクエストを処理致します。アカウント及びアカウントに保管された情報（注文履歴など）にはアクセスできなくなりますのでご注意ください。一度実行すると取り消しはできません。';

// Forget me request confirmed
$_['forget_me_request_confirmed_heading_title'] = 'お客様のGDPRアカウント削除リクエストが確認されました。';
$_['forget_me_request_confirmed_text_message'] = 'お客様のGDPRリクエストに基づき、お客様の個人データを早急に削除/匿名化します。';
