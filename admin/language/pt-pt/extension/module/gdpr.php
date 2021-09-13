<?php
// Module
$_['text_gdpr'] = 'RGPD';
$_['text_gdpr_settings'] = 'Configurações do RGPD';
$_['text_gdpr_report'] = 'Histórico de Pedidos RGPD';

// Heading
$_['heading_title'] = 'RGPD - Regulamento Geral da Proteção de Dados';
$_['module_name'] = 'RGPD - Regulamento Geral da Proteção de Dados';

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
$_['entry_locations_of_servers'] = 'Localização física dos servidores que alojam o seu website e outros dados.';
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
$_['help_pending_status'] = 'Nome do estado de um pedido RGPD qua não tenha ainda sido confirmado pelo cliente. O nome deste estado será apresentado no relatório do administrador do RGPD e revelado ao cliente no seu histórico de pedidos RGPD.';
$_['help_confirmed_status'] = 'Nome do estado de um pedido RGPD confirmado pelo cliente utilizando o link de confirmação enviado no e-mail mas que ainda não tenha sido processado (o e-mail com o relatório ainda não foi enviado). Este nome de estado será apresentado no relatório do administrador do RGPD e revelado ao cliente no seu histórico de pedidos RGPD.';
$_['help_emailed_status'] = 'Nome do estado de um pedido RGPD que já tenha sido processado/completo (o e-mail com o relatório já foi enviado ao cliente). Este nome de estado será apresentado no relatório do administrador do RGPD e revelado ao cliente no seu histórico de pedidos RGPD.';
$_['help_account_deleted_status'] = 'Nome do estado de um pedido de remoção de uma conta RGPD  que já sido processada/completa. Este nome de estado será apresentado no relatório do administrador do RGPD.';
$_['help_locations_of_other_data'] = 'Apresentar aqui todas as outras localizações onde são armazenados dados dos clientes. Exemplos: Mailchimp, Google Docs, etc. Esta informação será incluida no e-mail/relatório de RGPD enviado ao cliente.';
$_['help_locations_of_servers'] = 'Apresentar toda a informação relevante sobre o alojamento do seu site e da localização dos servidores (por exemplo: países onde estão localizados os servidores, as empresas de alojamento, se estão dentro das normas RGPD? etc.).';
$_['help_max_requests_day'] = 'Número de pedidos que cada cliente está autorizado a realizar por dia. Deve ser um número baixo de forma a evitar spam, ataques e uma carga desnecessária no seu servidor web. Recomenda-se a utilização um valor máximo entre 3-5.';
$_['help_right_to_be_forgotten'] = 'Esta função ativa uma opção de \'Direito a Ser Esquecido\' na qual os clientes podem apagar automaticamente a sua conta e tornar os seus dados pessoais anónimos quando não os for possível apagar.';

// Error
$_['error_permission'] = 'Aviso: Não tem permissão para modificar o módulo RGPD!';

// Text
$_['text_module']      = 'Módulos';
$_['text_success']     = 'Sucesso: Modificou o módulo RGPD!';

// Added in v1.4
$_['entry_store_policy_acceptance'] = 'Aceitação da Política de Armazenamento';
$_['entry_forms_are_private'] = 'Os formulários GDPR exigem autênticação';

$_['help_store_policy_acceptance'] = 'Se definido como sim, sempre que o cliente aceite os seus termos na página de registo ou na finalização da compra, essa informação será guardada na base de dados. IMPORTANTE: se você o configurar como sim, verifique se a página de checkout está a funcionar corretamente!';
$_['help_forms_are_private'] = 'Se definido como sim, a solicitação GDPR só poderá ser enviada por um cliente autênticado.';
