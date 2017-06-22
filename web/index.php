<?php
$tpl = new raelgc\Template ('index.html', false);

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}
else
{
    session_destroy();
    session_start();
}

$tpl->NOME_SISTEMA = NOME_SISTEMA;
$tpl->CHAMADA_AJAX = CHAMADA_AJAX;
$tpl->DIRETORIO_CONTEUDO = DIRETORIO_RAIZ_VIEW;
$tpl->PAGINA_PRINCIPAL = PAGINA_PRINCIPAL;

$tpl->show();
