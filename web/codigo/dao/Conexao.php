<?php
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/portal/ConstantesPortal.php");
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/util/SqlFormatter.php");
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/util/Util.php");

class Conexao extends SQLite3
{
    private $Conexao;

    public function __construct ()
    {
        try
        {
            parent::open(BANCO_URL);

            if (DEBUG == 'true')
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
        return parent::close();
    }
}
