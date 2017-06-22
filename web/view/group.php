<?php
Portal\Template::carregarTemplate($tpl);

$tpl->CAMINHO_PAGINA = " > Grupo";


$tpl->addFile('CONTEUDO_CENTRAL',  DIRETORIO_VIEW . '/group.html');

$group_id = $_REQUEST['group_id'];
$group = UtilDAO::getResult(Querys::SELECT_PROJETO_BY_ID, $group_id);
if (count($group) > 0)
{
    $group = $group[0];
}

$tpl->NOME_PAGINA = $group->group_name;
$tpl->DESCRIPTION = $group->description;
$tpl->SIGLA = $group->unix_group_name;
$tpl->GROUP_ID = $group_id;
$tpl->CHAMADA_AJAX = CHAMADA_AJAX; 

$banco = UtilDAO::getResult(Querys::SELECT_TREE_BY_GROUPID, $group_id);
if (count($banco) > 0)
{
    $release = $banco[0]->rel;
    $sprint = $banco[0]->sprint;
    $esforco_sp = $banco[0]->esforco_sp;
    $story = $banco[0]->story;
    $esforco_st = $banco[0]->esforco_st;
    $status_rel = $status_spt = ucfirst($banco[0]->status_sprint);
    $status_sto = ucfirst($banco[0]->status_story);
    foreach ($banco as $row)
    {
        if ($story != $row->story)
        {
            $tpl->STORY = "story #{$story}";
            $tpl->ESFORCO_STORY = $esforco_st;
            $tpl->STATUS_STORY = $status_sto;
            $tpl->INFO_STORY = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($story)));
            $tpl->block('BLOCK_STORY');

            $story = $row->story;
            $esforco_st = $row->esforco_st;
            $status_sto = ucfirst($row->status_story);
            $tpl->clear('STORY');
        }
        if (!empty($row->task))
        {
            $tpl->TASK = "task #{$row->task}";
            $tpl->ESFORCO_TASK = empty($row->esforco_tk) ? '' : "&ensp;&ensp;&ensp;{$row->esforco_tk}h";
            $tpl->STATUS_TASK = ucfirst($row->status_task);
            $tpl->INFO_TASK = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($row->task)));
            $tpl->block('BLOCK_TASK');
        }
        else
        {
            $tpl->clear('TASK');
        }

        if ($sprint != $row->sprint)
        {
            $tpl->SPRINT = "sprint #{$sprint}";
            $tpl->ESFORCO_SPRINT = $esforco_sp;
            $tpl->STATUS_SPRINT = $status_spt;
            $tpl->INFO_SPRINT = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($sprint)));
            $tpl->block('BLOCK_SPRINT');

            $sprint = $row->sprint;
            $esforco_sp = $row->esforco_sp;
            $status_spt = ucfirst($row->status_sprint);
            $tpl->clear('SPRINT');
        }
        if ($release != $row->rel)
        {
            $tpl->RELEASE = "release #{$release}";
            $tpl->STATUS_RELEASE = $status_rel;
            $tpl->INFO_RELEASE = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, $release));
            $tpl->block('BLOCK_RELEASE');

            $release = $row->rel;
            $status_rel = ucfirst($row->status_sprint);
            $tpl->clear('RELEASE');
        }
    }

    $tpl->STORY = "story #{$story}";
    $tpl->ESFORCO_STORY = $esforco_st;
    $tpl->STATUS_STORY = $status_sto;
    $tpl->INFO_STORY = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($story)));
    $tpl->block('BLOCK_STORY');

    $tpl->SPRINT = "sprint #{$sprint}";
    $tpl->ESFORCO_SPRINT = $esforco_sp;
    $tpl->STATUS_SPRINT = $status_spt;
    $tpl->INFO_SPRINT = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($sprint)));
    $tpl->block('BLOCK_SPRINT');

    $tpl->RELEASE = "release #{$release}";
    $tpl->STATUS_RELEASE = $status_rel;
    $tpl->INFO_RELEASE = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, $release));
    $tpl->block('BLOCK_RELEASE');
}

$banco = UtilDAO::getResult(Querys::SELECT_UNRELATED_BY_GROUPID, $group_id);
if (count($banco) > 0)
{
    $release = $banco[0]->rel;
    $story = $banco[0]->story;
    $status_sto = ucfirst($banco[0]->status_story);
    foreach ($banco as $row)
    {
        if ($story != $row->story)
        {
            $tpl->STORY = "story #{$story}";
            $tpl->STATUS_STORY = $status_sto;
            $tpl->INFO_STORY = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($story)));
            $tpl->block('BLOCK_N_STORY');

            $story = $row->story;
            $status_sto = ucfirst($row->status_story);
            $tpl->clear('STORY');
        }
        if (!empty($row->task))
        {
            $tpl->TASK = "task #{$row->task}";
            $tpl->STATUS_TASK = ucfirst($row->status_task);
            $tpl->INFO_TASK = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($row->task)));
            $tpl->block('BLOCK_N_TASK');
        }
        else
        {
            $tpl->clear('TASK');
        }

        if ($release != $row->rel)
        {
            $tpl->CSS_RELEASE = 'release';
            $tpl->RELEASE = "release #{$release}";
            $tpl->INFO_RELEASE = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, $release));
            $tpl->block('BLOCK_N_RELEASE');

            $release = $row->rel;
            $tpl->clear('RELEASE');
        }
    }

    $tpl->STORY = "story #{$story}";
    $tpl->STATUS_STORY = $status_sto;
    $tpl->INFO_STORY = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, intval($story)));
    $tpl->block('BLOCK_N_STORY');

    if (empty($release))
    {
        $tpl->CSS_RELEASE = 'unkown';
        $tpl->RELEASE = 'N/A';
        $tpl->STATUS_RELEASE = '';
        $tpl->INFO_RELEASE = '';
    }
    else
    {
        $tpl->CSS_RELEASE = 'release';
        $tpl->RELEASE = "release #{$release}";
        $tpl->STATUS_RELEASE = $status_rel;
        $tpl->INFO_RELEASE = Portal\Template::preparaPopOver('', 'label', 'value', UtilDAO::getResult(Querys::SELECT_FIELDS_HTML_BY_ARTIFACT, $release));
    }
    $tpl->block('BLOCK_N_RELEASE');
}


$tpl->show();