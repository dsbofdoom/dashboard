<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 4/11/2017
 * Time: 4:34 PM
 */
class PoolConexao
{
    private static $Conexao;

    function __construct ()
    {
        if (isset(self::$Conexao) || empty(self::$Conexao))
        {
            self::$Conexao = new Conexao();
        }
    }

    /**
     * @return Conexao
     */
    public static function getConexao (): Conexao
    {
        return self::$Conexao;
    }
}