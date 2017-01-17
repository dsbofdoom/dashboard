<?php
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/bean/Contrato.php");
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/Querys.php");
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/UtilDAO.php");
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/ResponsavelDAO.php");
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/controle/Ajax.php");
class ProgamaDAO {

	public static function insertPrograma() {
		try {
			// inserindo programa
			UtilDAO::executeQueryParam ( Querys::DELETE_PROGRAMA, $_POST ["contrato"] );
			if (! empty ( $_POST ["programa"] ))
				UtilDAO::executeQueryParam ( Querys::INSERT_PROGRAMA, $_POST ["contrato"], $_POST ["programa"] );

			Ajax::RespostaSucesso ( "Salvo com sucesso.", true, Ajax::TIPO_SUCCESS );
		} catch ( Exception $e ) {
			if (stripos ( $e->getMessage (), "Cannot insert duplicate key" ) < 0)
				Ajax::RespostaErro ( "Falha ao salvar contrato.", $e );
			else
				Ajax::RespostaErro ( "Este contrato já se encontra cadastrado.", $e );
		}
	}
}