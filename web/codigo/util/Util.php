<?php
class Util {

	public static function DataSQLtoString($data) {
		return isset ( $data ) ? date ( "d/m/Y", strtotime ( $data ) ) : "";
	}

	public static function SendReadyScript($script) {
		return "<script>$().ready(function(){" . $script . "});</script>";
	}

	public static function SendScript($script) {
		return "<script>" . $script . "</script>";
	}

	public static function inverterOrdem($ordem) {
		if (strcasecmp ( $ordem, "DESC" ) == 0)
			return "ASC";
		return "DESC";
	}

	public static function numberToMoney($number, $cifrao = false) {
		return ($cifrao ? "R$ " : "") . number_format ( $number, 2, ',', '.' );
	}

	public static function numberToMoneyCor($number, $cifrao = false) {
		return ($number < 0 ? "<font color='red'>" : "") . ($cifrao ? "R$ " : "") . number_format ( $number, 2, ',', '.' ) . ($number < 0 ? "</font>" : "");
	}

	public static function number($number) {
		return number_format ( $number, 2, ',', '.' );
	}

	public static function round($number, $decimal) {
		return number_format ( $number, $decimal, ',', '.' );
	}

	public static function isDiferente($cmp1, $cmp2, $retorno) {
		if ($cmp1 != $cmp2)
			return $retorno;

		return null;
	}

	public static function startsWith($fullText, $starts) {
		return (substr ( $fullText, 0, strlen ( $starts ) ) === $starts);
	}

	public static function endsWith($fullText, $ends) {
		$length = strlen ( $ends );
		if ($length == 0)
			return true;

		return (substr ( $fullText, - $length ) === $ends);
	}

	public static function PreencherFiltro(&$tpl, $campo, $coluna, $block, $query) {
		foreach ( UtilDAO::getResult ( $query ) as $row ) {
			$tpl->{$campo} = $row [$coluna];
			$tpl->block ( $block );
		}
	}
}