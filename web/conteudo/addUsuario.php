<?php
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/template/pagina.php");

$tpl->addFile ( "CONTEUDO_CENTRAL", $_SERVER ['DOCUMENT_ROOT'] . "/conteudo/addUsuario.html" );

$tpl->NOME_PAGINA = "Cadastrar Usuário";
$tpl->DESCRICAO_PAGINA = "";
$tpl->CHAMADA_AJAX = CHAMADA_AJAX;
$tpl->PAGINA_RETORNO = PAGINA_PRINCIPAL;

// se tiver id no get
if (! empty ( $_GET ["id"] )) {
	$tpl->NOME_PAGINA = "Modificar Usuário";

	$tpl->ID_JSON = 46832;

	// preencher os campos de contrato
	foreach ( UtilDAO::getResult ( Querys::SELECT_USUARIO_BY_ID, $_GET ["id"] ) as $row ) {
		// script para preenchimento do valor de combos
		$tpl->SCRIPT_PERFIL = Util::SendReadyScript ( '$("#perfil").val("' . $row ["perfil"] . '");' );

		// preenche valor dos campos text
		$tpl->VALUE_ID_USUARIO = $row ["id_usuario"];
		$tpl->VALUE_NOME = $row ["nome"];
		$tpl->VALUE_EMAIL = $row ["usuario"];

		$tpl->VALUE_ATIVO_EXTENSO = ($row ["ativo"] == "S" ? "ATIVADO" : "DESATIVADO");
		$tpl->BTN_ATIVO = ($row ["ativo"] == "S" ? "Desativar" : "Ativar");
		$tpl->CLASS_ATIVO = ($row ["ativo"] == "S" ? "btn-danger" : "btn-success");
		$tpl->VALUE_ATIVO = $row ["ativo"];
		$tpl->block ( "BLOCK_ATIVO" );
		$tpl->block ( "BLOCK_PERFIL" );
	}
} else if (isset ( $_GET ["corrente"] )) {
	$tpl->ID_JSON = 287354;

	$tpl->NOME_PAGINA = "Modificar Usuário";

	$tpl->VALUE_NOME = $_SESSION ["NOME_USUARIO"];
	$tpl->VALUE_ID_USUARIO = $_SESSION ["ID_USUARIO"];
	$tpl->VALUE_EMAIL = $_SESSION ["USUARIO"];

	$tpl->block ( "BLOCK_USUARIO_CORRENTE" );
} else {
	$tpl->ID_JSON = 9874312;
	$tpl->block ( "BLOCK_PERFIL" );
}

$tpl->show ();