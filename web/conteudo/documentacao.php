<?php
require_once("{$_SERVER ['DOCUMENT_ROOT']}/template/pagina.php");
require_once("{$_SERVER ['DOCUMENT_ROOT']}/codigo/soap/Tuleap.php");
require_once("{$_SERVER ['DOCUMENT_ROOT']}/codigo/util/Enconding.php");



$tpl->addFile("DESCRICAO_PAGINA", "documentacao.html");

if (isset($_REQUEST['group_id']))
{
    $projeto = UtilDAO::getResult(Querys::SELECT_PROJETO_BY_ID, intval($_REQUEST['group_id']))[0];
    $tpl->CAMINHO_PAGINA = " > Documentação ({$projeto->unix_group_name})";
    if (isset($_REQUEST['sprint']))
    {
        
        $release = UtilDAO::getResult(Querys::SELECT_RELEASE_BY_ARTIFACT_ID, intval($_REQUEST['sprint']))[0]->rel;

        $dirTmp = "{$_SERVER ['DOCUMENT_ROOT']}/tmp{$_SESSION ['ID_USUARIO']}";

        // cria diretorio release e copia arquivo roteiro
        $dirProjeto = "{$dirTmp}/{$projeto->unix_group_name}-release-{$release}";
        Util::criaPasta($dirProjeto);
        Util::converterTemplate(
            TEMPLATE_ROTEIRO,
            [
                '##_NOME_SISTEMA_##',
                '##_UNIX_NAME_##',
                '##_NUMERO_RELEASE_##',
                '##_NUMERO_SPRINT_##'
            ],
            [
                $projeto->group_name,
                $projeto->unix_group_name,
                $release,
                $_REQUEST['sprint']
            ],
            "{$dirProjeto}/roteiroPublicacaoRelease{$release}.docx");

        // cria diretorio sprint e copia arquivo termo
        $dirRelease = "{$dirProjeto}/sprint-{$_REQUEST['sprint']}";
        Util::criaPasta($dirRelease);
        Util::converterTemplate(
            TEMPLATE_TERMO_ENTREGA,
            [
                '##_NOME_SISTEMA_##',
                '##_UNIX_NAME_##',
                '##_NUMERO_RELEASE_##',
                '##_NUMERO_SPRINT_##'
            ],
            [
                $projeto->group_name,
                $projeto->unix_group_name,
                $release,
                $_REQUEST['sprint']
            ],
            "{$dirRelease}/termoDeEntrega-sprint-{$_REQUEST['sprint']}.docx");

        $story = [];
        foreach (UtilDAO::getResult(Querys::SELECT_VALUES_BY_SPRINT, intval($_REQUEST['sprint'])) as $row)
        {
            $story[$row->artifact_id][$row->field_name] = Encoding::fixUTF8(preg_replace('/\s+/S', " ", trim(html_entity_decode(stripslashes(strip_tags($row->field_value)), ENT_QUOTES, 'UTF-8'))));
        }

        foreach ($story as $key => $row)
        {
            // cria diretorio historia e copia arquivo analise funcionalidades
            Util::criaPasta("{$dirRelease}/historia-{$key}");
            Util::converterTemplate(
                TEMPLATE_HISTORIA,
                [
                    '##_NOME_SISTEMA_##',
                    '##_UNIX_NAME_##',
                    '##_NUMERO_RELEASE_##',
                    '##_NUMERO_SPRINT_##',
                    '##_NUMERO_STORY_##',
                    '##_DESCRICAO_##',
                    '##_BREVE_DESCRICAO_##',
                    '##_FUNCIONALIDADE_##'
                ],
                [
                    $projeto->group_name,
                    $projeto->unix_group_name,
                    $release,
                    $_REQUEST['sprint'],
                    $key,
                    $row['como_demonstrar'] . " " . $row['acceptance_criteria_1'],
                    $row['observao'],
                    $row['in_order_to_1']
                ],
                "{$dirRelease}/historia-{$key}/AnaliseFuncionalidadesHistoria{$key}.docx");
        }

        Util::criaPasta("{$dirRelease}/scripts");

        $zip = "{$_SERVER ['DOCUMENT_ROOT']}/{$projeto->unix_group_name}-release-{$release}.zip";
        Util::zipFile($dirTmp, $zip);

        ignore_user_abort(true);

        header('Content-type:  application/zip');
        header('Content-Length: ' . filesize($zip));
        header("Content-Disposition: attachment; filename=\"{$projeto->unix_group_name}-release-{$release}.zip\"");

        readfile($zip);

        Util::removeDir($dirTmp);

        unlink($zip);
    }
    else
    {
        foreach (UtilDAO::getResult(Querys::SELECT_SPRINT_RELEASE_BY_GROUP_ID, $_REQUEST['group_id']) as $row)
        {
            $tpl->GROUP_ID = $_REQUEST['group_id'];
            $tpl->RELEASE = "release #{$row->rel} - sprint #{$row->sprint}";
            $tpl->SPRINT = $row->sprint;

            $tpl->block('BLOCK_RELEASE');
        }
    }
}
else
{
    $tpl->CAMINHO_PAGINA = " > Documentação";
    foreach (UtilDAO::getResult(Querys::SELECT_PROJETO) as $row)
    {
        $tpl->GROUP_NAME = $row->group_name;
        $tpl->GROUP_ID = $row->group_id;
        $tpl->UNIX_GROUP_NAME = $row->unix_group_name;

        $tpl->block('BLOCK_GROUP');
    }
}

$tpl->show();