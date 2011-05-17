<?php
/**
 * @package QueryTemplates
 * @subpackage QueryTemplatesPlugins
 */
abstract class phpQueryObjectPlugin_jsCode {
	public static function ifJS($self, $code, $separate = false) {
		return $self->_if('js', $code, $separate);
	}
	public static function elseJS($self, $separate = false) {
		return $self->elseStatement($separate, 'js');
	}
	public static function attrJS($self, $attr, $code) {
		if (! is_null($code)) {
			$code = '<'.'?js '.$code.' ?'.'>';
		}
		foreach($self->stack(1) as $node) {
			if (! is_null($code)) {
				$node->setAttribute($attr, $code);
			}
		}
		return $self;
	}
	public static function addClassJS($self, $code) {
		foreach($self->stack(1) as $node) {
				$classes = $node->getAttribute('class');
				$newValue = $classes
					? $classes.' <'.'?js '.$className.' ?'.'>'
					: '<'.'?js '.$className.' ?'.'>';
				$node->setAttribute('class', $newValue);
		}
		return $self;
	}
	public static function beforeJS($self, $code) {
		return $self->insert(phpQuery::code('js', $code), 'before');
	}
	public static function afterJS($self, $code) {
		return $self->insert(phpQuery::code('js', $code), 'after');
	} 
	public static function prependJS($self, $code) {
		return $self->insert(phpQuery::code('js', $code), 'prepend');
	} 
	public static function appendJS($self, $code) {
		return $self->insert(phpQuery::code('js', $code), 'append');
	} 
	public static function js($self, $code = nul) {
		return $self->markupJS($code);
	}
	public static function markupJS($self, $code = null) {
		return isset($code)
			? $self->markup(phpQuery::code('js', $code))
			: phpQuery::markupToJS($self->markup());
	}
	public static function markupOuterJS($self) {
		return phpQuery::markupToJS($self->markupOuter());
	}
	public static function wrapAllJS($self, $codeBefore, $codeAfter) {
		return $self
			->slice(0, 1)
				->beforeJS($codeBefore)
			->end()
			->slice(-1)
				->afterJS($codeAfter)
			->end();
	} 
	public static function wrapJS($self, $codeBefore, $codeAfter) {
		foreach($self->stack() as $node)
			phpQuery::pq($node, $self->getDocumentID())->wrapAllJS($codeBefore, $codeAfter);
		return $self;
	}
	public static function wrapInnerJS($self, $codeBefore, $codeAfter) {
		// TODO test this
		foreach($self->stack(1) as $node)
			phpQuery::pq($node, $self->getDocumentID())->contents()
				->wrapAllJS($codeBefore, $codeBefore);
	}
	public static function replaceWithJS($self, $code) {
		return $self->replaceWith(phpQuery::code('js', $code));
	}
}
abstract class phpQueryPlugin_jsCode {
	public static function markupToJS($markup) {
		$split = preg_split(
			'@(<js>\s*<!--.*?-->\s*</js>)@s',
			$markup,
			null,
			PREG_SPLIT_DELIM_CAPTURE
		);
		$template = '';
		$attrRegexes = array(
			'@(<(?!\\?)(?:[^>]|\\?>)+\\w+\\s*=\\s*)(\')([^\']*)(?:&lt;|%3C)\\?(?:js)?(.*?)(?:\\?(?:&gt;|%3E))([^\']*)\'@s',
			'@(<(?!\\?)(?:[^>]|\\?>)+\\w+\\s*=\\s*)(")([^"]*)(?:&lt;|%3C)\\?(?:js)?(.*?)(?:\\?(?:&gt;|%3E))([^"]*)"@s',
		);
		foreach($split as $chunk) {
			if (substr($chunk, 0, 4) == '<js>') {
				$chunk = substr($chunk, 8, -8);
				$template .= htmlspecialchars_decode($chunk);
				if (strtolower(substr(trim($chunk), -1)) != '}') {
					$template .= ";";
				}
				$template .= "\n";
			} else {
				foreach($attrRegexes as $regex) {
					// clean JS code
					while (preg_match($regex, $chunk))
						$chunk = preg_replace_callback(
							$regex,
							create_function('$m',
								'return $m[1].$m[2].$m[3]."<?js \n"
									.str_replace(
										array("%20", "%3E", "%09", "&#10;", "&#9;", "%7B", "%24", "%7D", "%22", "%5B", "%5D"),
										array(" ", ">", "	", "\n", "	", "{", "$", "}", \'"\', "[", "]"),
										htmlspecialchars_decode($m[4])
									)
									." \n?".">".$m[5].$m[2];'
							),
							$chunk
						);
				}
				$chunk = preg_split(
					'@(<\\?js.*?\\?>)@s',
					$chunk,
					null,
					PREG_SPLIT_DELIM_CAPTURE
				);
				foreach($chunk as $_chunk) {
					if (! $_chunk)
						continue;
					if (substr($_chunk, 0, 4) == '<?js')
						$template .= substr($_chunk, 4, -2).";\n";
					else
						$template .= "\t__template += '"
							.phpQuery::$plugins->jsVarValue($_chunk)
							."';\n";
				}
			}
		}
		return $template;
	}
	public static function jsVarValue($value, $delimiter = "'") {
		return str_replace(array("\n", "$delimiter"), array("\\\n", "\\$delimiter"), $value);
	}
}