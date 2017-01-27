<?php
require_once($_SERVER ['DOCUMENT_ROOT'] . "/template/pagina.php");
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/soap/Tuleap.php");

$tpl->CAMINHO_PAGINA = " > Grupo";


$tpl->addFile("DESCRICAO_PAGINA", "group.html");

$group_id = $_REQUEST['group_id'];
$group = UtilDAO::getResult(Querys::SELECT_PROJETO_BY_ID, $group_id);
if (count($group) > 0)
    $group = $group[0];

$tpl->NOME_PAGINA = $group->group_name;
$tpl->DESCRIPTION = $group->description;
$tpl->SIGLA = $group->unix_group_name;


$tpl->show();