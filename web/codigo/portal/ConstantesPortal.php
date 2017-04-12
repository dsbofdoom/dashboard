<?php

// DEBUG
const DEBUG = true;
if (DEBUG)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Configuracoes do Portal
const TITULO_PAGINA = "Dashboard";
const NOME_SISTEMA = "Dashboard";
const EMPRESA_SISTEMA = "Cast Group Inc.";
const ANO_SISTEMA = "2017";
const FAV_ICON = "";
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

// Constantes dos arquivos de template 
define('TEMPLATE_ROTEIRO', "{$_SERVER ['DOCUMENT_ROOT']}/arquivos/roteiro");
define('TEMPLATE_TERMO_ENTREGA', "{$_SERVER ['DOCUMENT_ROOT']}/arquivos/termo");
define('TEMPLATE_HISTORIA', "{$_SERVER ['DOCUMENT_ROOT']}/arquivos/AnaliseFuncionalidades");

spl_autoload_register("my_autoload", true, true);

new PoolConexao();

/**
 * Tratamento de erro padrão do portal
 *
 * @param string    $msg
 * @param Exception $ex
 * @throws Exception
 */
function trataErro (string $msg, Exception $ex = null)
{
    if (DEBUG)
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

function generateCallTrace ($e)
{
    if (!DEBUG)
    {
        return;
    }

    $trace = explode("\n", $e->getTraceAsString());

    echo implode("<br>", $trace);
}

function my_autoload ($className)
{
    carregaClasseRecursiva($_SERVER ['DOCUMENT_ROOT'] . '/codigo/', strtolower("$className.php"));
}

function carregaClasseRecursiva ($directory, $classe)
{
    foreach (scandir($directory) as $file)
    {
        if ($file == '.' || $file == '..')
        {
            continue;
        }

        if (is_dir($directory . DIRECTORY_SEPARATOR . $file))
        {
            if (carregaClasseRecursiva($directory . DIRECTORY_SEPARATOR . $file, $classe))
            {
                return;
            }
        }
        elseif (strtolower($file) == $classe)
        {
            require_once($directory . DIRECTORY_SEPARATOR . $file);

            return true;
        }
    }

    return false;
}