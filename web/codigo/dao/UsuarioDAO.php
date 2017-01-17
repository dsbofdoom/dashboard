<?php
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/Querys.php");
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/UtilDAO.php");
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/controle/Ajax.php");
require_once($_SERVER ['DOCUMENT_ROOT'] . "/codigo/util/Mail.php");

class UsuarioDAO
{
    const SENHA_PADRAO = "123456";

    public static function cadastrarUsuario ()
    {
        try
        {
            UtilDAO::executeQueryParam(Querys::INSERT_USUARIO, $_POST ["nome"], $_POST ["email"], md5(self::SENHA_PADRAO), $_POST ["perfil"]);

            Ajax::RespostaSucesso("Usuário cadastrado com sucesso.", true, Ajax::TIPO_SUCCESS);
        } catch (Exception $e)
        {
            Ajax::RespostaErro("Falha ao salvar perfil.", $e);
        }
    }

    public static function modificarUsuario ()
    {
        try
        {
            UtilDAO::executeQueryParam(Querys::UPDATE_USUARIO_PERFIL, $_POST ["nome"], $_POST ["email"], $_POST ["perfil"], $_POST ["id_usuario"]);

            Ajax::RespostaSucesso("Usuário modificado com sucesso.", true, Ajax::TIPO_SUCCESS);
        } catch (Exception $e)
        {
            Ajax::RespostaErro("Falha ao salvar perfil.", $e);
        }
    }

    public static function desativarUsuario ()
    {
        try
        {
            UtilDAO::executeQueryParam(Querys::UPDATE_USUARIO_ATIVO, ($_POST["ativo"] == "S" ? "N" : "S"), $_POST ["id_usuario"]);

            Ajax::RespostaSucesso("Usuário modificado com sucesso.", true, Ajax::TIPO_SUCCESS);
        } catch (Exception $e)
        {
            Ajax::RespostaErro("Falha ao salvar perfil.", $e);
        }

    }

    public static function login ()
    {
        try
        {
            $retorno = UtilDAO::getResult(Querys::SELECT_LOGIN, $_POST ["login"], md5($_POST ["senha"]));
            if (count($retorno) == 0)
            {
                Ajax::RespostaErro("Usuário e/ou Senha incorretos.");
            }
            else
            {
                $_SESSION ['NOME_USUARIO'] = $retorno [0] ['nome'];
                $_SESSION ['ID_USUARIO'] = $retorno [0] ['id_usuario'];
                $_SESSION ['USUARIO'] = $retorno [0] ['usuario'];
                $_SESSION ['PERFIL'] = $retorno [0] ['perfil'];

                Ajax::RespostaSucesso("", true, Ajax::TIPO_SUCCESS);
            }
        } catch (Exception $e)
        {
            Ajax::RespostaErro("Falha ao logar.", $e);
        }
    }

    public static function ResetSenha ()
    {
        try
        {
            UtilDAO::executeQueryParam(Querys::UPDATE_USUARIO_RESET_SENHA, md5(self::SENHA_PADRAO), $_POST ["id_usuario"]);

            Ajax::RespostaSucesso("Resetado senha com sucesso.", true, Ajax::TIPO_SUCCESS);
        } catch (Exception $e)
        {
            Ajax::RespostaErro("Falha ao resetar senha.", $e);
        }
    }

    public static function salvarPerfil ()
    {
        try
        {
            // Se for trocar senha
            if (isset ($_POST ["senha_atual"]) && !empty ($_POST ["senha_atual"]))
            {
                $retorno = UtilDAO::getResult(Querys::SELECT_LOGIN, $_POST ["email"], md5($_POST ["senha_atual"]));
                if (count($retorno) == 0)
                {
                    Ajax::RespostaErro("Senha incorreta.");
                }

                UtilDAO::executeQueryParam(Querys::UPDATE_USUARIO_SENHA, $_POST ["nome"], $_POST ["email"], md5($_POST ["nova_senha"]), $_POST ["id_usuario"]);
            }
            else
            {
                UtilDAO::executeQueryParam(Querys::UPDATE_USUARIO, $_POST ["nome"], $_POST ["email"], $_POST ["id_usuario"]);
            }

            $_SESSION ['NOME_USUARIO'] = $_POST ["nome"];
            $_SESSION ['USUARIO'] = $_POST ["email"];

            Ajax::RespostaSucesso("Usuário modificado com sucesso.", true, Ajax::TIPO_SUCCESS);
        } catch (Exception $e)
        {
            Ajax::RespostaErro("Falha ao salvar perfil.", $e);
        }
    }
}