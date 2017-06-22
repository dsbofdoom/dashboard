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

                $retorno .= '<br>' . $espaco . ($nivel > 0 ? ($count == $pos ? $espacoFim : $espacoMeio) : '') . $key . ' ';
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
        return ($cifrao ? "R$ " : "") . self::number($number);
    }

    public static function numberToMoneyCor ($number, $cifrao = false)
    {
        return ($number < 0 ? "<span style='color: red'>" : "") . ($cifrao ? "R$ " : "") . self::number($number) . ($number < 0 ? "</span>" : "");
    }

    public static function number ($number)
    {
        return self::round($number, 2);
    }

    public static function round ($number, $decimal)
    {
        return number_format($number, $decimal, ',', '.');
    }

    public static function isDiferente ($cmp1, $cmp2, $retorno = true)
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

        date_default_timezone_set('America/Sao_Paulo');//'UTC');

        return date("Y-m-d H:i:s", $time);
    }

    public static function criaPasta ($pasta)
    {
        if (!is_dir($pasta))
        {
            return mkdir($pasta, 0777, true);
        }

        return false;
    }

    public static function zipFile ($source, $destination, $remove = '')
    {
        if (!extension_loaded('zip') || !file_exists($source))
        {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE))
        {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, '/') + 1), ['.', '..']) || "{$source}{$remove}" == $file)
                {
                    continue;
                }

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                elseif (is_file($file) === true)
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents(realpath($file)));
                }
            }
        }
        elseif (is_file($source) === true)
        {
            $zip->addFromString(basename($source), file_get_contents(realpath($source)));
        }

        return $zip->close();
    }


    public static function removeDir ($dir)
    {
        foreach (glob($dir . '/*') as $file)
        {
            if (is_dir($file))
            {
                self::removeDir($file);
            }
            else
            {
                unlink($file);
            }
        }
        rmdir($dir);
    }

}