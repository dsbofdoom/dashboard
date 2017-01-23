<?php
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/Querys.php");
require_once ($_SERVER ['DOCUMENT_ROOT'] . "/codigo/dao/UtilDAO.php");
class ResponsavelDAO {
	// Responsavel
	const OBRA_P1 = "Obra P1";
	const PROJETO_P2 = "Projeto P2";

	// Engenheiro
	const DNIT_TITULAR = "DNIT Titular";
	const DNIT_SUBSTITUTO = "DNIT Substituto";

	/**
	 * Monta as querys de responsavel e adiciona na variavel $querys por referencia
	 *
	 * @param unknown $querys
	 */
	public static function MontaQueryResponsavel(&$querys, $id_contrato) {
		for($i = 0; $i < count ( $_POST ["responsavel_p1"] ); $i ++)
			if (! empty ( $_POST ["responsavel_p1"] [$i] ))
				$querys [count ( $querys )] = UtilDAO::MontarQuery ( Querys::INSERT_RESPONSAVEL, $id_contrato, $_POST ["responsavel_p1"] [$i], self::OBRA_P1, $_SESSION ["ID_USUARIO"] );

		for($i = 0; $i < count ( $_POST ["responsavel_p2"] ); $i ++)
			if (! empty ( $_POST ["responsavel_p2"] [$i] ))
				$querys [count ( $querys )] = UtilDAO::MontarQuery ( Querys::INSERT_RESPONSAVEL, $id_contrato, $_POST ["responsavel_p2"] [$i], self::PROJETO_P2, $_SESSION ["ID_USUARIO"] );
	}

	/**
	 * Monta as querys de engenheiro e adiciona na variavel $querys por referencia
	 *
	 * @param unknown $querys
	 */
	public static function MontaQueryEngenharia(&$querys, $id_contrato) {
		for($i = 0; $i < count ( $_POST ["engenheiro_dnit_titular"] ); $i ++)
			if (! empty ( $_POST ["engenheiro_dnit_titular"] [$i] ))
				$querys [count ( $querys )] = UtilDAO::MontarQuery ( Querys::INSERT_RESPONSAVEL, $id_contrato, $_POST ["engenheiro_dnit_titular"] [$i], self::DNIT_TITULAR, $_SESSION ["ID_USUARIO"] );

		for($i = 0; $i < count ( $_POST ["engenheiro_dnit_substituto"] ); $i ++)
			if (! empty ( $_POST ["engenheiro_dnit_substituto"] [$i] ))
				$querys [count ( $querys )] = UtilDAO::MontarQuery ( Querys::INSERT_RESPONSAVEL, $id_contrato, $_POST ["engenheiro_dnit_substituto"] [$i], self::DNIT_SUBSTITUTO, $_SESSION ["ID_USUARIO"] );
	}

	public static function selectObraP1ByIdContrato($idContrato) {
		return self::selectTipoByIdContrato ( $idContrato, self::OBRA_P1 );
	}

	public static function selectProjetoP2ByIdContrato($idContrato) {
		return self::selectTipoByIdContrato ( $idContrato, self::PROJETO_P2 );
	}

	public static function selectDnitTitularByIdContrato($idContrato) {
		return self::selectTipoByIdContrato ( $idContrato, self::DNIT_TITULAR );
	}

	public static function selectDnitSubstitutoByIdContrato($idContrato) {
		return self::selectTipoByIdContrato ( $idContrato, self::DNIT_SUBSTITUTO );
	}

	private static function selectTipoByIdContrato($idContrato, $tipo) {
		UtilDAO::getResult ( Querys::SELECT_RESPONSAVEL_BY_ID_TIPO, $idContrato, $tipo );
	}
}
