<?php
// Module
$_['text_gdpr']             = 'EU一般データ保護規則（GDPR）';
$_['text_gdpr_settings']             = 'GDPR設定';
$_['text_gdpr_report']             = 'GDPRリクエスト履歴';

// Heading
$_['heading_title']    = 'EU一般データ保護規則（GDPR）';
$_['module_name'] = 'GDPR';

// Buttons etc.
$_['button_save'] = '保存';
$_['button_cancel'] = 'キャンセル';

// Entry
$_['entry_admin']      = '管理者のみ';
$_['entry_status']     = 'ステータス';
$_['entry_name'] = '名前';
$_['entry_message_text'] = '表示するメッセージ';
$_['entry_date_start'] = '開始日 (YYYY-MM-DD)';
$_['entry_date_end'] = '終了日 (YYYY-MM-DD)';
$_['entry_status'] = 'ステータス';

$_['entry_email_footer'] = 'GDPRメール フッター文';
$_['entry_email_header'] = 'GDPRメール ヘッダー文';
$_['entry_locations_of_other_data'] = '個人データを保管するその他の場所/サービス';
$_['entry_locations_of_servers'] = 'お客様のウェブサイト及びその他のデータをホストするサーバの物理的場所';
$_['entry_max_requests_day'] = '１日の最大リクエスト回数';
$_['entry_pending_status'] = '処理中ステータス文';
$_['entry_confirmed_status'] = '確定ステータス文';
$_['entry_emailed_status'] = 'メール送信済みステータス文';
$_['entry_account_deleted_status'] = 'アカウント削除ステータス文';
$_['entry_data_sent'] = 'データ送信済み';
$_['entry_unpaid'] = '未払いを示す文';
$_['entry_free'] = 'フリーテキスト';
$_['entry_rejected'] = '却下されたテキスト';
$_['entry_fairuse'] = '公正利用';
$_['entry_max_days_process'] = '１日の最大対応回数';
$_['entry_right_to_be_forgotten'] = '忘れられる権利申請フォーム有効化';

// Help
$_['help_pending_status'] = 'GDPRリクエストのステータス名は顧客により確認されていません。このステータス名は管理者GDPRリクエスト・レポート上に表示され、顧客はGDPRリクエストの履歴から閲覧することができます。';
$_['help_confirmed_status'] = 'GDPRリクエストのステータス名は顧客がEメール内の確認用リンクをクリックすることで確認されましたが、まだ未処理です（レポートEメールがまだ送信されていません）。このステータス名は管理者GDPRリクエスト・レポート上に表示され、顧客はGDPRリクエストの履歴から閲覧することができます。';
$_['help_emailed_status'] = 'GDPRリクエストのステータス名は処理されました/完了しました（レポートEメールが顧客に送信されました）。このステータス名は管理者GDPRリクエスト・レポート上に表示され、顧客はGDPRリクエストの履歴から見ることができます。';
$_['help_account_deleted_status'] = 'GDPRアカウント削除リクエストのステータス名は処理されました/完了しました。このステータス名は管理者GDPRリクエスト・レポート上に表示されます。';
$_['help_locations_of_other_data'] = '顧客の個人データを保管するその他のすべての場所及びウェブサービスをここにリスト化してください。例: Mailchimp, Google Docs　この情報は顧客に送信されるGDPRのEメール・レポートに含まれます。';
$_['help_locations_of_servers'] = 'ホスティングとサーバーの場所に関する全ての情報  (サーバの位置する国、 ホスティング・プロバイダー、ホスティング・プロバイダーがGDPRコンプライアンスを遵守しているかどうかなど）をリスト化してください。';
$_['help_max_requests_day'] = '１日に顧客に許可されるリクエスト回数。スパムリクエストや攻撃、ウェブ・サーバにかかる不要な負荷防止のため、低目に設定してください。最大 3-5 回に設定することを推奨します。';
$_['help_right_to_be_forgotten'] = '顧客が自動でアカウントを削除し、削除できない部分の個人データは匿名化することができるように、「忘れられる権利」フォームを有効化します。';

// Error
$_['error_permission'] = '警告:GDPRモジュール変更の権限がありません。';

// Text
$_['text_module']      = 'モジュール';
$_['text_success']     = 'GDPRモジュールの変更に成功しました。';
$_['text_edit']        = 'GDPRモジュールを編集する';
