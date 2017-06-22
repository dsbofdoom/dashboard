<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 4/11/2017
 * Time: 4:34 PM
 */
class PoolConexao
{
    private static $con;

    /**
     * @return ConexaoPG
     */
    public static function getConexao ()
    {
        if (isset(self::$con) || empty(self::$con))
        {
            self::$con = new ConexaoPG();
        }

        if (pg_connection_status(self::$con) != PGSQL_CONNECTION_OK)
        {
            self::$con = new ConexaoPG();
        }

        return self::$con;
    }
}