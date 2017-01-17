<?php
if (! isset ( $_SESSION ['ID_USUARIO'] )) {
	session_destroy ();

	header ( "Location: " . DIRETORIO_RAIZ );
	exit ();
} else if (DEBUG == "true") {
	$retorno = UtilDAO::getResult ( Querys::SELECT_USUARIO_BY_ID, $_SESSION ['ID_USUARIO'] );

	$_SESSION ['NOME_USUARIO'] = $retorno [0] ['nome'];
	$_SESSION ['ID_USUARIO'] = $retorno [0] ['id_usuario'];
	$_SESSION ['USUARIO'] = $retorno [0] ['usuario'];
	$_SESSION ['PERFIL'] = $retorno [0] ['perfil'];
}
?>