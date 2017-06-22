<?php
Portal\Template::carregarTemplate($tpl);

$tpl->addFile('CONTEUDO_CENTRAL', DIRETORIO_VIEW . '/tabela.html');

$tpl->NOME_PAGINA = 'Buscar Tabelas';
$tpl->DESCRICAO_PAGINA = '';
$tpl->CHAMADA_AJAX = CHAMADA_AJAX;

$tpl->ID_JSON = 45678;
$tpl->ID_JSON_SALVAR = 4523;

$tpl->show();