<?php
require_once("{$_SERVER ['DOCUMENT_ROOT']}/template/pagina.php");

$tpl->addFile('DESCRICAO_PAGINA', 'documentacao.html');

if (isset($_REQUEST['group_id']))
{
    $projeto = UtilDAO::getResult(Querys::SELECT_PROJETO_CONF_BY_ID, intval($_REQUEST['group_id']))[0];
    $tpl->CAMINHO_PAGINA = " > Documentação ({$projeto->group_name})";
    $tpl->GROUP_NAME = $projeto->group_name;

    if (isset($_REQUEST['sprint']))
    {
        $dirTmp = "{$_SERVER ['DOCUMENT_ROOT']}/tmp{$_SESSION ['ID_USUARIO']}";

        $release = UtilDAO::getResult(Querys::SELECT_RELEASE_BY_ARTIFACT_ID, intval($_REQUEST['sprint']))[0]->rel;

        $zip = UtilDocx::montarDocumentacao($dirTmp, $projeto, $release);

        ignore_user_abort(true);

        header('Content-type: application/zip');
        header('Content-Length: ' . filesize($zip));
        header("Content-Disposition: attachment; filename=\"{$projeto->unix_group_name}-release-{$release}.zip\"");

        readfile($zip);

        Util::removeDir($dirTmp);

        unlink($zip);

        exit();
    }
    else
    {
        $tpl->GROUP_ID = $_REQUEST['group_id'];
        $tpl->CHAMADA_AJAX = CHAMADA_AJAX;
        $tpl->ID_JSON = 8762;

        foreach (UtilDAO::getResult(Querys::SELECT_PROJETO_CONF_BY_ID, $_REQUEST['group_id']) as $row){
            $tpl->UNIX_NAME = $row->unix_group_name;
        }
        

        foreach (UtilDAO::getResult(Querys::SELECT_SPRINT_RELEASE_BY_GROUP_ID, $_REQUEST['group_id']) as $row)
        {
            $tpl->RELEASE = "release #{$row->rel} - sprint #{$row->sprint}";
            $tpl->SPRINT = $row->sprint;

            $tpl->block('BLOCK_RELEASE');
        }
    }
}
else
{
    $tpl->CAMINHO_PAGINA = ' > Documentação';
    foreach (UtilDAO::getResult(Querys::SELECT_PROJETO) as $row)
    {
        $tpl->GROUP_NAME = $row->group_name;
        $tpl->GROUP_ID = $row->group_id;
        $tpl->UNIX_GROUP_NAME = $row->unix_group_name;

        $tpl->block('BLOCK_GROUP');
    }
}

$tpl->show();