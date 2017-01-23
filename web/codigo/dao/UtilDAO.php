<?php
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/Conexao.php");

class UtilDAO
{
    const REGEX_GREP_NUMERICO = "/^(\d)+(([,]|[\.])*(\d)*)*(?!.*[#$%^&*()+=\-\[\]\';,.\/{}|\":<>?~\\\\a-z])+/ADi";
    const REGEX_GREP_DATETIME = "/((\d{2})[\/])((\d{2})[\/])(\d{4})([ ]\d{2}[:]\d{2}[:]\d{2})?/AD";
    const REGEXP_REPLACE_DATETIME = "/((\d{2})[\/])((\d{2})[\/])(\d{4})([ ]\d{2}[:]\d{2}[:]\d{2})?/";
    const REGEX_GREP_DATE = "/((\d{2})[\/])((\d{2})[\/])(\d{4})/AD";
    const REGEX_REPLACE_DATE = "/((\d{2})[\/])((\d{2})[\/])(\d{4})/";

    /**
     * Utilizado para SELECT, resultados em array
     *
     * @param string  $query
     * @param unknown ...$parametros
     * @return array
     */
    public static function getResult ($query, ...$parametros)
    {
        try
        {
            $con = new Conexao ();

            $stm = $con->prepare($query);

            if ($parametros != null and count($parametros) > 0)
            {
                if ($stm->paramCount() != count($parametros))
                {
                    throw new Exception ("Query possui {$stm->paramCount()} e foram enviados " . count($parametros));
                }


                $id = 1;
                foreach ($parametros as $index => $parametro)
                {
                    if (!$stm->bindValue($id, $parametro, self::getArgType($parametro)))
                    {
                        throw new Exception ("Binding value falhou: ({$stm->errno}) {$stm->error}");
                    }

                    $id++;
                }

            }

            $retorno = self::LerRetorno($stm->execute());

            $con->close();
        } catch (Exception $e)
        {
            trataErro(SqlFormatter::format(forward_static_call_array(array(
                    'self',
                    'MontarQuery'
                ), array_merge(array(
                    $query
                ), $parametros))) . "<br>" . str_replace("\n", "<br>", $e));
        }

        return ( array )$retorno;
    }

    public static function getResultArrayParam ($query, array $parametros)
    {
        if ($parametros != null and count($parametros) > 0)
        {
            $retorno = forward_static_call_array(array(
                'self',
                'getResult'
            ), array_merge(array(
                $query
            ), $parametros));
        }
        else
        {
            $retorno = self::getResult($query);
        }

        return ( array )$retorno;
    }

    /**
     * Utilizado para INSERT, DELETE e UPDATE.
     * Com transaction.
     *
     * @param string $query
     */
    public static function executeQuery ($query)
    {
        $con = new Conexao ();

        try
        {
            self::executeStatement($con, $query);
        } catch (Exception $e)
        {
            trataErro(SqlFormatter::format($query) . "<br>" . str_replace("\n", "<br>", $e));
        }

        $con->close();
    }

    /**
     * Utilizado para INSERT, DELETE e UPDATE.
     * Com transaction unico para todas as linhas.
     *
     * @param array $query
     */
    public static function executeArrayQuery (array $query)
    {
        $con = new Conexao ();

        $qErro = '';
        try
        {
            foreach ($query as $q)
            {
                $qErro = $q;
                if ($con->query($q) == false)
                {
                    throw new Exception('Falha ao executar query');
                }
            }
        } catch (Exception $e)
        {
            trataErro(SqlFormatter::format($qErro) . "<br>" . str_replace("\n", "<br>", $e));
        }

        $con->close();
    }

    public static function executeQueryParamArray ($query, array $parametros)
    {
        return forward_static_call_array(array(
            'self',
            'executeQueryParam'
        ), array_merge(array(
            $query
        ), $parametros));
    }

    /**
     * Utilizado para INSERT, DELETE e UPDATE com parametros onde substitui na query o char '?' pelo valor do parametro.
     * Com transaction.
     *
     * @param string  $query
     * @param unknown ...$parametros
     */
    public static function executeQueryParam ($query, ...$parametros)
    {
        $con = new Conexao ();

        try
        {
            self::executeStatement($con, $query, $parametros);
        } catch (Exception $e)
        {
            trataErro(SqlFormatter::format(forward_static_call_array(array(
                    'self',
                    'MontarQuery'
                ), array_merge(array(
                    $query
                ), $parametros))) . "<br>" . str_replace(" ", "&nbsp;", print_r($parametros, true) . "<br>") . $e, $e);
        }

        $con->close();
    }

    
    
    /**
     * Trata os parametros e preenche uma query substituindo '?' pelo parametro corresponde já tratado.
     *
     * @param string $query
     * @param mixed  ...$parametro
     * @return string com a query preenchida
     */
    public static function MontarQuery ($query, ...$parametro)
    {
        foreach ($parametro as $value)
            $query = preg_replace('/[?]/', self::ConverteParaSQL($value), $query, 1);

        return ( string )$query . ';';
    }

    private static function executeStatement ($con, $query, array $parametros = [])
    {
        $stm = $con->prepare($query);

        if ($parametros != null and count($parametros) > 0)
        {
            if (is_array($parametros[0])){
                foreach ($parametros as $index => $parametro)
                {
                    if ($stm->paramCount() != count($parametro))
                    {
                        throw new Exception ("Query possui {$stm->paramCount()} e foram enviados " . count($parametro));
                    }


                    $id = 1;
                    foreach ($parametro as $coluna)
                    {
                        if (!$stm->bindValue($id, $coluna, self::getArgType($coluna)))
                        {
                            throw new Exception ("Binding value falhou: ({$stm->errno}) {$stm->error}");
                        }

                        $id++;
                    }
                    
                    $stm->execute();
                }
            } else
            {
                if ($stm->paramCount() != count($parametros))
                {
                    throw new Exception ("Query possui {$stm->paramCount()} e foram enviados " . count($parametros));
                }


                $id = 1;
                foreach ($parametros as $index => $parametro)
                {
                    if (!$stm->bindValue($id, $parametro, self::getArgType($parametro)))
                    {
                        throw new Exception ("Binding value falhou: ({$stm->errno}) {$stm->error}");
                    }

                    $id++;
                }
            }
        }

        return self::LerRetorno($stm->execute());
    }

    private static function LerRetorno ($result)
    {
        $retorno = array();

        while ($row = $result->fetchArray())
        {
            $retorno [count($retorno)] = $row;//self::charsetDefault($row);
        }

        return $retorno;
    }

    /**
     * Converte valores string para nulo, float, datetime, data ou propria string
     *
     * @param unknown $str
     * @return NULL|number|unknown
     */
    private static function ConverteParaSQL ($str)
    {
        if (!isset ($str))
        {
            return (string)"NULL";
        }

        if (gettype($str) != "string")
        {
            return (string)$str;
        }

        // Para Nulo
        if ($str == "")
        {
            return (string)"NULL";
        }

        $str = array(
            $str
        );

        // Para Numerico
        if (preg_grep(self::REGEX_GREP_NUMERICO, $str))
        {
            return (float)floatval(str_replace(',', '.', str_replace('.', '', $str [0])));
        }

        // Para Datetime
        if (preg_grep(self::REGEX_GREP_DATETIME, $str))
        {
            return (string)"'" . preg_replace(self::REGEXP_REPLACE_DATETIME, "$5-$4-$2$6", $str) [0] . "'";
        }

        // Para Data
        if (preg_grep(self::REGEX_GREP_DATE, $str))
        {
            return (string)"'" . preg_replace(self::REGEX_REPLACE_DATE, "$5-$4-$2", $str) [0] . "'";
        }

        // Para String
        return (string)"'" . $str [0] . "'";
    }

    private static function getArgType ($arg)
    {
        switch (gettype($arg))
        {
            case 'double':
                return SQLITE3_FLOAT;
            case 'integer':
                return SQLITE3_INTEGER;
            case 'boolean':
                return SQLITE3_INTEGER;
            case 'NULL':
                return SQLITE3_NULL;
            case 'string':
                return SQLITE3_TEXT;
            default:
                throw new \Exception('Argument is of invalid type ' . gettype($arg));
        }
    }

    private static function charsetDefault ($row)
    {
        static $enclist = array(
            'UTF-8',
            'ASCII',
            'ISO-8859-1',
            'ISO-8859-2',
            'ISO-8859-3',
            'ISO-8859-4',
            'ISO-8859-5',
            'ISO-8859-6',
            'ISO-8859-7',
            'ISO-8859-8',
            'ISO-8859-9',
            'ISO-8859-10',
            'ISO-8859-13',
            'ISO-8859-14',
            'ISO-8859-15',
            'ISO-8859-16',
            'Windows-1251',
            'Windows-1252',
            'Windows-1254'
        );

        if (mb_detect_encoding($row, $enclist) == 'ISO-8859-1')
        {
            return utf8_encode($row);
        }

        return $row;
    }
}