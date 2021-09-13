<?php

// Buttons
$_['button_add_breach'] = 'データ漏洩インシデントを追加する';
$_['button_download_pdf'] = '通知レターをPDF形式でダウンロードする';
$_['button_email'] = 'メールを送信する';
$_['button_generate_customer_notifications'] = '顧客通知を作成する';
$_['button_preview_customer_notification'] = 'プレビュー';
$_['button_save_breach'] = '保存';
$_['button_send'] = '通知レターをメールで送信する';

// Columns
$_['column_action'] = 'アクション';
$_['column_bcc_to'] = 'BCC送信宛先';
$_['column_breach_id'] = '漏洩ID';
$_['column_customer_email'] = 'メールアドレス';
$_['column_customer_id'] = '顧客ID';
$_['column_customer_name'] = '名前';
$_['column_id'] = 'ID';

$_['column_date_added'] = '追加した日付';
$_['column_date_of_breach']	= '漏洩日';
$_['column_date_of_discovery'] = '発見日';
$_['column_date_updated'] = '最終アップデート';
$_['column_number_of_accounts_affected'] = '影響を受けたアカウントの数';
$_['column_breach_name'] = '漏洩インシデント名';
$_['column_sent_to'] = 'メール送信宛先';
$_['column_status'] = 'コミッショナー通知ステータス';
$_['column_status_customers'] = '顧客通知ステータス';
$_['column_status_notification'] = '顧客メールステータス';

// Heading
$_['heading_title']        = 'データセキュリティ漏洩通知';
$_['heading_title_customers']        = 'データセキュリティ漏洩通知';
$_['heading_title_customers_send_emails'] = '顧客にメールで通知する';
$_['heading_detailed'] = 'データ保護コミッショナーへのデータセキュリティ漏洩通知';
$_['heading_detailed_customers'] = '顧客へのデータセキュリティ漏洩通知';
$_['heading_detailed_customers_send_emails'] = '手動でバッチごとに顧客へ通知する。この方法は顧客数が多い場合には推奨しません。';

// Text
$_['text_breach_email_subject'] = '個人データ漏洩通知';
$_['text_commissioner'] = 'データ保護コミッショナー';
$_['text_customers'] = '顧客';
$_['text_data_breach'] = 'データ漏洩報告';
$_['text_data_breach_commissioner_list'] = '記録済みインシデント';
$_['text_data_breach_commissioner_form'] = '新しいインシデントの追加';
$_['text_data_breach_customer_list'] = '通知の作成';
$_['text_data_breach_customer_emails'] = '記録済みメール通知';
$_['text_data_breach_customer_emails_send'] = 'メール送信';
$_['text_data_breach_customer_csv'] = '顧客リストのダウンロード';

$_['text_email_commissioner_body'] = 'データ漏洩通知レターを添付しました。';
$_['text_email_commissioner_subject'] = 'データ漏洩通知';
$_['text_section_commissioner'] = 'データ保護コミッショナー用情報';
$_['text_section_customer'] = '顧客用情報';
$_['text_section_general'] = '一般情報';
$_['text_success']         = 'メッセージが確かに送信されました。';
$_['text_success_saved_record']         = 'お客様の漏洩通知記録は確かに保存されました。まだ通知がない場合には、本レターのPDF版をダウンロードするか、「コミッショナーにメールを送信する」ボタンをクリックしてください。';
$_['text_success_generate_customer_notifications']         = 'お客様の顧客通知が作成されました（まだ送信されていません）！';
$_['text_sent']            = 'メッセージは %s 名の受信者（全 %s 名）に送信されました。';
$_['text_notifications_emailed'] = 'お客様のメール通知：';
$_['text_notifications_pending'] = 'お客様の保留の通知：';
$_['text_total_customers'] = 'システム内の顧客アカウント総数は';


$_['text_from']         = '送信者：';
$_['text_to']         = '受信者：';
$_['text_date']         = '日時';
$_['text_default']         = 'デフォルト';
$_['text_email'] = 'メールを送信する';
$_['text_header_cron'] = 'CRONセットアップ（推奨）';
$_['text_header_log'] = 'ログ';
$_['text_hour'] = '時間';
$_['text_hours'] = '時間';
$_['text_instructions']    = 'デフォルト';
$_['text_instructions_customers_send_emails'] = '本フォームは手動でバッチごとに顧客にメール通知を送信するためのものです。 お使いのホスティング/サーバに合った設定を選択する必要があります。 お使いのサーバのメール送信上限が低いと完了までに時間がかかる場合があります。自動的に動作するクロンジョブを設定するようお勧めします (詳細は下記を参照)。このスクリプトは未送信の通知のみに対して動作します。顧客にメール送信された通知はすべて、送信済みとしてデータベースに転送されるため、送信済み通知のトラッキングが可能です。 ';
$_['text_instructions_log'] = '顧客に送信済みのメールログはOpencartログフォルダの中にあります：';
$_['text_instructions_cron'] = 'サーバによるメールの自動送信を許可するには、以下のURLからクロンジョブを設定してください。 URLの\'max_runtime\'はサーバがスクリプトを動作させることのできる最大時間を分単位で示すもので、\'batch_size\'は１時間に送信できる最大メール数です：';
$_['text_minutes'] = '分';
$_['text_newsletter']      = 'すべてのニュースレター購読者';
$_['text_customer_all']    = 'すべての顧客';
$_['text_list'] = '記録済みデータ漏洩（データコミッショナー）';
$_['text_list_customers'] = '記録済みデータ漏洩（顧客）';
$_['text_product']         = '製品';
$_['text_add_breach']         = 'データ漏洩インシデントを追加する';
$_['text_return'] = 'リターン';
$_['text_save_breach']         = '保存';
$_['text_signature'] = '敬具';
$_['text_status_generated']         = '作成済み';
$_['text_status_pending']         = '保留';
$_['text_status_sent']         = '送信済み';
$_['text_status_unknown']         = '未知のステータス';
$_['text_status_failed']         = '無効なメール';
$_['text_success_email_batch'] = 'お客様のサーバからメールが送信されました。通知リストをご覧ください:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = '個人情報を含む可能性のあるデータ・セキュリティ漏洩インシデントを報告します。';
$_['text_report_to_commissioner_contact'] = '本インシデントに関するコンタクトのポイントは以下の通りです：';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = '%s に以下のデータ漏洩を発見しました。：';
$_['text_report_to_commissioner_data_exposed'] = 'アクセスしたデータは以下の個人情報を含んでいる可能性があります：';
$_['text_report_to_commissioner_actions_taken'] = '本インシデントの解決のために以下の措置を講じました：';
$_['text_report_to_commissioner_ending'] = '影響が及んだ可能性のあるシステムの徹底的なレビューを実行中です。重大な変化が見つかった場合にはデータ保護コミッショナーオフィスに通知致します。このような攻撃の再発を防止し、顧客のプライバシーを保護するため、追加の安全対策を実装中です。';

$_['text_send_breach_notification'] = '漏洩通知を送信する';

// Entry
$_['entry_address_commissioner'] = 'データコミッショナー（住所）';
$_['entry_address_store'] = 'ストア（住所）';
$_['entry_batch_size'] = 'バッチサイズ';
$_['entry_breach_notification_status'] = 'ステータス';
$_['entry_breach_customer_email_notification_status'] = 'メール通知ステータス';
$_['entry_contact_details_of_person_reporting']       = 'データ・コミッショナー用コンタクト詳細情報';
$_['entry_contact_email']       = '顧客用コンタクトアドレス';
$_['entry_customer_email']       = 'メールアドレス';
$_['entry_customer_group'] = '顧客グループ';
$_['entry_customer']       = '顧客';
$_['entry_date_added']       = '追加日';
$_['entry_date_of_breach']       = '漏洩日（わかれば）';
$_['entry_date_of_discovery']       = 'インシデント発見日';
$_['entry_email_bcc']       = 'BCC';
$_['entry_email_commissioner'] = 'データ・コミッショナー（メール）';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = '最大ランタイム';
$_['entry_message_action']        = '漏洩発見後に取ったアクションの簡単な説明（コミッショナー）';
$_['entry_message_action_customers']        = '取ったアクションの簡単な説明（顧客）';
$_['entry_message_incident']       = '漏洩の簡単な説明（コミッショナー）';
$_['entry_message_incident_customers']       = '漏洩の簡単な説明（顧客）';
$_['entry_name']       = 'インシデント名（参照用）';
$_['entry_number_of_accounts_affected']       = '影響を受けたアカウント数（わかれば）';
$_['entry_store']          = '送信者';
$_['entry_subject']        = '表題（コミッショナーのメールアドレス）';
$_['entry_subject_customers']        = '表題（顧客のメールアドレス）';
$_['entry_to']             = '受信者';

// Help
$_['help_address_commissioner'] = 'データ保護コミッショナーの完全な住所。 漏洩通知レターのヘッダーに印刷されます。';
$_['help_address_store'] = '漏洩を報告するストアの完全な住所。 レターのヘッダーに印刷されます。';
$_['help_batch_size'] = '1時間に送信できるEメールの件数。サーバのコンフィギュレーションによって異なります。ほとんどのシェアサーバでは１時間に最大100-200件に制限されています。';
$_['help_contact_details_of_person_reporting'] = 'データ保護コミッショナーがコンタクトできる人のメールアドレス及び/または電話番号を追加してください。';
$_['help_contact_email'] = 'データ漏洩に関してコンタクトできる顧客のメールアドレスを追加してください。';
$_['help_data_commissioner']       = '管轄のデータコミッショナーのメールアドレス';
$_['help_date_of_breach'] = '正確な日付が不明な場合は推定範囲を入力してください。';
$_['help_date_of_discovery'] = 'システム漏洩の発見日';
$_['help_email_bcc']       = '漏洩通知に複写したメールアドレス';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Eメール送信スクリプトの最大動作時間';
$_['help_message_action'] = '漏洩発見以降に取ったすべてのアクションを記述してください。記述したアクションはデータ保護コミッショナーへのレターに含まれます。';
$_['help_message_action_customers'] = '顧客に関連する漏洩発見以降に取ったすべてのアクションを記述してください。記述したアクションは顧客へのEメールに含まれます。';
$_['help_message_incident'] = '誰がデータにアクセスし、漏洩がどのように起こったか、どのようにして漏洩が発見されたかなどを喜寿視してください。このテキストはデータ保護コミッショナーへのレターに含まれます。';
$_['help_message_incident_customers'] = '顧客に関する詳細を含め、漏洩について記述してください。記述した内容は顧客へのEメールに含まれます。';
$_['help_name'] = 'この名前はストア所有者/管理者の参照用です。データ保護コミッショナーまたは顧客には提示されません。';
$_['help_number_of_accounts_affected'] = '漏洩により影響を受けたアカウント数（顧客/データ件名）を記述してください。';
$_['help_subject']       = 'データ保護コミッショナーへの通知メール/レターの件名';
$_['help_subject_customers']       = '顧客への通知Eメールの件名';

// Error
$_['error_address_commissioner']        = 'データ保護コミッショナーの住所の入力が必要です！ ';
$_['error_address_store']        = 'ストアの住所の入力が必要です！';
$_['error_contact_details_of_person_reporting']        = 'レポートの送信者のコンタクト情報の入力が必要です！';
$_['error_customer_notification_add'] = '顧客通知を追加できませんでした。';
$_['error_customer_notification_existing'] = '本データの漏洩については通知済みです。';
$_['error_data_commissioner']        = 'データ保護コミッショナーのの入力住所が必要です。';
$_['error_date_of_breach']        = '漏洩日の入力が必要です！';
$_['error_date_of_discovery']        = '漏洩発見日の入力が必要です！';
$_['error_email_batch'] = 'お客様のメール通知が発送できませんでした。もう一度お試しください。';
$_['error_permission']     = '警告: 漏洩通知メールの送信が許可されていません！';
$_['error_subject']        = '漏洩通知Eメール件名の入力が必要です！';
$_['error_mail_bcc'] = 'メールアドレスの書式が無効です。メールアドレスはコンマで区切ってリスト化してください。';
$_['error_mail_commissioner'] = 'データ・コミッショナー用のメールの形式が正しくありません。 ';
$_['error_message_action']        = '漏洩後のアクションのサマリーの入力が必要です！';
$_['error_message_incident']        = 'インシデント/漏洩の説明が必要です！';
$_['error_missing_commissioner_email'] = 'コミッショナーのメールアドレスの入力が必要です！';
$_['error_saving_breach_notification_failed'] = 'お客様の漏洩通知記録を保存できませんでした。もう一度お試しください。';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'data_breach_notification';
