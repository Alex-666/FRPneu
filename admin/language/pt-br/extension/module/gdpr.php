<?php
// Module
$_['text_gdpr'] = 'RGPD';
$_['text_gdpr_settings'] = 'Configurações do RGPD';
$_['text_gdpr_report'] = 'Histórico Pedidos RGPD';

// Heading
$_['heading_title'] = 'RGPD';
$_['module_name'] = 'RGPD';

// Buttons etc.
$_['button_save'] = 'Guardar';
$_['button_cancel'] = 'Cancelar';

// Entry
$_['entry_admin'] = 'Acesso permitido apenas a Administradores.';
$_['entry_status'] = 'Estado';
$_['entry_name'] = 'Nome';
$_['entry_message_text'] = 'Mensagem a apresentar';
$_['entry_date_start'] = 'Data Inícial (AAAA-MM-DD)';
$_['entry_date_end'] = 'Data Final (AAAA-MM-DD)';
$_['entry_status'] = 'Estado';

$_['entry_email_footer'] = 'Texto de Rodapé do e-mail RGPD';
$_['entry_email_header'] = 'Texto de Cabeçalho do e-mail RGPD';
$_['entry_locations_of_other_data'] = 'Outros locais/serviços onde são armazenados dados pessoais';
$_['entry_locations_of_servers'] = 'Localização física dos servidores que hospedam seu website e outros dados.';
$_['entry_max_requests_day'] = 'Nº Máximo de Pedidos Diários';
$_['entry_pending_status'] = 'Estado do texto Pendente';
$_['entry_confirmed_status'] = 'Estado do texto Confirmado';
$_['entry_emailed_status'] = 'Estado do texto Enviado por e-mail';
$_['entry_account_deleted_status'] = 'Estado do texto de Contas Apagadas';
$_['entry_data_sent'] = 'Informação Enviada';
$_['entry_unpaid'] = 'Texto Aguardar Pagamento';
$_['entry_free'] = 'Texto Gratuito';
$_['entry_rejected'] = 'Texto Rejeitado';
$_['entry_fairuse'] = 'Uso Justo';
$_['entry_max_days_process'] = 'Nº. Máximo de Dias para Responder';
$_['entry_right_to_be_forgotten'] = 'Permitir Direito a Ser Esquecido';

// Help
$_['help_pending_status'] = 'Nome do estado de um pedido RGPD qua não foi ainda confirmado pelo cliente. O nome deste estado será mostrado no relatório do admin do RGPD e revelado ao cliente no seu histórico de pedidos RGPD.';
$_['help_confirmed_status'] = 'Nome do estado de um pedido RGPD confirmado pelo cliente utilizando o link de confirmação enviado no e-mail mas que ainda não foi processado (o e-mail com o relatório ainda não foi enviado). Este nome de estado será mostrado no relatório do admin do RGPD e revelado ao cliente no seu histórico de pedidos RGPD.';
$_['help_emailed_status'] = 'Nome do estado de um pedido RGPD que foi já processado/completado (o e-mail com o relatório foi já enviado ao cliente). Este nome de estado será mostrado no relatório do admin do RGPD e revelado ao cliente no seu histórico de pedidos RGPD.';
$_['help_account_deleted_status'] = 'Nome do estado de um pedido de remoção de uma conta RGPD  já processada/completada. Este nome de estado será mostrado no relatório do admin do RGPD.';
$_['help_locations_of_other_data'] = 'Listar aqui todas as outras localizações onde armazena os dados dos clientes. Exemplos: Mailchimp, Google Docs, etc. Esta informação será incluida no e-mail/relatório de RGPD enviado ao cliente.';
$_['help_locations_of_servers'] = 'Listar toda a informação relevante sobre a hospedagem do seu site e localização dos servidores (Por exemplo: países onde estão localizados os servidores, as empresas de hospedagem, se estão dentro das normas RGPD? etc.).';
$_['help_max_requests_day'] = 'Número de pedidos que cada cliente está autorizado a efectuar por dia. Deve ser um número baixo para evitar spam, ataques e carga desnecessária em seu servidor web. Recomenda-se um valor entre 3-5 no máximo.';
$_['help_right_to_be_forgotten'] = 'Esta função activa uma forma de \'Direito a Ser Esquecido\' da qual os clientes podem apagar automaticamente a sua conta e anonimizar os seus dados pessoais quando não for possível apagar os mesmos';

// Error
$_['error_permission'] = 'Aviso: Não tem permissão para modificar o módulo RGPD!';

// Text
$_['text_module']      = 'Módulos';
$_['text_success']     = 'Sucesso: Modificou o módulo RGPD!';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Aceitação de Política de Armazenamento';
$_['entry_forms_are_private'] = 'Formulários GDPR exigem Login';

$_['help_store_policy_acceptance'] = 'Se definido como sim, sempre que o cliente aceitar seus termos na página de registro ou na finalização da compra, isso será registrado no banco de dados. IMPORTANTE: se você configurá-lo como sim, verifique se o checkout está funcionando corretamente!';
$_['help_forms_are_private'] = 'Se definido como sim, a solicitação GDPR só poderá ser enviada por um cliente conectado.';
