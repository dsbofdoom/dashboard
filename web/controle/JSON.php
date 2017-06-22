<?php
switch ($_REQUEST ['comando'])
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

    // Desativar Usuario - addUsuario.php
    case '948723' :
    {
        UsuarioDAO::desativarUsuario();
        break;
    }

    // Mudar Configuracao
    case '8762' :
    {
        ConfiguracaoDAO::salvarConfiguracao();
        break;
    }

    case '45678' :
    {
        ComentarioTabelasDAO::buscarTabelas();
        break;
    }

    case '4523' :
    {
        ComentarioTabelasDAO::salvarTabela();
        break;
    }
    case '43781264' :
    {
        Portal\Ajax::RespostaGenerica("", "", false, [
            'data' => UtilDAO::getResult(Querys::SELECT_GANTT)
        ]);
        break;
    }
    case '1089148' :
    {
        Portal\Ajax::RespostaGenerica("", "", false, [
            'data' => UtilDAO::getResult(Querys::SELECT_GANTT_BY_GROUP_ID, $_REQUEST['group_id'])
        ]);
        break;
    }
    // Comando nao reconhecido
    default :
    {
        Portal\Ajax::RespostaErro('Comando não reconhecido');
        break;
    }
}
