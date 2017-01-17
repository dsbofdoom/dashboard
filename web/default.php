<?php
require_once($_SERVER ['DOCUMENT_ROOT'] . "/template/pagina.php");
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/soap/Tuleap.php");

$tpl->CAMINHO_PAGINA = "";

$tpl->NOME_PAGINA = "";

$tuleap = new Tuleap('saulocorreia', 'Carol010');

$tpl->addFile("DESCRICAO_PAGINA", "default.html");

$saida = '';
$time_start = microtime(true);
if (isset($_POST['projeto']))
{
    $saida = $tuleap->inserirDadosProjeto();
}
if (isset($_POST['tracker']))
{
    $saida = $tuleap->inserirDadosTracker();
}
if (isset($_POST['artifacts']))
{
    $saida = $tuleap->inserirDadosArtifacts();
}
$time_end = microtime(true);
$time = $time_end - $time_start;

$tpl->SAIDA_WS = "Process Time: {$time}<pre>{$saida}</pre>";

$tpl->show();