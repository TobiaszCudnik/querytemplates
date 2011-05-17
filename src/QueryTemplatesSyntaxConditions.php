<?php
/**
 * Class extending phpQueryObject with templating methods.
 *
 * @abstract
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
class QueryTemplatesSyntaxConditions extends QueryTemplatesSyntaxCode {
	/**
	 * Replaces selected tag with PHP "if" statement containing $code as condition.
	 *
	 * Method doesn't change selected nodes stack.
	 * detached from it's parent.
	 *
	 * Example
	 * <code>
	 * $template['.if']->tagToIfPHP('$foo == 1');
	 * </code>
	 *
	 * Source
	 * <code>
	 * <div class='if'><node1/></div>
	 * </code>
	 *
	 * Result
	 * <code>
	 * <?php
	 * if ($foo == 1) {
	 * ?><node1/><?php
	 * }
	 * ?>
	 * </code>
	 *
	 * @param string $code
	 * Valid PHP condition code
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function tagToIfPHP($code) {
		return $this->_tagToIf('php', $code);
	}
	private function _tagToIf($lang, $code) {
		$lang = strtoupper($lang);
		foreach($this as $node) {
			$node = pq($node, $this->getDocumentID())
				->{"if$lang"}($code)
				->contents()
					->insertAfter($this)->end()
				->remove();
		}
		return $this;
	}
	/**
	 * Replaces selected tag with PHP "if" statement checking if $var evaluates
	 * to TRUE. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Method doesn't change selected nodes stack.
	 * detached from it's parent.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->
	 * 	tagToIfVar('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  if ((isset($data['foo']['bar']['0']) && $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && $data->{'foo'}->{'bar'}->{'0'})) {  ?>2<?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::ifVar()
	 */
	public function tagToIfVar($var) {
		foreach($this as $node) {
			$node = pq($node, $this->getDocumentID())
				->ifVar($var)
				->contents()
					->insertAfter($node)->end()
				->remove();
		}
		return $this;
	}
	/**
	 * Replaces selected tag with PHP "if" statement checking if $var evaluates
	 * to FALSE. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Method doesn't change selected nodes stack.
	 * detached from it's parent.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->
	 * 	tagToIfNotVar('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  if ((isset($data['foo']['bar']['0']) && ! $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && ! $data->{'foo'}->{'bar'}->{'0'})) {  ?>2<?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::ifVar()
	 */
	public function tagToIfNotVar($var) {
		foreach($this as $node) {
			$node = pq($node, $this->getDocumentID())
				->ifNotVar($var)
				->contents()
					->insertAfter($node)->end()
				->remove();
		}
		return $this;
	}
	/**
	 * Replaces selected tag with PHP "else if" statement containing $code as condition.
	 *
	 * Method doesn't change selected nodes stack.
	 * detached from it's parent.
	 *
	 * Example
	 * <code>
	 * $template['.else-if']->tagToElseIfPHP('$foo == 1');
	 * </code>
	 *
	 * Source
	 * <code>
	 * <div class='else-if'><node1/></div>
	 * </code>
	 *
	 * Result
	 * <code>
	 * <?php
	 * else if ($foo == 1) {
	 * ?><node1/><?php
	 * }
	 * ?>
	 * </code>
	 *
	 * @param string $code
	 * Valid PHP condition code
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function tagToElseIfPHP($code) {
		return $this->_tagToElseIf('php', $code);
	}
	private function _tagToElseIf($lang, $code) {
		$lang = strtoupper($lang);
		foreach($this as $node) {
			$node = pq($node, $this->getDocumentID())
				->{"elseIf$lang"}($code)
				->contents()
					->insertAfter($node)->end()
				->remove();
		}
		return $this;
	}
	/**
	 * Replaces selected tag with PHP "else if" statement checking if $var evaluates
	 * to TRUE. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Method doesn't change selected nodes stack.
	 * detached from it's parent.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->
	 * 	tagToElseIfVar('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  else if ((isset($data['foo']['bar']['0']) && $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && $data->{'foo'}->{'bar'}->{'0'})) {  ?>2<?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::ifVar()
	 */
	public function tagToElseIfVar($var) {
		foreach($this as $node) {
			$node = pq($node, $this->getDocumentID())
				->elseIfVar($var)
				->contents()
					->insertAfter($node)->end()
				->remove();
		}
		return $this;
	}
	/**
	 * Replaces selected tag with PHP "else if" statement checking if $var evaluates
	 * to FALSE. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Method doesn't change selected nodes stack.
	 * detached from it's parent.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->
	 * 	tagToElseIfNotVar('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  else if ((isset($data['foo']['bar']['0']) && ! $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && ! $data->{'foo'}->{'bar'}->{'0'})) {  ?>2<?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::ifVar()
	 */
	public function tagToElseIfNotVar($var) {
		foreach($this as $node) {
			$node = pq($node, $this->getDocumentID())
				->elseIfNotVar($var)
				->contents()
					->insertAfter($node)->end()
				->remove();
		}
		return $this;
	}
		public function tagToElseStatement() {
		return $this->_tagToElse($this->qt_lang());
	}
	public function tagToElsePHP() {
		return $this->_tagToElse('php');
	}
	public function _tagToElse($lang) {
		$lang = strtoupper($lang);
		foreach($this as $node) {
			$node = pq($node, $this->getDocumentID())
			->{"else$lang"}()
			->contents()
				->insertAfter($node)->end()
			->remove();
		}
		return $this;
	}
	/**
	 * Wraps selected tag with PHP "if" statement containing $code as condition.
	 *
	 * Optional $separate parameter determines if selected elements should be
	 * wrapped together or one-by-one. This is usefull when stack contains non-sibling
	 * nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * Example
	 * <code>
	 * $template['node1']->tagToElseIf('$foo == 1');
	 * </code>
	 *
	 * Source
	 * <code>
	 * <node1/>
	 * </code>
	 *
	 * Result
	 * <code>
	 * <?php
	 * else if ($foo == 1) {
	 * ?><node1/><?php
	 * }
	 * ?>
	 * </code>
	 *
	 * @param string $code
	 * Valid PHP condition code
	 * @param bool $separate
	 * Determines if selected elements should be wrapped together or one-by-one
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @TODO hardcode php code
	 */
	public function ifPHP($code, $separate = false) {
		return $this->_if('php', $code, $separate);
	}
	public function _if($lang, $code, $separate = false) {
		$lang = strtoupper($lang);
		$method = $separate
			? 'wrap'
			: 'wrapAll';
		$languageClass = 'QueryTemplatesLanguage'.$lang;
		$code = call_user_func_array(
			array($languageClass, 'ifCode'), array($code)
		);
		$this->{$method.$lang}($code[0], $code[1]);
		return $this;
	}
	/**
	 * Wraps selected tag with PHP "if" statement checking if $var evaluates
	 * to TRUE. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Optional $separate parameter determines if selected elements should be
	 * wrapped together or one-by-one. This is usefull when stack contains non-sibling
	 * nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->
	 * 	ifVar('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  if ((isset($data['foo']['bar']['0']) && $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && $data->{'foo'}->{'bar'}->{'0'})) {  ?><div>2</div>
	 * <?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * div
	 *  - Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 * @param bool $separate
	 * Determines if selected elements should be wrapped together or one-by-one
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::elseStatement()
	 */
	public function ifVar($var, $separate = false) {
		$method = $separate
			? 'wrap' : 'wrapAll';
		$code = $this->qt_langCode('ifVar', $var);
		$this->qt_langMethod($method, $code[0], $code[1]);
		return $this;
	}
	/**
	 * TODO doc
	 * @param $code
	 * @param $separate
	 * @return unknown_type
	 */
	public function ifCode($code, $separate = false) {
		return $this->qt_langMethod('if', $code, $separate);
	}
	/**
	 * Wraps selected tag with PHP "if" statement checking if $var evaluates
	 * to FALSE. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Optional $separate parameter determines if selected elements should be
	 * wrapped together or one-by-one. This is usefull when stack contains non-sibling
	 * nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->
	 * 	ifNotVar('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  if ((isset($data['foo']['bar']['0']) && ! $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && ! $data->{'foo'}->{'bar'}->{'0'})) {  ?><div>2</div>
	 * <?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * div
	 *  - Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 * @param bool $separate
	 * Determines if selected elements should be wrapped together or one-by-one
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::elseStatement()
	 */
	public function ifNotVar($var, $separate = false) {
		$method = $separate
			? 'wrap' : 'wrapAll';
		$code = $this->qt_langCode('ifNotVar', $var);
		$this->qt_langMethod($method, $code[0], $code[1]);
		return $this;
	}
	/**
	 * Wraps selected tag with PHP "else if" statement containing $code as condition.
	 *
	 * Optional $separate parameter determines if selected elements should be
	 * wrapped together or one-by-one. This is usefull when stack contains non-sibling
	 * nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * Example
	 * <code>
	 * $template['node1']->elseIfPHP('$foo == 1');
	 * </code>
	 *
	 * Source
	 * <code>
	 * <node1/>
	 * </code>
	 *
	 * Result
	 * <code>
	 * <?php
	 * else if ($foo == 1) {
	 * ?><node1/><?php
	 * }
	 * ?>
	 * </code>
	 *
	 * @param string $code
	 * Valid PHP condition code
	 * @param bool $separate
	 * Determines if selected elements should be wrapped together or one-by-one
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @TODO hardcode php code
	 */
	public function elseIfPHP($code, $separate = false) {
		$lang = strtoupper($lang);
		$method = $separate
			? 'wrap'
			: 'wrapAll';
		$lang = $this->parent->language;
		$languageClass = 'QueryTemplatesLanguage'.$lang;
		$code = call_user_func_array(
			array($languageClass, 'elseIfCode'), array($code)
		);
		$this->{$method.$lang}($code[0], $code[1]);
		return $this;
	}
	public function elseIfCode($code, $separate = false) {
		return $this->qt_method('if', $code, $separate);
	}
	/**
	 * Wraps selected tag with PHP "else if" statement checking if $var evaluates
	 * to true. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Optional $separate parameter determines if selected elements should be
	 * wrapped together or one-by-one. This is usefull when stack contains non-sibling
	 * nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->elseIfVar('data.foo.bar.0');
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  else if ((isset($data['foo']['bar']['0']) && $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && $data->{'foo'}->{'bar'}->{'0'})) {  ?><div>2</div>
	 * <?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * div
	 *  - Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 * @param bool $separate
	 * Determines if selected elements should be wrapped together or one-by-one
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function elseIfVar($var, $separate = false) {
		$method = $separate
			? 'wrap' : 'wrapAll';
		$code = $this->qt_langCode('elseIfVar', $var);
		$this->qt_langMethod($method, $code[0], $code[1]);
		return $this;
	}
	/**
	 * Wraps selected tag with PHP "else if" statement checking if $var evaluates
	 * to FALSE. $var must be available inside template's scope.
	 *
	 * $var is passed in JavaScript object notation (dot separated).
	 *
	 * Optional $separate parameter determines if selected elements should be
	 * wrapped together or one-by-one. This is usefull when stack contains non-sibling
	 * nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * Notice-safe.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array(true)
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->elseIfNotVar('data.foo.bar.0');
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  else if ((isset($data['foo']['bar']['0']) && ! $data['foo']['bar']['0']) || (isset($data->{'foo'}->{'bar'}->{'0'}) && ! $data->{'foo'}->{'bar'}->{'0'})) {  ?><div>2</div>
	 * <?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * div
	 *  - Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param string $var
	 * Dot-separated object path, eg Object.property.inneProperty
	 * @param bool $separate
	 * Determines if selected elements should be wrapped together or one-by-one
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function elseIfNotVar($var, $separate = false) {
		$method = $separate
			? 'wrap' : 'wrapAll';
		$code = $this->qt_langCode('elseIfNotVar', $var);
		$this->qt_langMethod($method, $code[0], $code[1]);
		return $this;
	}
	/**
	 * Wraps selected tag with PHP "else" statement.
	 *
	 * Optional $separate parameter determines if selected elements should be
	 * wrapped together or one-by-one. This is usefull when stack contains non-sibling
	 * nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * @param bool $separate
	 * Determines if selected elements should be wrapped together or one-by-one
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function elsePHP($separate = false) {
		return $this->elseStatement($separate, 'php');
	}
	/**
	 * TODO description
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>1</div>
	 * <div>2</div>
	 * <div>3</div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 *
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['div:eq(1)']->
	 * 	elseStatement()
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <div>1</div>
	 * <?php  else {  ?><div>2</div>
	 * <?php  }  ?>
	 * <div>3</div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - Text:1
	 * div
	 *  - Text:2
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - Text:1
	 * PHP
	 * div
	 *  - Text:2
	 * PHP
	 * div
	 *  - Text:3
	 * </code>
	 *
	 * @param $separate
	 * @param $lang
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function elseStatement($separate = false, $lang = null) {
		$lang = $lang
			? strtoupper($lang)
			: $this->qt_lang();
		$languageClass = 'QueryTemplatesLanguage'.$lang;
		$code = call_user_func_array(
			array($languageClass, 'elseStatement'), array()
		);
		return $separate
			? $this
				->{"before$lang"}($code[0])
				->{"after$lang"}($code[1])
			: $this
				->filter(':first')->{"before$lang"}($code[0])->end()
				->filter(':last')->{"after$lang"}($code[1])->end();
	}
	/**
	 * Honors code between onlyPHP and endOnly, only for PHP templates.
	 *
	 * TODO: Is theres something wrong with this name ?
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function onlyPHP() {
		return strtolower($this->qt_lang()) == 'php'
			? $this : new QueryTemplatesVoid($this, 'endOnly');
	}
	/**
	 * TODO Move to jsCode plugin.
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function onlyJS() {
		return strtolower($this->qt_lang()) == 'js'
			? $this : new QueryTemplatesVoid($this, 'endOnly');
	}
	/**
	 * TODO docs
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function onlyIf($condition) {
		return $condition
			? $this : new QueryTemplatesVoid($this, 'endOnly');
	}
	/**
	 * TODO docs
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	/**
	 * TODO docs
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function onlyIfNot($condition) {
		return $condition
			? new QueryTemplatesVoid($this, 'endOnly') : $this;
	}
	/**
	 * TODO docs
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function endOnly() {
		return $this;
	}
}