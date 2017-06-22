<?php
Portal\Template::carregarTemplate($tpl);

$tpl->addFile('CONTEUDO_CENTRAL', DIRETORIO_VIEW . '/addUsuario.html');

$tpl->NOME_PAGINA = 'Cadastrar Usuário';
$tpl->DESCRICAO_PAGINA = '';
$tpl->CHAMADA_AJAX = CHAMADA_AJAX;

if (isset($_SERVER['HTTP_REFERER']))
{
    $tpl->PAGINA_RETORNO = substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], '?') > 0
            ? strpos($_SERVER['HTTP_REFERER'], '?')
            : strlen($_SERVER['HTTP_REFERER'])) . '?' . http_build_query(unserialize($_COOKIE['POST'])) . '&retorno=1';
}
else
{
    $tpl->PAGINA_RETORNO = PAGINA_PRINCIPAL;
}

// se tiver id no get
if (!empty ($_GET ['id']))
{
    $tpl->NOME_PAGINA = 'Modificar Usuário';

    $tpl->ID_JSON = 46832;

    // preencher os campos de contrato
    foreach (UtilDAO::getResult(Querys::SELECT_USUARIO_BY_ID, $_GET ['id']) as $row)
    {
        // script para preenchimento do valor de combos
        $tpl->SCRIPT_PERFIL = Portal\Template::SendReadyScript('$("#perfil").val("' . $row->perfil . '");');

        // preenche valor dos campos text
        $tpl->VALUE_ID_USUARIO = $row->usuario_id;
        $tpl->VALUE_NOME = $row->nome;
        $tpl->VALUE_TULEAP_USER = $row->tuleap_user;
        $tpl->VALUE_EMAIL = $row->email;

        $tpl->VALUE_ATIVO_EXTENSO = ($row->ativo == 'S' ? 'ATIVADO' : 'DESATIVADO');
        $tpl->BTN_ATIVO = ($row->ativo == 'S' ? 'Desativar' : 'Ativar');
        $tpl->CLASS_ATIVO = ($row->ativo == 'S' ? 'btn-danger' : 'btn-success');
        $tpl->VALUE_ATIVO = $row->ativo;
        $tpl->block('BLOCK_ATIVO');
        $tpl->block('BLOCK_PERFIL');
    }
}
else
{
    if (isset ($_GET ['corrente']))
    {
        $tpl->ID_JSON = 287354;

        $tpl->NOME_PAGINA = 'Modificar Usuário';

        $row = UtilDAO::getResult(Querys::SELECT_USUARIO_BY_ID, $_SESSION ['ID_USUARIO'])[0];
        $tpl->VALUE_NOME = $row->nome;
        $tpl->VALUE_ID_USUARIO = $row->usuario_id;
        $tpl->VALUE_EMAIL = $row->email;
        $tpl->VALUE_TULEAP_USER = $row->tuleap_user;
    }
    else
    {
        $tpl->ID_JSON = 9874312;
        $tpl->block('BLOCK_PERFIL');
    }
}

$tpl->show();