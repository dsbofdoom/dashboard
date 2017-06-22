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
            $result = UtilDAO::getResult(Querys::SELECT_CONFIGURACAO_BY_GROUP_ID, $_POST ['group_id']);

            \Portal\Gestao::flushMemcache();

            if (count($result) > 0)
            {
                UtilDAO::executeQueryParam(Querys::UPDATE_CONFIGURACAO,$_POST ['unix_name'], $_POST ['diretorio'], $_POST['caminho_mer'], $_POST ['group_id']);
            }
            else
            {
                UtilDAO::executeQueryParam(Querys::INSERT_CONFIGURACAO, $_POST ['group_id'], $_POST ['unix_name'], $_POST ['diretorio'], $_POST['caminho_mer']);
            }

            Portal\Ajax::RespostaSucesso('Configuração salva com sucesso.', false, Portal\Ajax::TIPO_SUCCESS);
        } catch (Exception $e)
        {
            Portal\Ajax::RespostaErro('Falha ao salvar configuração.', $e);
        }
    }
}