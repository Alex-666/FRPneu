<?php

// Buttons
$_['button_add_breach'] = 'Agregar incidente de fuga de datos';
$_['button_download_pdf'] = 'Descargue la carta de notificaci&oacute;n en formato PDF';
$_['button_email'] = 'Enviar correos electr&oacute;nicos';
$_['button_generate_customer_notifications'] = 'Generar notificaciones de clientes';
$_['button_preview_customer_notification'] = 'Vista previa';
$_['button_save_breach'] = 'Guardar';
$_['button_send'] = 'Enviar carta de notificaci&oacute;n por correo electr&oacute;nico';

// Columns
$_['column_action'] = 'Acci&oacute;n';
$_['column_bcc_to'] = 'Cco\'para';
$_['column_breach_id'] = 'ID Fuga';
$_['column_customer_email'] = 'Email';
$_['column_customer_id'] = 'ID Cliente';
$_['column_customer_name'] = 'Nombre';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'A&ntilde;adida el';
$_['column_date_of_breach']	= 'Fecha de la fuga';
$_['column_date_of_discovery'] = 'Fecha de descubrimiento';
$_['column_date_updated'] = '&uacute;ltima actualizaci&oacute;n';
$_['column_number_of_accounts_affected'] = 'N&uacute;mero de cuentas afectadas';
$_['column_breach_name'] = 'Nombre de la fuga';
$_['column_sent_to'] = 'Enviado por E-mail a';
$_['column_status'] = 'Estado de notificaci&oacute;n Agencia de Protecci&oacute;n de Datos';
$_['column_status_customers'] = 'Estado de notificaci&oacute;n de clientes';
$_['column_status_notification'] = 'Estado del E-mail del cliente';

// Heading
$_['heading_title']        = 'Notificaci&oacute;n de violaci&oacute;n de seguridad de datos';
$_['heading_title_customers']        = 'Notificaci&oacute;n de violaci&oacute;n de seguridad de datos';
$_['heading_title_customers_send_emails'] = 'Enviar E-mail de notificaci&oacute;n a los clientes';
$_['heading_detailed'] = 'Notificaci&oacute;n de violaci&oacute;n de seguridad de datos a la Agencia de Protecci&oacute;n de Datos';
$_['heading_detailed_customers'] = 'Notificaci&oacute;n de violaci&oacute;n de seguridad de datos a los clientes';
$_['heading_detailed_customers_send_emails'] = 'Enviar notificaciones a los clientes manualmente en lotes. Este m&eacute;todo no es recomendable si tiene una gran cantidad de clientes.';

// Text
$_['text_breach_email_subject'] = 'Notificaci&oacute;n de fuga de datos personales';
$_['text_commissioner'] = 'Agencia de Protecci&oacute;n de Datos';
$_['text_customers'] = 'Clientes';
$_['text_data_breach'] = 'Informe fuga de datos';
$_['text_data_breach_commissioner_list'] = 'Registro de incidentes';
$_['text_data_breach_commissioner_form'] = 'Agregar nuevo incidente';
$_['text_data_breach_customer_list'] = 'Generar notificaciones';
$_['text_data_breach_customer_emails'] = 'Notificaciones de Email registradas';
$_['text_data_breach_customer_emails_send'] = 'Enviar Emails';
$_['text_data_breach_customer_csv'] = 'Descargar listado de clientes';

$_['text_email_commissioner_body'] = 'Encontrar una carta de notificaci&oacute;n de violaci&oacute;n de datos adjunta.';
$_['text_email_commissioner_subject'] = 'Notificaci&oacute;n de fuga de datos';
$_['text_section_commissioner'] = 'Informaci&oacute;n para la Agencia de Protecci&oacute;n de Datos';
$_['text_section_customer'] = 'Informaci&oacute;n para los Clientes';
$_['text_section_general'] = 'Informaci&oacute;n General';
$_['text_success']         = '&iexcl;Su mensaje se envi&oacute; correctamente!';
$_['text_success_saved_record']         = 'Su registro de notificaci&oacute;n se ha guardado con &eacute;xito. Tenga en cuenta que todav&iacute;a no se ha enviado, para hacerlo, descargue una versi&oacute;n en PDF de la carta con el bot&oacute;n \'Email Agencia de Protecci&oacute;n de Datos\'';
$_['text_success_generate_customer_notifications']         = 'Se han generado las notificaciones para sus clientes (&iexcl;Recuerde que todav&iacute;a no se ha enviado ning&uacute;n correo electr&oacute;nico!)';
$_['text_sent']            = '&iexcl;Su mensaje ha sido enviado correctamente a %s de %s destinatarios!';
$_['text_notifications_emailed'] = 'Sus notificaciones por Email:';
$_['text_notifications_pending'] = 'Sus notificaciones pendientes:';
$_['text_total_customers'] = 'El n&uacute;mero total de cuentas de clientes en su sistema es';


$_['text_from']         = 'De:';
$_['text_to']         = 'A:';
$_['text_date']         = 'Fecha';
$_['text_default']         = 'Por defecto';
$_['text_email'] = 'Emviar emails';
$_['text_header_cron'] = 'Preparar CRON (recomendado)';
$_['text_header_log'] = 'Log';
$_['text_hour'] = 'hora';
$_['text_hours'] = 'horas';
$_['text_instructions']    = 'Por defecto';
$_['text_instructions_customers_send_emails'] = 'Este formulario le permite enviar manualmente lotes de notificaciones por correo electr&oacute;nico a sus clientes. Debe seleccionar configuraciones que sean apropiadas para su servidor/hosting. Tenga en cuenta que si los l&iacute;mites de su servidor para enviar correos electr&oacute;nicos son bajos, puede llevar mucho tiempo completarlos. Es por eso que recomendamos configurar un trabajo cron que ejecute esto autom&aacute;ticamente (detalles disponibles a continuaci&oacute;n). Esta secuencia de comandos solo funcionar&aacute; en las notificaciones que a&uacute;n no se hayan enviado. Cada notificaci&oacute;n que se env&iacute;a por correo electr&oacute;nico a un cliente se marcar&aacute; como enviada en la base de datos, por lo que puede rastrear qu&eacute; notificaciones se enviaron.';
$_['text_instructions_log'] = 'El registro de emails enviados a los clientes se puede encontrar en la carpeta de registro de Opencart:';
$_['text_instructions_cron'] = 'Para permitir que su servidor env&iacute;e sus correos electr&oacute;nicos autom&aacute;ticamente, configure un trabajo cron usando la siguiente URL donde \'max_runtime\' es el tiempo m&aacute;ximo que el servidor puede ejecutar el script en minutos y \'batch_size\' es el n&uacute;mero m&aacute;ximo de correos electr&oacute;nicos que se puede enviar en una hora:';
$_['text_minutes'] = 'minutos';
$_['text_newsletter']      = 'Todos los suscriptores del bolet&iacute;n';
$_['text_customer_all']    = 'Todos los clientes';
$_['text_list'] = 'Fuga de datos registradas (Agencia de Protecci&oacute;n de Datos)';
$_['text_list_customers'] = 'Fuga de datos registradas (Clientes)';
$_['text_product']         = 'Productos';
$_['text_add_breach']         = 'Agregar incidente fuga de datos';
$_['text_return'] = 'Volver';
$_['text_save_breach']         = 'Guardar';
$_['text_signature'] = 'Saludos,';
$_['text_status_generated']         = 'Generado';
$_['text_status_pending']         = 'Pendiente';
$_['text_status_sent']         = 'Enviar';
$_['text_status_unknown']         = 'Desconocido';
$_['text_status_failed']         = 'E-mail no v&aacute;lido';
$_['text_success_email_batch'] = 'Ahora los emails ser&aacute;n enviados por su servidor. Por favor revise la lista de notificaciones en:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'Por la presente informamos de un incidente de violaci&oacute;n de seguridad de datos que puede involucrar informaci&oacute;n personal.';
$_['text_report_to_commissioner_contact'] = 'Nuestro punto de contacto con respecto a este incidente es:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = 'El %s hemos descubierto una fuga de datos como se detalla a continuaci&oacute;n:';
$_['text_report_to_commissioner_data_exposed'] = 'Los datos a los que se ha accedido pueden haber incluido informaci&oacute;n personal, como:';
$_['text_report_to_commissioner_actions_taken'] = 'Hasta el momento hemos realizado los siguientes pasos para remediar este incidente:';
$_['text_report_to_commissioner_ending'] = 'Estamos llevando a cabo una revisi&oacute;n exhaustiva de los sistemas potencialmente afectados, y notificaremos a la Agencia de Protecci&oacute;n de Datos si hay desarrollos significativos. Estamos implementando medidas de seguridad adicionales dise&ntilde;adas para prevenir la recurrencia de dicho ataque y para proteger la privacidad de nuestros clientes.';

$_['text_send_breach_notification'] = 'Send Breach Notification';

// Entry
$_['entry_address_commissioner'] = 'Agencia de Protecci&oacute;n de Datos (Direcci&oacute;n Postal)';
$_['entry_address_store'] = 'Tienda (Direcci&oacute;n Postal)';
$_['entry_batch_size'] = 'Tama&ntilde;o del lote';
$_['entry_breach_notification_status'] = 'Estado';
$_['entry_breach_customer_email_notification_status'] = 'Estado de notificaci&oacute;nes por correo electr&oacute;nico';
$_['entry_contact_details_of_person_reporting']       = 'Datos de contacto de la Agencia de Protecci&oacute;n de Datos';
$_['entry_contact_email']       = 'Correo electr&oacute;nico de contacto para clientes';
$_['entry_customer_email']       = 'Email';
$_['entry_customer_group'] = 'Grupo de Clientes';
$_['entry_customer']       = 'Cliente';
$_['entry_date_added']       = 'A&ntilde;adido el';
$_['entry_date_of_breach']       = 'Fecha(s) de la fuga (si se conoce)';
$_['entry_date_of_discovery']       = 'Fecha en el que el incidente fue descubierto';
$_['entry_email_bcc']       = 'CCO';
$_['entry_email_commissioner'] = 'Agencia de Protecci&oacute;n de Datos (email)';
//$_['entry_email_cc']       = 'CC';
$_['entry_max_runtime'] = 'Tiempo de ejecuci&oacute;n m&aacute;ximo';
$_['entry_message_action']        = 'Breve descripci&oacute;n de cualquier acci&oacute;n desde que se descubri&oacute; el incidente (Ag. Protecci&oacute;n Datos)';
$_['entry_message_action_customers']        = 'Breve descripci&oacute;n de las acciones tomadas (Clientes)';
$_['entry_message_incident']       = 'Breve descripci&oacute;n de la violaci&oacute;n de seguridad (Ag. Protecci&oacute;n Datos)';
$_['entry_message_incident_customers']       = 'Breve descripci&oacute;n de la violaci&oacute;n de seguridad (Clientes)';
$_['entry_name']       = 'Nombre incidente (su referencia)';
$_['entry_number_of_accounts_affected']       = 'N&uacute;mero de cuentas afectadas (si se conoce)';
$_['entry_store']          = 'De';
$_['entry_subject']        = 'Asunto (Ag. Protecci&oacute;n Datos E-mail)';
$_['entry_subject_customers']        = 'Asunto (Clientes E-mails)';
$_['entry_to']             = 'A';

// Help
$_['help_address_commissioner'] = 'Direcci&oacute;n postal completa de la Agencia de Protecci&oacute;n de Datos. Esto se imprimir&aacute; en el encabezado de la carta de notificaci&oacute;n de incumplimiento';
$_['help_address_store'] = 'Direcci&oacute;n postal completa de la tienda que informa de la infracci&oacute;n. Esto se imprimir&aacute; en el encabezado de la carta';
$_['help_batch_size'] = 'Una cantidad de correos electr&oacute;nicos que se pueden enviar en una hora. Esto depender&aacute; de la configuraci&oacute;n de tu servidor. La mayor&iacute;a de los servidores compartidos lo limitar&aacute;n en gran medida, por lo que no se le permitir&aacute; enviar m&aacute;s de 100-200 correos electr&oacute;nicos por hora.';
$_['help_contact_details_of_person_reporting'] = 'Agregue la direcci&oacute;n de correo electr&oacute;nico y/o el n&uacute;mero de tel&eacute;fono de una persona como contacto con la Agencia de Protecci&oacute;n de Datos';
$_['help_contact_email'] = 'Agregue un email al que sus clientes puedan contactar en relaci&oacute;n con la violaci&oacute;n de datos.';
$_['help_data_commissioner']       = 'Email de la Agencia de Protecci&oacute;n de Datos en su zona';
$_['help_date_of_breach'] = 'Si no conoce la fecha exacta, por favor proporcione un rango de tiempo estimado';
$_['help_date_of_discovery'] = 'Fecha en que descubri&oacute; que su sistema ha sido violado';
$_['help_email_bcc']       = 'Direcciones de correo electr&oacute;nico que se copiar&aacute;n en la notificaci&oacute;n de incumplimiento';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Tiempo m&aacute;ximo en que la secuencia de comandos de env&iacute;o de correo electr&oacute;nico debe ejecutarse.';
$_['help_message_action'] = 'Describa todas las acciones que has realizado desde que se descubri&oacute; la infracci&oacute;n. Este texto se incluir&aacute; en la carta a la Agencia de Protecci&oacute;n de Datos.';
$_['help_message_action_customers'] = 'Describa todas las acciones relevantes para sus clientes que se han tomado desde que se descubri&oacute; el incidente. Este texto se incluir&aacute; en los correos electr&oacute;nicos a los clientes.';
$_['help_message_incident'] = 'Describa los detalles de la violaci&oacute;n de seguridad, qui&eacute;n estaba accediendo a los datos, c&oacute;mo lo hicieron, c&oacute;mo se descubri&oacute;, etc. Este texto se incluir&aacute; en la carta a la Agencia de Protecci&oacute;n de Datos.';
$_['help_message_incident_customers'] = 'Describa el incumplimiento con cualquier detalle relevante para sus clientes. Este texto se incluir&aacute; en los correos electr&oacute;nicos a los clientes.';
$_['help_name'] = 'Este nombre es solo como referencia para el propietario/administrador de la tienda, no se revelar&aacute; a la Agencia de Protecci&oacute;n de Datos ni a los clientes.';
$_['help_number_of_accounts_affected'] = 'Indique cu&aacute;ntas cuentas (clientes/datos de personas) se han visto afectadas por este incidente';
$_['help_subject']       = 'Objeto del correo electr&oacute;nico/carta de notificaci&oacute;n a la Agencia de Protecci&oacute;n de Datos';
$_['help_subject_customers']       = 'Asunto de los correos electr&oacute;nicos de notificaci&oacute;n a los clientes';

// Error
$_['error_address_commissioner']        = '&iexcl;Se requiere la direcci&oacute;n de la Agencia de Protecci&oacute;n de Datos!';
$_['error_address_store']        = '&iexcl;Direcci&oacute;n de la Tienda requerida!';
$_['error_contact_details_of_person_reporting']        = '&iexcl;Se requieren los datos de contacto de la persona que env&iacute;a el informe!';
$_['error_customer_notification_add'] = 'No se pudieron agregar notificaciones de clientes';
$_['error_customer_notification_existing'] = 'Ya hay una notificaci&oacute;n existente para esta violaci&oacute;n de datos';
$_['error_data_commissioner']        = '&iexcl;Se requiere el email de la Agencia de Protecci&oacute;n de Datos!';
$_['error_date_of_breach']        = '&iexcl;Fecha del incidente requerida!';
$_['error_date_of_discovery']        = '&iexcl;Fecha del descubrimiento del incidente requerida!';
$_['error_email_batch'] = 'Sus notificaciones por correo electr&oacute;nico no pudieron ser enviadas, intentelo de nuevo.';
$_['error_permission']     = 'Advertencia: &iexcl;No tiene permiso para enviar correos electr&oacute;nicos de notificaci&oacute;n!';
$_['error_subject']        = 'Asunto para el email de notificaci&oacute;n requiredo!';
$_['error_mail_bcc'] = 'El formato incorrecto. Proporcione emails en una lista separada por comas.';
$_['error_mail_commissioner'] = 'Formato incorrecto Email Agencia de Protecci&oacute;n de Datos.';
$_['error_message_action']        = '&iexcl;Se requiere un resumen de las acciones tomadas despues del incidente!';
$_['error_message_incident']        = 'Se requiere descripci&oacute;n del incidente/incumplimiento!';
$_['error_missing_commissioner_email'] = '&iexcl;El Email de la Agencia de Protecci&oacute;n de Datos es obligatorio!';
$_['error_saving_breach_notification_failed'] = 'No pudimos guardar su registro de notificaci&oacute;n de incumplimiento, intentelo de nuevo';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'notificacion_fuga_datos';
