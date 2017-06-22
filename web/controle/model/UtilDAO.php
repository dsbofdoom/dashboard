<?php

class UtilDAO
{
    const REGEX_GREP_NUMERICO = "/^(\d)+(([,]|[\.])*(\d)*)*(?!.*[#$%^&*()+=\-\[\]\';,.\/{}|\":<>?~\\\\a-z\s])+/ADi";
    const REGEX_GREP_DATETIME = "/((\d{2})[\/])((\d{2})[\/])(\d{4})([ ]\d{2}[:]\d{2}[:]\d{2})?/AD";
    const REGEXP_REPLACE_DATETIME = "/((\d{2})[\/])((\d{2})[\/])(\d{4})([ ]\d{2}[:]\d{2}[:]\d{2})?/";
    const REGEX_GREP_DATE = "/((\d{2})[\/])((\d{2})[\/])(\d{4})/AD";
    const REGEX_REPLACE_DATE = "/((\d{2})[\/])((\d{2})[\/])(\d{4})/";

    /**
     * Utilizado para SELECT, resultados em array
     *
     * @param string $query
     * @param array  ...$parametros
     * @return array
     */
    public static function getResult (string $query, ...$parametros)
    {
        $retorno = [];

        try
        {
            $con = PoolConexao::getConexao();

            $retorno = $con->prepare($query, $parametros);
        } catch (Exception $e)
        {
            Portal\Gestao::trataErro(SqlFormatter::format(forward_static_call_array([
                    'self',
                    'MontarQuery'
                ], array_merge([
                    $query
                ], $parametros))) . "<br>" . str_replace("\n", "<br>", $e));
        }


        return self::LerRetorno($retorno);
    }

    public static function getResultArrayParam (string $query, array $parametros)
    {
        if ($parametros != null and count($parametros) > 0)
        {
            $retorno = forward_static_call_array([
                'self',
                'getResult'
            ], array_merge([
                $query
            ], $parametros));
        }
        else
        {
            $retorno = self::getResult($query);
        }

        return ( array ) $retorno;
    }

    /**
     * Utilizado para INSERT, DELETE e UPDATE.
     * Com transaction.
     *
     * @param string $query
     */
    public static function executeQuery (string $query)
    {
        $con = PoolConexao::getConexao();

        try
        {
            self::executeStatement($con, $query);
        } catch (Exception $e)
        {
            Portal\Gestao::trataErro(SqlFormatter::format($query) . "<br>" . str_replace("\n", "<br>", $e));
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
        $con = PoolConexao::getConexao();

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

                $con->commit();
            }
        } catch (Exception $e)
        {
            error_log($qErro);
            Portal\Gestao::trataErro($qErro . "\n" . SqlFormatter::format($qErro) . "<br>" . str_replace("\n", "<br>", $e));
        }

        $con->close();
    }

    public static function executeQueryParamArray (string $query, array $parametros)
    {
        return forward_static_call_array([
            'self',
            'executeQueryParam'
        ], array_merge([
            $query
        ], $parametros));
    }

    /**
     * Utilizado para INSERT, DELETE e UPDATE com parametros onde substitui na query o char '?' pelo valor do parametro.
     * Com transaction.
     *
     * @param string $query
     * @param        ...$parametros
     */
    public static function executeQueryParam (string $query, ...$parametros)
    {
        $con = PoolConexao::getConexao();

        try
        {
            self::executeStatement($con, $query, $parametros);
        } catch (Exception $e)
        {
            Portal\Gestao::trataErro(SqlFormatter::format(forward_static_call_array([
                    'self',
                    'MontarQuery'
                ], array_merge([
                    $query
                ], $parametros))) . "<br>" . str_replace(" ", "&nbsp;", print_r($parametros, true) . "<br>") . $e, $e);
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
    public static function MontarQuery (string $query, ...$parametro)
    {
        $query = preg_replace('/[\s]+|[\n\r]+/', ' ', $query);

        $cont = 1;
        foreach ($parametro as $value)
        {
            $query = preg_replace("/[$][{$cont}]/", self::ConverteParaSQL($value), $query);

            $cont++;
        }

        return ( string ) $query . ';';
    }

    public static function MontarQueryArray (string $query, array $parametro)
    {
        return forward_static_call_array([
            'self',
            'MontarQuery'
        ], array_merge([
            $query
        ], $parametro));
    }

    private static function executeStatement (ConexaoPG $con, $query, array $parametros = [])
    {
        $con->execute($query, $parametros);
    }

    /**
     * @param $result
     * @return array
     */
    private static function LerRetorno ($result)
    {
        $retorno = [];

        if ($result)
        {
            foreach ($result as $index => $item)
            {
                $retorno[] = (object) $item;
            }
        }

        return $retorno;
    }

    /**
     * Converte valores string para nulo, float, datetime, data ou propria string
     *
     * @param  $str
     * @return NULL|number|string
     */
    private static function ConverteParaSQL ($str)
    {
        if (!isset ($str))
        {
            return (string) "NULL";
        }

        if (gettype($str) != "string")
        {
            return (string) $str;
        }

        // Para Nulo
        if ($str == "")
        {
            return (string) "NULL";
        }

        $str = [
            $str
        ];

        // Para Numerico
        if (preg_grep(self::REGEX_GREP_NUMERICO, $str))
        {
            return (float) floatval(str_replace(',', '.', str_replace('.', '', $str [0])));
        }

        // Para Datetime
        if (preg_grep(self::REGEX_GREP_DATETIME, $str))
        {
            return (string) "'" . preg_replace(self::REGEXP_REPLACE_DATETIME, "$5-$4-$2$6", $str) [0] . "'";
        }

        // Para Data
        if (preg_grep(self::REGEX_GREP_DATE, $str))
        {
            return (string) "'" . preg_replace(self::REGEX_REPLACE_DATE, "$5-$4-$2", $str) [0] . "'";
        }

        // Para String
        return (string) "'" . $str [0] . "'";
    }

    /**
     * @param $arg
     * @return int
     * @throws Exception
     */
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
        static $enclist = [
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
        ];


        if (is_array($row))
        {
            $saida = [];
            foreach ($row as $index => $item)
            {
                if (mb_detect_encoding($item, $enclist) == 'ISO-8859-1')
                {
                    if (is_string($item))
                    {
                        $saida[$index] = utf8_encode($item);
                    }
                    else
                    {
                        $saida[$index] = $item;
                    }
                }
            }

            return $saida;
        }
        else
        {
            return utf8_encode($row);
        }
    }
}