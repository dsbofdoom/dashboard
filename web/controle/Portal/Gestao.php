<?php
/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 19/06/2017
 * Time: 10:26
 */

namespace Portal;


class Gestao
{
    const ARQUIVOS_AUTOLOAD = 'ArquivosAutoLoad';
    const CARGA_TULEAP = 'CargaTuleap';

    /**
     * Tratamento de erro padrão do portal
     *
     * @param string     $msg
     * @param \Exception $ex
     * @throws \Exception
     */
    public static function trataErro (string $msg, \Exception $ex = null)
    {
        self::log_exception($ex, $msg);
    }

    public static function autoload ($className)
    {
        // splitar nas barras
        $className = explode('\\', $className);

        // buscar somente o nome da classe
        $classe = strtolower("{$className[count($className) - 1]}.php");

        // remove a classe e unifica o namespace
        unset($className[count($className) - 1]);
        $namespace = strtolower(implode(DIRECTORY_SEPARATOR, $className));

        // busca dados memcache
        $ArquivosAutoLoad = Cache::getMemcache(self::ARQUIVOS_AUTOLOAD);
        if ($ArquivosAutoLoad === false)
        {
            if (DEBUG)
            {
                error_log('Carregando Autoload');
            }

            // Carrega todos os arquivos
            $arquivos = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(DIRETORIO_IMPORT . DIRECTORY_SEPARATOR . DIRETORIO_CONTROLE), \RecursiveIteratorIterator::LEAVES_ONLY);

            // busca a classe por namespace
            foreach ($arquivos as $file)
            {
                if (substr($file->getPathname(), -1) == '.' || substr($file->getPathname(), -2) == '..')
                {
                    continue;
                }

                $fileMin = strtolower($file->getPathname());
                $fileSplit = explode(DIRECTORY_SEPARATOR, $fileMin);

                $fileClasse = $fileSplit[count($fileSplit) - 1];
                unset($fileSplit[count($fileSplit) - 1]);
                $fileNamespace = implode(DIRECTORY_SEPARATOR, $fileSplit);

                $ArquivosAutoLoad[] = [
                    'namespace' => $fileNamespace,
                    'classe'    => $fileClasse,
                    'arquivo'   => $file->getPathname()
                ];
            }

            // guarda dados memcached
            Cache::setMemcache(self::ARQUIVOS_AUTOLOAD, $ArquivosAutoLoad, 10);
        }

        // busca a classe por namespace
        foreach ($ArquivosAutoLoad as $arquivo)
        {
            if (
                (empty($namespace) && $arquivo['classe'] == $classe)
                || (!empty($namespace) && $arquivo['classe'] == $classe && \Util::endsWith($arquivo['namespace'], $namespace))
            )
            {
                require_once($arquivo['arquivo']);

                return true;
            }
        }
    }

    public static function generateCallTrace ($e)
    {
        if (!DEBUG)
        {
            return;
        }

        $trace = explode("\n", $e->getTraceAsString());

        return implode("<br>", $trace);
    }

    public static function verificaSessao ($arquivo)
    {
        if (empty($_SESSION ['ID_USUARIO']))
        {
            session_destroy();

            if (!(\Util::endsWith($arquivo, 'index') || \Util::endsWith($arquivo, 'index.php')))
            {
                header("Location: " . ENDERECO_RAIZ);
            }
            exit ();
        }
        else
        {
            if (DEBUG)
            {
                $retorno = \UtilDAO::getResult(\Querys::SELECT_USUARIO_BY_ID, $_SESSION ['ID_USUARIO']);

                $_SESSION ['NOME_USUARIO'] = $retorno [0]->nome;
                $_SESSION ['ID_USUARIO'] = $retorno [0]->usuario_id;
                $_SESSION ['EMAIL'] = $retorno [0]->email;
                $_SESSION ['PERFIL'] = $retorno [0]->perfil;
                $_SESSION ['TULEAP_USER'] = $retorno [0]->tuleap_user;
                $_SESSION ['TULEAP_PASS'] = $retorno [0]->tuleap_pass;
            }
        }
    }

    /**
     * Error handler, passes flow over the exception logger with new ErrorException.
     */
    public static function log_error ($num, $str, $file, $line, $context = null)
    {
        self::log_exception(new \ErrorException($str, 0, $num, $file, $line));
    }

    /**
     * Uncaught exception handler.
     */
    public static function log_exception ($e, $message = null)
    {
        if (!empty($message))
        {
            $message = "<br>$message";
        }

        $trace = self::generateCallTrace($e);

        print "<div style='text-align: center;'>
    <h2 style='color: rgb(190, 50, 50);'>Ocorreu uma Exceção:</h2>
    <table style='width: 80%; display: inline-table;'>
    <tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td>" . get_class($e) . "</td></tr>
    <tr style='background-color:rgb(240,240,240);'><th>Message</th><td>{$e->getMessage()}{$message}</td></tr>
    <tr style='background-color:rgb(230,230,230);'><th>File</th><td>{$e->getFile()}</td></tr>
    <tr style='background-color:rgb(240,240,240);'><th>Line</th><td>{$e->getLine()}</td></tr>
    <tr style='background-color:rgb(230,230,230);'><th>Trace</th><td><pre>{$trace}</pre></td></tr>
    </table></div>";

        exit();
    }

    /**
     * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
     */
    public static function check_for_fatal ()
    {
        $error = error_get_last();
        if ($error["type"] == E_ERROR)
        {
            self::log_error($error["type"], $error["message"], $error["file"], $error["line"]);
        }
    }
}

/**
 * Class Cache
 * @package Portal
 */
class Cache
{
    private static $memcache;

    public static function flushMemcache ()
    {
        return self::initMemcache()->flush();
    }

    public static function getMemcache ($key)
    {
        return self::initMemcache()->get($key);
    }

    public static function setMemcache ($key, $val, $expire = 10)
    {
        return self::initMemcache()->set($key, $val, MEMCACHE_COMPRESSED, $expire);
    }

    private static function initMemcache (): \Memcache
    {
        if (empty(self::$memcache))
        {
            // abre conexao memcache
            self::$memcache = new \Memcache();
            self::$memcache->addServer('127.0.0.1');
        }

        return self::$memcache;
    }
}