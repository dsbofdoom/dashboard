<?php
Portal\Template::carregarTemplate($tpl);

$tpl->addFile('CONTEUDO_CENTRAL', DIRETORIO_VIEW . '/viewUsuario.html');

$tpl->CHAMADA_AJAX = CHAMADA_AJAX;
$tpl->NOME_PAGINA = 'Pesquisar Usuário';
$tpl->DESCRICAO_PAGINA = '';

if (empty($_POST) && !empty($_COOKIE['POST']) && !empty($_REQUEST['retorno']))
{
    $_POST['nome'] = $_REQUEST['nome'];
    $_POST['email'] = $_REQUEST['email'];
    $_POST['perfil'] = $_REQUEST['perfil'];
}

if (!empty ($_POST))
{
    setcookie('POST', serialize($_POST));

    $where = '';
    $parametro = [];

    $pos = 1;
    if (!empty ($_POST ['nome']))
    {
        $tpl->VALUE_NOME = $_POST ['nome'];

        $where .= " AND nome ~* \${$pos}";
        $parametro[] = $_POST ['nome'];
        $pos++;
    }
    if (!empty ($_POST ['email']))
    {
        $tpl->VALUE_EMAIL = $_POST ['email'];

        $where .= " AND email ~* \${$pos}";
        $parametro [] = $_POST ['email'];
        $pos++;
    }
    if (!empty ($_POST ['tuleap_user']))
    {
        $tpl->VALUE_TULEAP_USER = $_POST ['tuleap_user'];

        $where .= " AND tuleap_user ~* \${$pos}";
        $parametro [] = $_POST ['tuleap_user'];
        $pos++;
    }
    if (isset ($_POST ['perfil']) and ($_POST ['perfil'] == '0' or !empty ($_POST ['perfil'])))
    {
        $tpl->SCRIPT_PERFIL = Portal\Template::SendReadyScript('$("#perfil").val("' . $_POST ['perfil'] . '");');

        $where .= " AND perfil = \${$pos}";
        $parametro [] = $_POST ['perfil'];
        $pos++;
    }
    foreach (UtilDAO::getResultArrayParam(Querys::SELECT_USUARIO . "$where ORDER BY 1", $parametro) as $row)
    {
        $tpl->ID_USUARIO = $row->usuario_id;
        $tpl->NOME = $row->nome;
        $tpl->TULEAP_USER = $row->tuleap_user;
        $tpl->EMAIL = $row->email;

        switch ($row->perfil)
        {
            case PERFIL_0_ADMIN :
                $tpl->PERFIL = 'Administrador';
                break;
            case PERFIL_1_ESCRITA :
                $tpl->PERFIL = 'Escrita';
                break;
            case PERFIL_2_CONSULTA :
                $tpl->PERFIL = 'Consulta';
                break;
        }

        $tpl->ATIVO = ($row->ativo == 'S' ? 'Ativo' : 'Inativo');

        $tpl->block('BLOCK_VALORES');
    }
}

$tpl->show();