<?php

// DEBUG
const DEBUG = "true";
if (DEBUG == "true")
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Configuracoes do Portal
const TITULO_PAGINA = "Gestão";
const NOME_SISTEMA = "Gestão";
const EMPRESA_SISTEMA = "Cast Group Inc.";
const ANO_SISTEMA = "2017";
const FAV_ICON = "http://servicos.dnit.gov.br/sigacont/img/favicon-2.png";
define('DIRETORIO_RAIZ', "http://{$_SERVER['HTTP_HOST']}");
const CHAMADA_AJAX = "/codigo/controle/JSON.php";
const DIRETORIO_CONTEUDO = "/conteudo";
const PAGINA_PRINCIPAL = "/default.php";
define("DIRETORIO_IMPORT", $_SERVER ['DOCUMENT_ROOT']);

// Configuracoes de Banco
define("BANCO_URL", $_SERVER ['DOCUMENT_ROOT'] . "/db\\banco.db");

// Configuracao de Perfil
const PERFIL_0_ADMIN = "0";
const PERFIL_1_ESCRITA = "1";
const PERFIL_2_CONSULTA = "2";

/**
 * Tratamento de erro padrão do portal
 *
 * @param string    $msg
 * @param Exception $ex
 * @throws Exception
 */
function trataErro ($msg, Exception $ex = null)
{
    if (DEBUG == "true")
    {
        $msg = str_replace("\n", "<br>", $msg);
        //generateCallTrace();

        if (!isset ($ex))
        {
            throw new Exception ($msg);
        }
    }
    else
    {
        if (isset ($ex))
        {
            if (stripos($ex->getMessage(), "duplicate key") >= 0)
            {
                throw new Exception ("Dado já existente em banco. Não é possivel inserir o mesmo dado duas vezes.", -1, $ex);
            }
        }
    }

    throw new Exception ($msg, -1, $ex);
}

function generateCallTrace($e)
{
    $trace = explode("\n", $e->getTraceAsString());

    echo implode("<br>", $trace);
}