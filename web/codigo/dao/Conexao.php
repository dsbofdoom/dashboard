<?php

class Conexao extends SQLite3
{
    public function __construct ()
    {
        try
        {
            parent::open(BANCO_URL);

            if (DEBUG)
            {
                parent::exec(Querys::CREATE_TABLE);
            }
        } catch (Exception $e)
        {
            trataErro("Falha ao abrir conexão", $e);
        }
    }

    public function close ()
    {
        //return parent::close();
    }
}
