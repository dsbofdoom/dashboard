<?php
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/portal/ConstantesPortal.php");
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/controle/Ajax.php");
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/UsuarioDAO.php");

$Comando = $_POST ["comando"];
switch ($Comando) {
	// Efetuar Login - index.php
	case "3894" :
		{
			UsuarioDAO::login ();
			break;
		}

	// Salvar perfil - perfil.php
	case "287354" :
		{
			UsuarioDAO::salvarPerfil ();
			break;
		}

	// Cadastrar Usuario - addUsuario.php
	case "9874312" :
		{
			UsuarioDAO::cadastrarUsuario ();
			break;
		}

	// Modificar Usuario - addUsuario.php
	case "46832" :
		{
			UsuarioDAO::modificarUsuario ();
			break;
		}

	// Modificar Usuario - addUsuario.php
	case "49023" :
		{
			UsuarioDAO::ResetSenha ();
			break;
		}
	// Desativar Usuario - addUsuario.php
	case "948723" :
		{
			UsuarioDAO::desativarUsuario ();
			break;
		}

	// Rubrica - adefault.php
	case "8762" :
		{
			ProgamaDAO::insertPrograma ();
			break;
		}

	// Comando nao reconhecido
	default :
		{
			Ajax::RespostaErro ( "Comando não reconhecido" );
			break;
		}
}
