<?php
require_once('controle/Portal/ConstantesPortal.php');
require_once('controle/Portal/Gestao.php');

$arquivo = "{$_REQUEST['file']}";

// Registradores de erro
register_shutdown_function("Portal\Gestao::check_for_fatal");
set_error_handler("Portal\Gestao::log_error");
set_exception_handler("Portal\Gestao::log_exception");

// carregar os errors log para DEBUG
if (DEBUG)
{
    ini_set('display_errors', 1);
    error_log("CHAMADA => {$arquivo} ");
    error_reporting(E_ALL);
}
else
{
    ini_set("display_errors", "off");
}

// Registra o AutoLoad
spl_autoload_register('Portal\Gestao::autoload', true, true);

// Inicializa o Pool de Conexao
new PoolConexao();

// Verifica a sessao por ultimo, evitando que entre em loop na hora do login
if (
    !(Util::endsWith($arquivo, 'index') || Util::endsWith($arquivo, 'index.php'))
    && !((Util::endsWith($arquivo, 'JSON') || Util::endsWith($arquivo, 'JSON.php') && $_REQUEST['comando'] == "3894"))
)
{
    Portal\Gestao::verificaSessao($arquivo);
}

// Carrega o arquivo pedido
require $arquivo;