<?php

class Util
{
    /**
     * @param       $var
     * @param int   $nivel
     * @param bool  $full
     * @param array $desabilita
     * @return string
     */
    public static function printInTree ($var, $nivel = 0, $full = false, $desabilita = [])
    {
        $retorno = '';

        $espaco = '';
        $espacoMeio = '&#x251C;&#x2500;&#x2500; ';
        $espacoFim = '&#x2514;&#x2500;&#x2500; ';
        for ($i = 0; $i < $nivel - 1; $i++)
        {
            if (in_array($i, $desabilita))
            {
                $espaco .= '    ';
            }

            else
            {
                $espaco .= '&#x2502;   ';
            }
        }

        if (is_array($var))
        {
            $pos = 0;
            $count = count($var);
            foreach ($var as $key => $value)
            {
                $pos++;

                if ($count == $pos)
                {
                    $desabilita[] = $nivel - 1;
                }

                $retorno .= "<br>" . $espaco . ($nivel > 0 ? ($count == $pos ? $espacoFim : $espacoMeio) : '') . $key . ' ';
                $retorno .= self::printInTree($var[$key], $nivel + 1, $full, $desabilita);
            };

        }
        else
        {
            if (is_object($var))
            {
                $pos = 0;
                $count = count(get_object_vars($var));
                foreach (get_object_vars($var) as $key => $value)
                {
                    $pos++;
                    if (self::startsWith($key, 'field_') and $full)
                    {
                        if ($key == 'field_name')
                        {
                            continue;
                        }
                        else
                        {
                            if ($key == 'field_label')
                            {
                                $retorno .= $value;
                                continue;
                            }
                            else
                            {
                                if ($key == 'field_value' AND isset($value->value))
                                {
                                    $retorno .= ' => ' . $value->value;
                                    continue;
                                }
                            }
                        }
                    }

                    if ($count == $pos)
                    {
                        $desabilita[] = $nivel - 1;
                    }

                    $retorno .= "<br>" . $espaco . ($nivel > 0 ? ($count == $pos ? $espacoFim : $espacoMeio) : '') . $key;
                    $retorno .= self::printInTree($var->{$key}, $nivel + 1, $full, $desabilita);
                };
            }
            else
            {
                if (is_integer($var) and strlen($var) == 10)
                {
                    $var = self::trataData($var);
                }

                $retorno .= ' => (' . gettype($var) . ') ' . $var;
            }
        }

        return $retorno;
    }

    public static function DataSQLtoString ($data)
    {
        return isset ($data) ? date("d/m/Y", strtotime($data)) : "";
    }

    public static function SendReadyScript ($script)
    {
        return "<script>$().ready(function(){" . $script . "});</script>";
    }

    public static function SendScript ($script)
    {
        return "<script>" . $script . "</script>";
    }

    public static function inverterOrdem ($ordem)
    {
        if (strcasecmp($ordem, "DESC") == 0)
        {
            return "ASC";
        }
        return "DESC";
    }

    public static function numberToMoney ($number, $cifrao = false)
    {
        return ($cifrao ? "R$ " : "") . number_format($number, 2, ',', '.');
    }

    public static function numberToMoneyCor ($number, $cifrao = false)
    {
        return ($number < 0 ? "<font color='red'>" : "") . ($cifrao ? "R$ " : "") . number_format($number, 2, ',', '.') . ($number < 0 ? "</font>" : "");
    }

    public static function number ($number)
    {
        return number_format($number, 2, ',', '.');
    }

    public static function round ($number, $decimal)
    {
        return number_format($number, $decimal, ',', '.');
    }

    public static function isDiferente ($cmp1, $cmp2, $retorno)
    {
        if ($cmp1 != $cmp2)
        {
            return $retorno;
        }

        return null;
    }

    public static function startsWith ($fullText, $starts)
    {
        return (substr($fullText, 0, strlen($starts)) === $starts);
    }

    public static function endsWith ($fullText, $ends)
    {
        $length = strlen($ends);
        if ($length == 0)
        {
            return true;
        }

        return (substr($fullText, -$length) === $ends);
    }

    public static function PreencherFiltro (&$tpl, $campo, $coluna, $block, $query)
    {
        foreach (UtilDAO::getResult($query) as $row)
        {
            $tpl->{$campo} = $row [$coluna];
            $tpl->block($block);
        }
    }


    /**
     * @param $time
     * @return false|string
     */
    public static function trataData ($time)
    {
        if (empty($time))
        {
            return '';
        }
        
        date_default_timezone_set('America/Sao_Paulo');

        return date("y-m-d H:i:s", $time);
    }
}