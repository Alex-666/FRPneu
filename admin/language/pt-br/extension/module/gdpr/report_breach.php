<?php

// Buttons
$_['button_add_breach'] = 'Adicionar incidente de violação de dados';
$_['button_download_pdf'] = 'Baixar carta de notificação em formato PDF';
$_['button_email'] = 'Enviar emails';
$_['button_generate_customer_notifications'] = 'Gerar Notificações do Cliente';
$_['button_preview_customer_notification'] = 'Pré-visualizar';
$_['button_save_breach'] = 'Salvar';
$_['button_send'] = 'Enviar carta de notificação por e-mail';

// Columns
$_['column_action'] = 'Acção';
$_['column_bcc_to'] = 'BCC\'ed to';
$_['column_breach_id'] = 'ID de violação';
$_['column_customer_email'] = 'E-mail';
$_['column_customer_id'] = 'ID do Cliente';
$_['column_customer_name'] = 'Nome';
$_['column_id'] = 'ID';

$_['column_date_added'] = 'Data de Adição';
$_['column_date_of_breach']	= 'Data de Violação';
$_['column_date_of_discovery'] = 'Data da Descoberta';
$_['column_date_updated'] = 'Última Atualização';
$_['column_number_of_accounts_affected'] = 'Número de Contas Afetadas';
$_['column_breach_name'] = 'Nome da Violação';
$_['column_sent_to'] = 'E-mail para';
$_['column_status'] = 'Estado de Notificação do Comissário';
$_['column_status_customers'] = 'Estado de Notificação de Clientes';
$_['column_status_notification'] = 'Estado do E-mail do Cliente';

// Heading
$_['heading_title'] = 'Notificação de Violação de Segurança de Dados';
$_['heading_title_customers'] = 'Notificação de Violação de Segurança de Dados';
$_['heading_title_customers_send_emails'] = 'Enviar e-mails de notificação aos Clientes';
$_['heading_detailed'] = 'Notificação de Violação de Segurança de Dados ao Comissário de Proteção de Dados';
$_['heading_detailed_customers'] = 'Notificação de Violação de Segurança de Dados para Clientes';
$_['heading_detailed_customers_send_emails'] = 'Envie notificações para os clientes manualmente em lotes. Este método não é recomendado se você tiver um grande número de clientes.';

// Text
$_['text_breach_email_subject'] = 'Notificação de Violação de Dados Pessoais';
$_['text_commissioner'] = 'DComissário de Proteção de Dados';
$_['text_customers'] = 'Clientes';
$_['text_data_breach'] = 'Relatório de Violação de Dados';
$_['text_data_breach_commissioner_list'] = 'Incidentes Registrados';
$_['text_data_breach_commissioner_form'] = 'Adicionar Novo Incidente';
$_['text_data_breach_customer_list'] = 'Gerar Notificações';
$_['text_data_breach_customer_emails'] = 'Notificações de E-mail registradas';
$_['text_data_breach_customer_emails_send'] = 'Enviar E-mails';
$_['text_data_breach_customer_csv'] = 'Baixar lista de clientes';

$_['text_email_commissioner_body'] = 'Por favor, encontre uma carta de notificação de violação de dados anexada.';
$_['text_email_commissioner_subject'] = 'Notificação de Violação de Segurança de Dados';
$_['text_section_commissioner'] = 'Informações para o Comissário de Proteção de Dados';
$_['text_section_customer'] = 'Informação para os Clientes';
$_['text_section_general'] = 'Informação Geral';
$_['text_success'] = 'Sua mensagem foi enviada com sucesso!';
$_['text_success_saved_record'] = 'Seu registro de notificação de violação foi salvo com sucesso. Por favor, note que ainda não foi enviado, para fazer isso, por favor, baixe uma versão em PDF da carta, use o botão \'Email Commissioner\'';
$_['text_success_generate_customer_notifications'] = 'Suas notificações de clientes foram geradas (lembre-se de que nada foi enviado por e-mail)!';
$_['text_sent'] = 'Sua mensagem foi enviada com sucesso para %s de %s destinatários!';
$_['text_notifications_emailed'] = 'Suas notificações por e-mail:';
$_['text_notifications_pending'] = 'Suas notificações pendentes:';
$_['text_total_customers'] = 'O número total de contas de clientes em seu sistema é';


$_['text_from'] = 'De:';
$_['text_to'] = 'Para:';
$_['text_date'] = 'Data';
$_['text_default'] = 'Padrão';
$_['text_email'] = 'Enviar e-mails';
$_['text_header_cron'] = 'Configuração CRON (recomendado)';
$_['text_header_log'] = 'Log';
$_['text_hour'] = 'hora';
$_['text_hours'] = 'horas';
$_['text_instructions'] = 'Padrão';
$_['text_instructions_customers_send_emails'] = 'Este formulário permite que você envie manualmente lotes de notificações por e-mail para seus clientes. Você precisa selecionar as configurações apropriadas para sua hospedagem / servidor. Lembre-se de que, se os limites do servidor para o envio de e-mails forem baixos, isso poderá levar muito tempo para ser concluído. É por isso que recomendamos a configuração de um cron job que execute isso automaticamente para você (detalhes disponíveis abaixo). Este script só funcionará em notificações que ainda não foram enviadas. Toda notificação que é enviada por e-mail para um cliente será marcada como enviada no banco de dados, para que você possa acompanhar quais notificações foram enviadas.';
$_['text_instructions_log'] = 'O log de emails enviados aos clientes pode ser encontrado na pasta de log do Opencart:';
$_['text_instructions_cron'] = 'Para permitir que seu servidor envie seus e-mails automaticamente, configure uma tarefa do cron usando o seguinte URL, em que \'max_runtime\' é o tempo máximo que o servidor pode executar o script em minutos e \'batch_size\' é o número máximo de e-mails que pode ser enviado em uma hora:';
$_['text_minutes'] = 'minutos';
$_['text_newsletter'] = 'Todos os Assinantes da Newsletter';
$_['text_customer_all'] = 'Todos os clientes';
$_['text_list'] = 'Violações de Dados Gravados (Comissário de Dados)';
$_['text_list_customers'] = 'Violações de Dados Gravados (Clientes)';
$_['text_product'] = 'Produtos';
$_['text_add_breach'] = 'Adicionar Incidente de Violação de Dados';
$_['text_return'] = 'Voltar';
$_['text_save_breach'] = 'Salvar';
$_['text_signature'] = 'Atenciosamente,';
$_['text_status_generated'] = 'Gerado';
$_['text_status_pending'] = 'Pendente';
$_['text_status_sent'] = 'Enviado';
$_['text_status_unknown'] = 'Desconhecido';
$_['text_status_failed'] = 'E-mail inválido';
$_['text_success_email_batch'] = 'Agora os E-mails serão enviados pelo seu servidor. Por favor, revise a lista de notificações em:';

// Commissioner letter
$_['text_report_to_commissioner_intro'] = 'Por este meio, relatamos um incidente de violação de segurança de dados que pode envolver informações pessoais.';
$_['text_report_to_commissioner_contact'] = 'Nosso ponto de contato em relação a esse incidente é:';
// %s is going to be replaced with the date of breach, so 'On 15/07/2018 we have discovered...'
$_['text_report_to_commissioner_details'] = 'Em %s, descobrimos uma violação de dados, conforme detalhado abaixo:';
$_['text_report_to_commissioner_data_exposed'] = 'Os dados acessados podem incluir informações pessoais como:';
$_['text_report_to_commissioner_actions_taken'] = 'Tomamos as seguintes medidas para remediar este incidente até agora:';
$_['text_report_to_commissioner_ending'] = 'Estamos realizando uma revisão completa dos sistemas potencialmente afetados e notificaremos o Escritório do Comissário de Proteção de Dados se houver algum desdobramento significativo. Estamos implementando medidas de segurança adicionais projetadas para evitar a recorrência de tal ataque e para proteger a privacidade de nossos clientes.';

$_['text_send_breach_notification'] = 'Enviar Notificação de Violação';

// Entry
$_['entry_address_commissioner'] = 'Comissário de dados (Endereço Postal)';
$_['entry_address_store'] = 'Loja (Endereço Postal)';
$_['entry_batch_size'] = 'Tamanho do Batch';
$_['entry_breach_notification_status'] = 'Estado';
$_['entry_breach_customer_email_notification_status'] = 'Status de Notificação por E-mail';
$_['entry_contact_details_of_person_reporting'] = 'Dados de contato do Comissário de Dados';
$_['entry_contact_email'] = 'E-mail de contato para Clientes';
$_['entry_customer_email'] = 'E-mail';
$_['entry_customer_group'] = 'Grupo de Clientes';
$_['entry_customer'] = 'Cliente';
$_['entry_date_added'] = 'Data de Adição';
$_['entry_date_of_breach'] = 'Data(s) de Violação (se conhecida)';
$_['entry_date_of_discovery'] = 'Dia em que o incidente foi descoberto';
$_['entry_email_bcc'] = 'BCC';
$_['entry_email_commissioner'] = 'Comissário de Dados (e-mail)';
//$_['entry_email_cc'] = 'CC';
$_['entry_max_runtime'] = 'Tempo máximo de execução';
$_['entry_message_action'] = 'Breve Descrição de qualquer ação desde que a violação foi descoberta (Comissário)';
$_['entry_message_action_customers'] = 'Breve descrição das ações tomadas (Clientes)';
$_['entry_message_incident'] = 'Breve descrição da violação (Comissário)';
$_['entry_message_incident_customers'] = 'Breve descrição da violação (Clientes)';
$_['entry_name'] = 'Nome do incidente (para sua referência)';
$_['entry_number_of_accounts_affected'] = 'Número de contas afetadas (se conhecidas)';
$_['entry_store'] = 'De';
$_['entry_subject'] = 'Assunto (E-mail do Comissário)';
$_['entry_subject_customers'] = 'Assunto (E-mails dos Clientes)';
$_['entry_to'] = 'Para';

// Help
$_['help_address_commissioner'] = 'Endereço postal completo do Comissário de Proteção de Dados. Isso será impresso no cabeçalho da carta de notificação de violação';
$_['help_address_store'] = 'Endereço postal completo da sua loja que está denunciando a violação. Isto será impresso no cabeçalho da carta';
$_['help_batch_size'] = 'Vários e-mails que podem ser enviados em uma hora. Isso vai depender da configuração do seu servidor. A maioria dos servidores compartilhados irá limitá-lo, portanto, você não poderá enviar mais de 100 a 200 e-mails por hora.';
$_['help_contact_details_of_person_reporting'] = 'Adicionar endereço de e-mail e/ou número de telefone de uma pessoa que pode ser contatado pelo Escritório do Comissário de Proteção de Dados';
$_['help_contact_email'] = 'Adicione um endereço de e-mail que seus clientes possam entrar em contato em relação à violação de dados.';
$_['help_data_commissioner'] = 'Endereço de email do Comissário de dados na sua jurisdição';
$_['help_date_of_breach'] = 'Se você não sabe a data exata, forneça um intervalo de tempo estimado';
$_['help_date_of_discovery'] = 'Data em que você descobriu que seu sistema foi violado';
$_['help_email_bcc'] = 'Endereços de e-mail a serem copiados em oculto na notificação de violação';
//$_['help_email_cc'] = $this->language->get('help_email_cc');
$_['help_max_runtime'] = 'Tempo máximo que o script de envio de email deve ser executado.';
$_['help_message_action'] = 'Descreva todas as ações que você realizou desde que a violação foi descoberta. Este texto será incluído na carta ao Comissário de Proteção de Dados.';
$_['help_message_action_customers'] = 'Descreva todas as ações que você tomou desde que a violação foi descoberta e que são relevantes para seus clientes. Este texto será incluído nos e-mails para os clientes.';
$_['help_message_incident'] = 'Descreva a violação dos detalhes, quem acessou os dados, como eles o fizeram, como foi descoberto etc. Este texto será incluído na carta ao Comissário de Proteção de Dados.';
$_['help_message_incident_customers'] = 'Descreva a violação com todos os detalhes relevantes para seus clientes. Este texto será incluído nos emails para os clientes.';
$_['help_name'] = 'Este nome é apenas para o proprietário da loja/referência de administrador, não será revelado ao Comissário de Dados ou Clientes';
$_['help_number_of_accounts_affected'] = 'ndicar quantas contas (clientes/titulares de dados) foram afetadas pela violação';
$_['help_subject'] = 'Assunto do e-mail de notificação/carta ao Comissário de Proteção de Dados';
$_['help_subject_customers'] = 'Assunto dos e-mails de notificação para os clientes';

// Error
$_['error_address_commissioner'] = 'O endereço do Comissário de Proteção de Dados é obrigatório!';
$_['error_address_store'] = 'O endereço da loja é obrigatório!';
$_['error_contact_details_of_person_reporting'] = 'Detalhes de contato da pessoa que envia o relatório são obrigatórios!';
$_['error_customer_notification_add'] = 'Não foi possível adicionar notificações do cliente';
$_['error_customer_notification_existing'] = 'Já existem notificações para essa violação de dados';
$_['error_data_commissioner'] = 'O e-mail do Data Protection Commissioner é obrigatório!';
$_['error_date_of_breach'] = 'A data de violação é obrigatória!';
$_['error_date_of_discovery'] = 'A data da descoberta da violação é obrigatória!';
$_['error_email_batch'] = 'Não foi possível enviar as notificações por e-mail. Tente novamente.';
$_['error_permission'] = 'Aviso: você não tem permissão para enviar e-mails de notificação de violação';
$_['error_subject'] = 'O Assunto de e-mail de notificação de violação é obrigatório!';
$_['error_mail_bcc'] = 'Formato de e-mail incorreto, forneça endereços de e-mail como uma lista separada por vírgulas.';
$_['error_mail_commissioner'] = 'E-mail para comissário de dados é formato incorreto.';
$_['error_message_action'] = 'Resumo das ações tomadas após a violação é obrigatória!';
$_['error_message_incident'] = 'A Descrição do incidente/violação é obrigatória!';
$_['error_missing_commissioner_email'] = 'O e-mail do Comissário é um campo obrigatório!';
$_['error_saving_breach_notification_failed'] = 'Não foi possível salvar seu registro de notificação de violação. Tente novamente';

// When translating please use similar format (no spaces, underscores separting words)
$_['data_breach_pdf_filename'] = 'data_breach_notification';
