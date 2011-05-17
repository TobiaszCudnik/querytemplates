<?php
abstract class QueryTemplatesLanguage {
	public static function addslashes($target, $char = "'") {
		return str_replace($char, '\\'.$char, $target);
	}
	public static function initialize() {
	}
//	abstract public static function templateWrapper($content, $name, $vars, $saveParams);
	public static function postFilterDom($dom) {
		return $dom;
	}
	public static function filterVarNameCallbacks(&$varName) {
		$var = null;
		$callbacks = array();
		foreach(explode('|', $varName) as $r) {
			if (! $var) {
				$var = $r;
				continue;
			}
			$callbacks[] = $r;
		}
		$varName = $var;
		return $callbacks;
	}
	public static function callbacks($callbacks) {
		return $callbacks
			? array(
				implode('(', $callbacks).'(',
				str_repeat(')', count($callbacks))
			) : array('', ''); 
	}
	/**
	 * Runs method $method from specific language class $lang.
	 * Parameters 3rd and after are forwarded to that method.
	 * 
	 * @param $lang
	 * @param $method
	 * @return unknown_type
	 */
	public static function get($lang, $method) {
		$langClass = "QueryTemplatesLanguage".strtoupper($lang);
		if (! class_exists($langClass))
			require_once(dirname(__FILE__)."/$langClass.php");
		$params = func_get_args();
		return call_user_func_array(
			array($langClass, $method), array_slice($params, 2)
		);
	}
}