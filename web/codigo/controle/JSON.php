<?php
require_once($_SERVER ['DOCUMENT_ROOT'] . '/dashboard/codigo/portal/ConstantesPortal.php');

$Comando = $_POST ['comando'];
switch ($Comando)
{
    // Efetuar Login - index.php
    case '3894' :
    {
        UsuarioDAO::login();
        break;
    }

    // Salvar perfil - perfil.php
    case '287354' :
    {
        UsuarioDAO::salvarPerfil();
        break;
    }

    // Cadastrar Usuario - addUsuario.php
    case '9874312' :
    {
        UsuarioDAO::cadastrarUsuario();
        break;
    }

    // Modificar Usuario - addUsuario.php
    case '46832' :
    {
        UsuarioDAO::modificarUsuario();
        break;
    }

    // Modificar Usuario - addUsuario.php
    case '49023' :
    {
        UsuarioDAO::ResetSenha();
        break;
    }
    // Desativar Usuario - addUsuario.php
    case '948723' :
    {
        UsuarioDAO::desativarUsuario();
        break;
    }

    // Mudar COnfiguracao
    case '8762' :
    {
        ConfiguracaoDAO::salvarConfiguracao();
        break;
    }

    // Comando nao reconhecido
    default :
    {
        Ajax::RespostaErro('Comando não reconhecido');
        break;
    }
}
