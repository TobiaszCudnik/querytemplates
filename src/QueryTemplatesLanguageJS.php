<?php
require_once(dirname(__FILE__)."/QueryTemplatesLanguage.php");
/**
 * 
 * @TODO error safe prints
 * @TODO error safe iterations
 */
abstract class QueryTemplatesLanguageJS
	extends QueryTemplatesLanguage {
	public static function printVar($varName) {
		$callbacks = self::callbacks(self::filterVarNameCallbacks($varName));
		$varName = self::varName($varName);
		return <<<EOF
	print({$callbacks[0]}$varName{$callbacks[1]});
EOF;
	}
	public static function printValue($value) {
		$value = self::addslashes($value);
		return "print '$value';";
	}
	public static function loopVar($varName, $asVarName, $keyName) {
		if (! $keyName)
			$keyName = '__key'.substr(md5(microtime()), 0, 5);
		$varName = self::varName($varName);
		return array(
			"	for ($keyName in $varName) {
		var $asVarName = {$varName}[$keyName]; 
		if ($asVarName instanceof Function)
			continue;",
			"	}"
		);
	}
	public static function compareVarValue($varName, $value, $strict = false) {
		if (is_bool($value)) {
			$value = $value
				 ? 'true' : 'false';
		} else {
			$value = "'".self::addslashes($value)."'";
		}
		$varName = self::varName($varName);
		$strict = $strict
			? '=' : '';
		return <<<EOF
{$varName} ==$strict $value
EOF;
	}
	public static function ifCode($code) {
		return array(
			"	if ($code) {",
			"	}"
		);
	}
	public static function elseIfCode($code) {
		return array(
			"	if ($code) {",
			"	}"
		);
	}
	public static function ifVar($var) {
		$var = self::varName($var);
		return array(
			"	if (typeof $var != undefined && $var) {",
			"	}"
		);
	}
	public static function ifNotVar($var) {
		$var = self::varName($var);
		return array(
			"	if (typeof $var != undefined && ! $var) {",
			"	}"
		);
	}
	public static function elseIfVar($var) {
		$var = self::varName($var);
		return array(
			"	else if (typeof $var != undefined && $var) {",
			"	}"
		);
	}
	public static function elseIfNotVar($var) {
		$var = self::varName($var);
		return array(
			"	else if (typeof $var != undefined && ! $var) {",
			"	}"
    );
	}
	public static function elseStatement() {
		return array(
			"	else {",
			"	}"
		);
	}
	public static function varName($name) {
		$return = '';
		foreach(explode('.', $name) as $v) {
			if (! $return)
				$return = "$v";
			else {
				$v = self::addslashes($v);
				$return .= "['$v']";
			}
		}
		return $return;
	}
	public static function initialize() {
		phpQuery::plugin('jsCode');
	}
	public static function valuesToVars($varsArray) {
		$lines = array();
		foreach($varsArray as $var => $value) {
			$var = self::varName($var);
			$value = is_array($value) || is_object($value)
				? QueryTemplates::toJSON($value)
				: "'".phpQuery::$plugins->jsVarValue($value)."'"; 
			$lines[] = "var $var = $value;";
		}
		return implode("\n", $lines);
	}
	/** 
	 * @param $content
	 * @param $name
	 * @param $vars
	 * @param $saveParams
	 * @return unknown_type
	 */
	public static function templateWrapper($content, $name, $vars, $saveParams) {
		$varsCode = '';
		if ($vars) {
			$varsCode .= "\n";
			// TODO use self::valuesToVars
			foreach($vars as $varName => $varValue) {
				$varName = self::varName($varName);
				$varValue = is_array($varValue) || is_object($varValue)
					? QueryTemplates::toJSON($varValue)
					: "'".phpQuery::$plugins->jsVarValue($varValue)."'"; 
				$varsCode .= "\tvar $varName = $varValue;\n";
			}
		}
		$callbackActive = isset($saveParams[1]) && $saveParams[1]
			? '()' : '';
		$callback = isset($saveParams[0]) && $saveParams[0]
			? "\n{$saveParams[0]}(template$name$callbackActive);"
			: '';
		return <<<EOF
function template$name(__data) {{$varsCode}
	if (typeof __data != 'undefined')
		for (__dataRow in __data)
			eval('var '+__dataRow+' = __data[__dataRow]');
	var __template = '';
	var print = function(value) {
		__template += value;
	}
$content
	return __template;
}$callback
EOF;
	}
	public static function postFilterMarkup($markup, $isDocumentFragment) {
		return phpQuery::$plugins->markupToJS($markup);
	}
}
