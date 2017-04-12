<?php

/**
 * Created by PhpStorm.
 * User: saulocorreia
 * Date: 3/2/2017
 * Time: 12:02 PM
 */
class ConfiguracaoDAO
{
    public static function salvarConfiguracao ()
    {
        try
        {
            UtilDAO::executeQueryParam(Querys::INSERT_REPLACE_CONFIGURACAO, $_POST ['group_id'],$_POST ['unix_name']);

            Ajax::RespostaSucesso('Configuração salva com sucesso.', false, Ajax::TIPO_SUCCESS);
        } catch (Exception $e)
        {
            Ajax::RespostaErro('Falha ao salvar configuração.', $e);
        }
    }
}