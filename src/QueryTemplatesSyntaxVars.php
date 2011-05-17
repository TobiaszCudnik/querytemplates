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
class QueryTemplatesSyntaxVars extends QueryTemplatesSyntaxConditions {
	/**
	 * Prints variable $varName as matched elements' content.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array('printMe')
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['p']->
	 * 	varPrint('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <div>
	 * 	<p><?php  if (isset($data['foo']['bar']['0'])) print $data['foo']['bar']['0'];
	 * else if (isset($data->{'foo'}->{'bar'}->{'0'})) print $data->{'foo'}->{'bar'}->{'0'};  ?></p>
	 * </div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - p
	 *  -  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 */
	public function varPrint($varName) {
		return $this->_varPrint('markup', $varName);
	}
	/**
	 * Prints variable $varName replacing matched elements.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array('printMe')
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['p']->
	 * 	varPrintReplace('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <div>
	 * 	<?php  if (isset($data['foo']['bar']['0'])) print $data['foo']['bar']['0'];
	 * else if (isset($data->{'foo'}->{'bar'}->{'0'})) print $data->{'foo'}->{'bar'}->{'0'};  ?>
	 * </div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 */
	public function varPrintReplace($varName) {
		return $this->_varPrint('replaceWith', $varName);
	}
	/**
	 * Prints variable $varName before matched elements.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array('printMe')
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['p']->
	 * 	varPrintBefore('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <div>
	 * 	<?php  if (isset($data['foo']['bar']['0'])) print $data['foo']['bar']['0'];
	 * else if (isset($data->{'foo'}->{'bar'}->{'0'})) print $data->{'foo'}->{'bar'}->{'0'};  ?><p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - PHP
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 */
	public function varPrintBefore($varName) {
		return $this->_varPrint('before', $varName);
	}
	/**
	 * Prints variable $varName after matched elements.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array('printMe')
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['p']->
	 * 	varPrintAfter('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * <?php  if (isset($data['foo']['bar']['0'])) print $data['foo']['bar']['0'];
	 * else if (isset($data->{'foo'}->{'bar'}->{'0'})) print $data->{'foo'}->{'bar'}->{'0'};  ?>
	 * </div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 */
	public function varPrintAfter($varName) {
		return $this->_varPrint('after', $varName);
	}
	/**
	 * Prints variable $varName on beggining of matched elements.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array('printMe')
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['p']->
	 * 	varPrintPrepend('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <div>
	 * 	<p><?php  if (isset($data['foo']['bar']['0'])) print $data['foo']['bar']['0'];
	 * else if (isset($data->{'foo'}->{'bar'}->{'0'})) print $data->{'foo'}->{'bar'}->{'0'};  ?>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - p
	 *  -  - PHP
	 *  -  - Text:FOO
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 */
	public function varPrintPrepend($varName) {
		return $this->_varPrint('prepend', $varName);
	}
	/**
	 * Prints variable $varName on the end of matched elements.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array('printMe')
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['p']->
	 * 	varPrintAppend('data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <div>
	 * 	<p>FOO<?php  if (isset($data['foo']['bar']['0'])) print $data['foo']['bar']['0'];
	 * else if (isset($data->{'foo'}->{'bar'}->{'0'})) print $data->{'foo'}->{'bar'}->{'0'};  ?></p>
	 * </div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 *  -  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 */
	public function varPrintAppend($varName) {
		return $this->_varPrint('append', $varName);
	}
	public function _varPrint($target, $varName) {
		$_target = $target;
		$targetData = null;
		if (is_array($target)) {
			$targetData = array_slice($target, 1);
			$target = $target[0];
		}
		switch($target) {
			case 'attr':
				$this->qt_langMethod('attr', $targetData[0],
					$this->qt_langCode('printVar', $varName)
				);
				break;
			default:
				$this->qt_langMethod($target,
					$this->qt_langCode('printVar', $varName)
				);
		}
		return $this;
	}
	/**
	 * Prints variable $varName as attribute $attr of matched elements.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <div>
	 * 	<p>FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'foo' => array(
	 *   	'bar' => array('printMe')
	 *   )
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['p']->
	 * 	varPrintAttr('rel', 'data.foo.bar.0')
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <div>
	 * 	<p data.foo.bar.0="<?php  if (isset($data['foo']['bar']['0'])) print $data['foo']['bar']['0'];
	 * else if (isset($data->{'foo'}->{'bar'}->{'0'})) print $data->{'foo'}->{'bar'}->{'0'};  ?>">FOO</p>
	 * </div>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * div
	 *  - p
	 *  -  - Text:FOO
	 * </code>
	 *
	 * @param String $attr
	 * Target attribute name.
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 */
	public function varPrintAttr($attr, $varName) {
		return $this->_varPrint(array('attr', $varName), $varName);
	}
	/**
	 * Injects executable code printing variable's fields inside nodes matched by
	 * selector. Method uses actually matched nodes as root for the query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->field1 = 'foo';
	 * $foo->field2 = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToSelector('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1"><?php  if (isset($foo['field1'])) print $foo['field1'];
	 * else if (isset($foo->{'field1'})) print $foo->{'field1'};  ?></p>
	 * <p class="field2"><?php  if (isset($foo['field2'])) print $foo['field2'];
	 * else if (isset($foo->{'field2'})) print $foo->{'field2'};  ?></p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - PHP
	 * p.field2
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * variables key (field).
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToSelector($varName, $varFields, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToSelector('markup', $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields replacing nodes matched by
	 * selector. Method uses actually matched nodes as root for the query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->field1 = 'foo';
	 * $foo->field2 = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToSelectorReplace('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <?php  if (isset($foo['field1'])) print $foo['field1'];
	 * else if (isset($foo->{'field1'})) print $foo->{'field1'};  ?>
	 * <?php  if (isset($foo['field2'])) print $foo['field2'];
	 * else if (isset($foo->{'field2'})) print $foo->{'field2'};  ?>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * PHP
	 * PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToSelectorReplace($varName, $varFields, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToSelector('replaceWith', $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields at the end of nodes
	 * matched by selector. Method uses actually matched nodes as root for the
	 * query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->field1 = 'foo';
	 * $foo->field2 = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToSelectorAppend('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1">lorem ipsum<?php  if (isset($foo['field1'])) print $foo['field1'];
	 * else if (isset($foo->{'field1'})) print $foo->{'field1'};  ?></p>
	 * <p class="field2">lorem ipsum<?php  if (isset($foo['field2'])) print $foo['field2'];
	 * else if (isset($foo->{'field2'})) print $foo->{'field2'};  ?></p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 *  - PHP
	 * p.field2
	 *  - Text:lorem ipsum
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * variables key (field).
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToSelectorAppend($varName, $varFields, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToSelector('append', $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields at the beggining of nodes
	 * matched by selector. Method uses actually matched nodes as root for the
	 * query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->field1 = 'foo';
	 * $foo->field2 = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToSelectorPrepend('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1"><?php  if (isset($foo['field1'])) print $foo['field1'];
	 * else if (isset($foo->{'field1'})) print $foo->{'field1'};  ?>lorem ipsum</p>
	 * <p class="field2"><?php  if (isset($foo['field2'])) print $foo['field2'];
	 * else if (isset($foo->{'field2'})) print $foo->{'field2'};  ?>lorem ipsum</p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - PHP
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - PHP
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * variables key (field).
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToSelectorPrepend($varName, $varFields, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToSelector('prepend', $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields after nodes matched by
	 * selector. Method uses actually matched nodes as root for the query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->field1 = 'foo';
	 * $foo->field2 = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToSelectorAfter('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1">lorem ipsum</p>
	 * <?php  if (isset($foo['field1'])) print $foo['field1'];
	 * else if (isset($foo->{'field1'})) print $foo->{'field1'};  ?>
	 * <p class="field2">lorem ipsum</p>
	 * <?php  if (isset($foo['field2'])) print $foo['field2'];
	 * else if (isset($foo->{'field2'})) print $foo->{'field2'};  ?>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * PHP
	 * p.field2
	 *  - Text:lorem ipsum
	 * PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * variables key (field).
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToSelectorAfter($varName, $varFields, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToSelector('after', $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields before nodes matched by
	 * selector. Method uses actually matched nodes as root for the query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->field1 = 'foo';
	 * $foo->field2 = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToSelectorBefore('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <?php  if (isset($foo['field1'])) print $foo['field1'];
	 * else if (isset($foo->{'field1'})) print $foo->{'field1'};  ?><p class="field1">lorem ipsum</p>
	 * <?php  if (isset($foo['field2'])) print $foo['field2'];
	 * else if (isset($foo->{'field2'})) print $foo->{'field2'};  ?><p class="field2">lorem ipsum</p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * PHP
	 * p.field1
	 *  - Text:lorem ipsum
	 * PHP
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * variables key (field).
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToSelectorBefore($varName, $varFields, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToSelector('before', $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields as attribute of nodes
	 * matched by selector. Method uses actually matched nodes as root for the
	 * query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->field1 = 'foo';
	 * $foo->field2 = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToSelectorAttr('rel', 'foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1" rel="<?php  if (isset($foo['field1'])) print $foo['field1'];
	 * else if (isset($foo->{'field1'})) print $foo->{'field1'};  ?>">lorem ipsum</p>
	 * <p class="field2" rel="<?php  if (isset($foo['field2'])) print $foo['field2'];
	 * else if (isset($foo->{'field2'})) print $foo->{'field2'};  ?>">lorem ipsum</p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * @param String $attr
	 * Target attribute name.
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * variables key (field).
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToSelectorAttr($attr, $varName, $varFields, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToSelector(array('attr', $attr), $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Internal method.
	 *
	 * @param $target
	 * @param $varName
	 * @param $varFields
	 * @param $selectorPattern
	 * @param $skipFields
	 * @param $fieldCallback
	 * @return unknown_type
	 *
	 * @TODO support $varName to be a function (last char == ')'),
	 */
	 protected function _varsToSelector($target, $varName, $varFields, $selectorPattern, $filters, $skipFields, $fieldCallback) {
		$filterVar = QueryTemplatesLanguage::filterVarNameCallbacks($varName);
		$filterVar = implode('|', $filterVar);
		$loop = $this->_varsParseFields($varFields);
		$_target = $target;
		$targetData = null;
		if (is_array($target)) {
			$targetData = array_slice($target, 1);
			$target = $target[0];
		}
		foreach($loop as $f) {
			if ($skipFields && in_array($f, $skipFields))
				continue;
			$filter = $filterVar;
			if (isset($filters[$f]))
				$filter = array_merge($filter, $filters[$f]);
			else if (is_string($filters))
				$filter = array_merge($filter, array($filters));
			if ($filter)
				$filter = '.|'.implode('|', $filter);
			$selector = str_replace(array('%k'), array($f), $selectorPattern);
			$node = $this->find($selector);
			switch($target) {
				case 'attr':
					$node->qt_langMethod('attr', $targetData[0],
						$this->qt_langCode('printVar', "$varName.$f$filter")
					);
					break;
				default:
					$node->qt_langMethod($target,
						$this->qt_langCode('printVar', "$varName.$f$filter")
					);
			}
			if ($fieldCallback)
				phpQuery::callbackRun($fieldCallback, array($node, $f, $_target));
		}
		return $this;
	}
	/**
	 * Injects executable code printing variable's fields inside actually matched
	 * nodes. Second param needs to be wrapped with array_keys for non-associative
	 * arrays.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * <node2/>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->first = 'foo';
	 * $foo->second = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	varsToStack('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><?php  if (isset($foo['first'])) print $foo['first'];
	 * else if (isset($foo->{'first'})) print $foo->{'first'};  ?></node1><node2></node2><node1><?php  if (isset($foo['second'])) print $foo['second'];
	 * else if (isset($foo->{'second'})) print $foo->{'second'};  ?></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - PHP
	 * node2
	 * node1
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 * Param needs to be passed thou array_keys for non-assosiative arrays.
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToSelector()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToStack($varName, $varFields, $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToStack('markup', $varName, $varFields, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields replacing actually matched
	 * nodes. Second param needs to be wrapped with array_keys for non-assosiative
	 * arrays.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * <node2/>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->first = 'foo';
	 * $foo->second = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	varsToStackReplace('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <?php  if (isset($foo['first'])) print $foo['first'];
	 * else if (isset($foo->{'first'})) print $foo->{'first'};  ?><node2></node2><?php  if (isset($foo['second'])) print $foo['second'];
	 * else if (isset($foo->{'second'})) print $foo->{'second'};  ?>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * PHP
	 * node2
	 * PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 * Param needs to be passed thou array_keys for non-assosiative arrays.
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToSelector()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToStackReplace($varName, $varFields, $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToStack('replaceWith', $varName, $varFields, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields at the end of actually
	 * matched nodes. Second param needs to be wrapped with array_keys for
	 * non-assosiative arrays.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * <node2/>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->first = 'foo';
	 * $foo->second = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	varsToStackAppend('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2><?php  if (isset($foo['first'])) print $foo['first'];
	 * else if (isset($foo->{'first'})) print $foo->{'first'};  ?></node1><node2></node2><node1><node2></node2><?php  if (isset($foo['second'])) print $foo['second'];
	 * else if (isset($foo->{'second'})) print $foo->{'second'};  ?></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - node2
	 *  - PHP
	 * node2
	 * node1
	 *  - node2
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 * Param needs to be passed thou array_keys for non-assosiative arrays.
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToSelector()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToStackAppend($varName, $varFields, $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToStack('append', $varName, $varFields, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields at the beggining of actually
	 * matched nodes. Second param needs to be wrapped with array_keys for
	 * non-assosiative arrays.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * <node2/>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->first = 'foo';
	 * $foo->second = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	varsToStackPrepend('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><?php  if (isset($foo['first'])) print $foo['first'];
	 * else if (isset($foo->{'first'})) print $foo->{'first'};  ?><node2></node2></node1><node2></node2><node1><?php  if (isset($foo['second'])) print $foo['second'];
	 * else if (isset($foo->{'second'})) print $foo->{'second'};  ?><node2></node2></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - PHP
	 *  - node2
	 * node2
	 * node1
	 *  - PHP
	 *  - node2
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 * Param needs to be passed thou array_keys for non-assosiative arrays.
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToSelector()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToStackPrepend($varName, $varFields, $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToStack('prepend', $varName, $varFields, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields after actually matched
	 * nodes. Second param needs to be wrapped with array_keys for
	 * non-assosiative arrays.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * <node2/>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->first = 'foo';
	 * $foo->second = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	varsToStackAfter('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2></node1><?php  if (isset($foo['first'])) print $foo['first'];
	 * else if (isset($foo->{'first'})) print $foo->{'first'};  ?><node2></node2><node1><node2></node2></node1><?php  if (isset($foo['second'])) print $foo['second'];
	 * else if (isset($foo->{'second'})) print $foo->{'second'};  ?>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - node2
	 * PHP
	 * node2
	 * node1
	 *  - node2
	 * PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 * Param needs to be passed thou array_keys for non-assosiative arrays.
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToSelector()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToStackAfter($varName, $varFields, $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToStack('after', $varName, $varFields, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields before actually matched
	 * nodes. Second param needs to be wrapped with array_keys for
	 * non-assosiative arrays.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * <node2/>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->first = 'foo';
	 * $foo->second = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	varsToStackBefore('foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <?php  if (isset($foo['first'])) print $foo['first'];
	 * else if (isset($foo->{'first'})) print $foo->{'first'};  ?><node1><node2></node2></node1><node2></node2><?php  if (isset($foo['second'])) print $foo['second'];
	 * else if (isset($foo->{'second'})) print $foo->{'second'};  ?><node1><node2></node2></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * PHP
	 * node1
	 *  - node2
	 * node2
	 * PHP
	 * node1
	 *  - node2
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 * Param needs to be passed thou array_keys for non-assosiative arrays.
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToSelector()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToStackBefore($varName, $varFields, $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToStack('before', $varName, $varFields, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects executable code printing variable's fields as attribute of actually
	 * matched nodes. Second param needs to be wrapped with array_keys for
	 * non-assosiative arrays.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * <node2/>
	 * <node1>
	 *   <node2/>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $foo = new stdClass();
	 * $foo->first = 'foo';
	 * $foo->second = 'bar';
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	varsToStackAttr('rel', 'foo', $foo)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1 rel="<?php  if (isset($foo['first'])) print $foo['first'];
	 * else if (isset($foo->{'first'})) print $foo->{'first'};  ?>"><node2></node2></node1><node2></node2><node1 rel="<?php  if (isset($foo['second'])) print $foo['second'];
	 * else if (isset($foo->{'second'})) print $foo->{'second'};  ?>"><node2></node2></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - node2
	 * node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * @param String $attr
	 * Target attribute name.
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 * Variable value with all fields (keys) OR array of variable fields (keys).
	 * Param needs to be passed thou array_keys for non-assosiative arrays.
	 *
	 * @param Array $skipFields
	 * Array of keys from $varValue which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToSelector()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 */
	public function varsToStackAttr($attr, $varName, $varFields, $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_varsToStack(array('attr', $attr), $varName, $varFields, $filters, $skipFields, $fieldCallback);
	}
	protected function _varsToStack($target, $varName, $varValue, $filters, $skipFields, $fieldCallback) {
		$loop = $this->_varsParseFields($varValue);
		$filterVar = QueryTemplatesLanguage::filterVarNameCallbacks($varName);
		$filterVar = implode('|', $filterVar);
		$_target = $target;
		$targetData = null;
		if (is_array($target)) {
			$targetData = array_slice($target, 1);
			$target = $target[0];
		}
		$i = 0;
		foreach($loop as $f) {
			if ($skipFields && in_array($f, $skipFields))
				continue;
			$filter = $filterVar;
			if (isset($filters[$f]))
				$filter = array_merge($filter, $filters[$f]);
			else if (is_string($filters))
				$filter = array_merge($filter, array($filters));
			if ($filter)
				$filter = '.|'.implode('|', $filter);
			$node = $this->eq($i++);
			switch($target) {
				case 'attr':
					$node->qt_langMethod('attr', $targetData[0],
						$this->qt_langCode('printVar', "$varName.$f$filter")
					);
					break;
				default:
					$node->qt_langMethod($target,
						$this->qt_langCode('printVar', "$varName.$f$filter")
					);
			}
			if ($fieldCallback)
				phpQuery::callbackRun($fieldCallback, array($node, $f, $_target));
		}
		return $this;
	}
	protected function _varsParseFields($varFields) {
		// determine if we have real values in $varValue or just list of fields
		if (is_array($varFields) && array_key_exists(0, $varFields))
			return $varFields;
		else if (is_object($varFields) && isset($varFields->{'0'}))
			return $varFields;
		else
			return is_object($varFields)
//				? get_class_vars(get_class($varFields))
				// TODO use this in other methods
				? array_keys(get_object_vars($varFields))
				: array_keys($varFields);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) into nodes
	 * matched by selector. Method uses actually matched nodes as root for the
	 * query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array(
	 * 	'field1' => '<foo/>',
	 * 	'field2' => '<bar/>'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	valuesToSelector($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1"><foo></foo></p>
	 * <p class="field2"><bar></bar></p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - foo
	 * p.field2
	 *  - bar
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of fields from $values which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToStack()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToSelector($values, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToSelector('markup', $values, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) replacing nodes
	 * matched by selector. Method uses actually matched nodes as root for the
	 * query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array(
	 * 	'field1' => '<foo/>',
	 * 	'field2' => '<bar/>'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	valuesToSelectorReplace($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <foo></foo>
	 * <bar></bar>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * foo
	 * bar
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of fields from $values which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToStack()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToSelectorReplace($values, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToSelector('replaceWith', $values, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) before nodes
	 * matched by selector. Method uses actually matched nodes as root for the
	 * query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array(
	 * 	'field1' => '<foo/>',
	 * 	'field2' => '<bar/>'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	valuesToSelectorBefore($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <foo></foo><p class="field1">lorem ipsum</p>
	 * <bar></bar><p class="field2">lorem ipsum</p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * foo
	 * p.field1
	 *  - Text:lorem ipsum
	 * bar
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of fields from $values which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToStack()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToSelectorBefore($values, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToSelector('before', $values, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) after nodes
	 * matched by selector. Method uses actually matched nodes as root for the
	 * query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array(
	 * 	'field1' => '<foo/>',
	 * 	'field2' => '<bar/>'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	valuesToSelectorAfter($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1">lorem ipsum</p>
	 * <foo></foo>
	 * <p class="field2">lorem ipsum</p>
	 * <bar></bar>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * foo
	 * p.field2
	 *  - Text:lorem ipsum
	 * bar
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of fields from $values which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToStack()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToSelectorAfter($values, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToSelector('after', $values, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) at the beggining of
	 * nodes matched by selector. Method uses actually matched nodes as root
	 * for the query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array(
	 * 	'field1' => '<foo/>',
	 * 	'field2' => '<bar/>'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	valuesToSelectorPrepend($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1"><foo></foo>lorem ipsum</p>
	 * <p class="field2"><bar></bar>lorem ipsum</p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - foo
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - bar
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of fields from $values which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToStack()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToSelectorPrepend($values, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToSelector('prepend', $values, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) at the end of
	 * nodes matched by selector. Method uses actually matched nodes as root
	 * for the query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array(
	 * 	'field1' => '<foo/>',
	 * 	'field2' => '<bar/>'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	valuesToSelectorAppend($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1">lorem ipsum<foo></foo></p>
	 * <p class="field2">lorem ipsum<bar></bar></p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 *  - foo
	 * p.field2
	 *  - Text:lorem ipsum
	 *  - bar
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of fields from $values which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToStack()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToSelectorAppend($values, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToSelector('append', $values, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) as attribute of
	 * nodes matched by selector. Method uses actually matched nodes as root
	 * for the query.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <p class='field1'>lorem ipsum</p>
	 * <p class='field2'>lorem ipsum</p>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array(
	 * 	'field1' => '<foo/>',
	 * 	'field2' => '<bar/>'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	valuesToSelectorAttr('rel', $values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1" rel="&lt;foo/&gt;">lorem ipsum</p>
	 * <p class="field2" rel="&lt;bar/&gt;">lorem ipsum</p>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * p.field1
	 *  - Text:lorem ipsum
	 * p.field2
	 *  - Text:lorem ipsum
	 * </code>
	 *
	 * @param String $attr
	 * Target attribute name.
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching target nodes. %k represents key.
	 * Defaults to ".%k", which matches nodes with class name equivalent to
	 * data source key.
	 * For example, to restrict match to nodes with additional class "foo" change
	 * $selectorPattern to ".foo.%k"
	 *
	 * @param Array $skipFields
	 * Array of fields from $values which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToStack()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToSelectorAttr($attr, $values, $selectorPattern = '.%k', $filters = null, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToSelector(array('attr', $attr), $values, $selectorPattern, $filters, $skipFields, $fieldCallback);
	}
	protected function _valuesToSelector($target, $data, $selectorPattern, $filters, $skipFields, $fieldCallback) {
		$_target = $target;
		$targetData = null;
		if (is_array($target)) {
			$targetData = array_slice($target, 1);
			$target = $target[0];
		}
		foreach($data as $k => $v) {
			if ($skipFields && in_array($f, $skipFields))
				continue;
			if ($v instanceof Callback)
				$v = phpQuery::callbackRun($v);
			$selector = str_replace(array('%k'), array($k), $selectorPattern);
//			$node = $this->find($selector)->add($this->filter($selector));
			$node = $this->find($selector);
			switch($target) {
				case 'attr':
					$node->attr($targetData[0], $v);
					break;
				default:
					$node->$target($v);
			}
			if ($fieldCallback)
				// TODO doc
				phpQuery::callbackRun($fieldCallback, array($node, $k, $_target));
		}
		return $this;
	}
	/**
	 * Wraps selected elements with executable code iterating $varName as $rowName.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <ul>
	 * 	<li class='row'>
	 * 		<span class='name'></span>
	 * 		<ul class='tags'>
	 * 			<li class='tag'>
	 * 				<span class='name'></span>
	 * 			</li>
	 * 		</ul>
	 * 	</li>
	 * </ul>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 * 	array(
	 * 		'User' => array('name' => 'foo'),
	 * 		'Tags' => array(
	 * 			array('name' => 'php'),
	 * 			array('name' => 'js'),
	 * 		),
	 * 	),
	 * 	array(
	 * 		'User' => array('name' => 'bar'),
	 * 		'Tags' => array(
	 * 			array('name' => 'perl'),
	 * 		),
	 * 	),
	 * 	array(
	 * 		'User' => array('name' => 'fooBar'),
	 * 		'Tags' => array(
	 * 			array('name' => 'php'),
	 * 			array('name' => 'js'),
	 * 			array('name' => 'perl'),
	 * 		),
	 * 	),
	 * );
	 * $userFields = array('name');
	 * $tagFields = array('name');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	find('> ul > li')->
	 * 		varsToLoop('data', 'row')->
	 * 		varsToSelector('row', $userFields, 'span.%k')->
	 * 		find('> ul > li')->
	 * 			varsToLoop('row.Tags', 'tag')->
	 * 			varsToSelector('tag', $tagFields)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <ul>
	 * <?php  if (isset($data) && (is_array($data) || is_object($data))) { foreach($data as $row):  ?><li class="row">
	 * 		<span class="name"><?php  if (isset($row['name'])) print $row['name'];
	 * else if (isset($row->{'name'})) print $row->{'name'};  ?></span>
	 * 		<ul class="tags">
	 * <?php  if (isset($row['Tags'])) $__8daca = $row['Tags']; else if (isset($row->{'Tags'})) $__8daca = $row->{'Tags'}; if (isset($__8daca) && (is_array($__8daca) || is_object($__8daca))) { foreach($__8daca as $tag):  ?><li class="tag">
	 * 				<span class="name"><?php  if (isset($tag['name'])) print $tag['name'];
	 * else if (isset($tag->{'name'})) print $tag->{'name'};  ?></span>
	 * 			</li>
	 * <?php  endforeach; }  ?>
	 * 		</ul>
	 * </li>
	 * <?php  endforeach; }  ?>
	 * </ul>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * ul
	 *  - li.row
	 *  -  - span.name
	 *  -  - ul.tags
	 *  -  -  - li.tag
	 *  -  -  -  - span.name
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * ul
	 *  - PHP
	 *  - li.row
	 *  -  - span.name
	 *  -  -  - PHP
	 *  -  - ul.tags
	 *  -  -  - PHP
	 *  -  -  - li.tag
	 *  -  -  -  - span.name
	 *  -  -  -  -  - PHP
	 *  -  -  - PHP
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable which will be looped. Must contain $ at the beggining.
	 *
	 * @param String $rowName
	 * Name of variable being result of iteration.
	 *
	 * @param String $indexName
	 * Optional. Use it when you want to have $varName's key available in the scope.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::valuesToLoop()
	 */
	public function varsToLoop($varName, $rowName, $indexName = null) {
		$this->_varsToLoop($this, $varName, $rowName, $indexName);
		return $this;
	}
	/**
	 * Wraps selected elements with executable code iterating $varName as $rowName.
	 * Acts as varsToLoop(), but affects each selected element separately.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * @param String $varName
	 * Variable which will be looped. Must contain $ at the beggining.
	 *
	 * @param String $rowName
	 * Name of variable being result of iteration.
	 *
	 * @param String $indexName
	 * Optional. Use it when you want to have $varName's key available in the scope.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToLoop()
	 */
	public function varsToLoopSeparate($varName, $rowName, $indexName = null) {
		foreach($this->stack() as $node)
			$this->_varsToLoop($node, $varName, $rowName, $indexName);
		return $this;
	}
	protected function _varsToLoop($pq, $varName, $asVarName, $keyName) {
		$code = $this->qt_langCode('loopVar', $varName, $asVarName, $keyName);
		$pq->
			eq(0)->qt_langMethod('before', $code[0])->end()->
			slice(-1, 1)->qt_langMethod('after', $code[1]);
	}
	/**
	 * Wraps selected elements with executable code iterating $varName as $rowName.
	 * Acts as varsToLoop(), but loops only first node from stack. Rest is removed
	 * from the DOM.
	 *
	 * Method DOES change selected nodes stack. Returned is first node.
	 *
	 * @param String $varName
	 * Variable which will be looped. Must contain $ at the beggining.
	 *
	 * @param String $rowName
	 * Name of variable being result of iteration.
	 *
	 * @param String $indexName
	 * Optional. Use it when you want to have $varName's key available in the scope.
	 *
	 * @return QueryTemplatesPhpQuery|QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::varsToLoop()
	 */
	public function varsToLoopFirst($varName, $rowName, $indexName = null) {
		$return = $this->eq(0);
		$this->slice(1)->remove();
		$this->_varsToLoop($return, $varName, $rowName, $indexName);
		return $return;
	}
	/**
	 * @deprecated use varsToLoopSeparate
	 * @param $varName
	 * @param $asVarName
	 * @param $keyName
	 * @return unknown_type
	 */
	public function loopSeparate($varName, $asVarName, $keyName = null) {
		return $this->varsToLoopSeparate($varName, $asVarName, $keyName);
	}
	/**
	 * @deprecated use varsToLoopFirst
	 * @param $varName
	 * @param $asVarName
	 * @param $keyName
	 * @return unknown_type
	 */
	public function loopOne($varName, $asVarName, $keyName = null) {
		return $this->varsToLoopFirst($varName, $asVarName, $keyName);
	}
	/**
	 * @deprecated use varsToLoop
	 * @param $varName
	 * @param $asVarName
	 * @param $keyName
	 * @return unknown_type
	 */
	public function loop($varName, $asVarName, $keyName = null) {
		return $this->varsToLoop($varName, $asVarName, $keyName);
	}/**
	 * Injects executable code which toggles form fields values and selection
	 * states according to value of variable $varName.
	 *
	 * This includes:
	 * - `input[type=radio][checked]`
	 * - `input[type=checkbox][checked]`
	 * - `select > option[selected]`
	 * - `input[value]`
	 * - `textarea`
	 *
	 * Inputs are selected according to $selectorPattern, where %k represents
	 * variable's key.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <form>
	 *   <input name='input-example'>
	 *   <input name='array[array-example]'>
	 *   <textarea name='textarea-example'></textarea>
	 *   <select name='select-example'>
	 *     <option value='first' selected='selected'></option>
	 *   </select>
	 *   <input type='radio' name='radio-example' value='foo'>
	 *   <input type='checkbox' name='checkbox-example' value='foo'>
	 * </form>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *   'input-example' => 'foo',
	 *   'array-example' => 'foo',
	 *   'textarea-example' => 'foo',
	 *   'select-example' => 'foo',
	 *   'radio-example' => 'foo',
	 *   'checkbox-example' => 'foo',
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	varsToForm('data', $data)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <form>
	 *   <input name="input-example" value="<?php  if (isset($data['input-example'])) print $data['input-example'];
	 * else if (isset($data->{'input-example'})) print $data->{'input-example'};  ?>"><input name="array[array-example]" value="<?php  if (isset($data['array-example'])) print $data['array-example'];
	 * else if (isset($data->{'array-example'})) print $data->{'array-example'};  ?>"><textarea name="textarea-example"><?php  if (isset($data['textarea-example'])) print $data['textarea-example'];
	 * else if (isset($data->{'textarea-example'})) print $data->{'textarea-example'};  ?></textarea><select name="select-example"><?php  if ((isset($data['select-example']) && $data['select-example'] == 'first')
	 * 	|| (isset($data->{'select-example'}) && $data->{'select-example'} == 'first')) {  ?><option value="first" selected></option>
	 * <?php  }    else {  ?><option value="first"></option>
	 * <?php  }  ?></select><?php  if ((isset($data['radio-example']) && $data['radio-example'] == 'foo')
	 * 	|| (isset($data->{'radio-example'}) && $data->{'radio-example'} == 'foo')) {  ?><input type="radio" name="radio-example" value="foo" checked><?php  }    else {  ?><input type="radio" name="radio-example" value="foo"><?php  }    if ((isset($data['checkbox-example']) && $data['checkbox-example'] == 'foo')
	 * 	|| (isset($data->{'checkbox-example'}) && $data->{'checkbox-example'} == 'foo')) {  ?><input type="checkbox" name="checkbox-example" value="foo" checked><?php  }    else {  ?><input type="checkbox" name="checkbox-example" value="foo"><?php  }  ?>
	 * </form>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * form
	 *  - input[name="input-example"]
	 *  - input[name="array[array-example]"]
	 *  - textarea[name="textarea-example"]
	 *  - select[name="select-example"]
	 *  -  - option[value="first"][selected]
	 *  - input[name="radio-example"][value="foo"]
	 *  - input[name="checkbox-example"][value="foo"]
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * form
	 *  - input[name="input-example"][value=PHP]
	 *  - input[name="array[array-example]"][value=PHP]
	 *  - textarea[name="textarea-example"]
	 *  -  - PHP
	 *  - select[name="select-example"]
	 *  -  - PHP
	 *  -  - option[value="first"][selected]
	 *  -  - PHP
	 *  -  - PHP
	 *  -  - option[value="first"]
	 *  -  - PHP
	 *  - PHP
	 *  - input[name="radio-example"][value="foo"][checked]
	 *  - PHP
	 *  - PHP
	 *  - input[name="radio-example"][value="foo"]
	 *  - PHP
	 *  - PHP
	 *  - input[name="checkbox-example"][value="foo"][checked]
	 *  - PHP
	 *  - PHP
	 *  - input[name="checkbox-example"][value="foo"]
	 *  - PHP
	 * </code>
	 *
	 * @param String $varName
	 * Variable avaible in scope of type Array or Object.
	 * $varName should NOT start with $.
	 *
	 * @param Array|Object $varFields
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching form fields.
	 * Defaults to "[name*='%k']", which matches fields containing variable's key in
	 * their names. For example, to match only names starting with "foo[bar]" change
	 * $selectorPattern to "[name^='foo[bar]'][name*='%k']"
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 * @see QueryTemplatesPhpQuery::formFromVars()
	 *
	 * @TODO support select[multiple] (thou array)
	 */
	public function varsToForm($varName, $varFields, $selectorPattern = "[name*='%k']") {
		$loop = $this->_varsParseFields($varFields);
		$lang = strtoupper($this->parent->language);
		$languageClass = 'QueryTemplatesLanguage'.$lang;
		foreach($loop as $f) {
			$code = call_user_func_array(
				array($languageClass, 'printVar'), array("$varName.$f")
			);
			$selector = str_replace(array('%k'), array($f), $selectorPattern);
			// texts, hiddens, passwords
			$this->find("input$selector:not(:radio, :checkbox)")->{"attr$lang"}('value', $code);
			// textareas
			$this->find("textarea$selector")->{strtolower($lang)}($code);
			// radios, checkboxes
			$inputs = $this->find("input$selector:radio, input$selector:checkbox");
			foreach($inputs as $input) {
//				$input = pq($input, $this->getDocumentID());
				$clone = $input->clone()->insertAfter($input);
				$value = $input->attr('value');
				$code = call_user_func_array(
					array($languageClass, 'compareVarValue'),
					array("$varName.$f", $value)
				);
//				$input->attr('checked', 'checked')->ifPHP($code, true);
				$input->attr('checked', 'checked')->{"if$lang"}($code);
				$clone->removeAttr('checked')->{"else$lang"}();
			}
			// selects
			$select = $this->find("select$selector");
			if ($select->length) {
				foreach($select['option'] as $option) {
					$option = pq($option, $this->getDocumentID());
					$clone = $option->clone()->insertAfter($option);
					$code = call_user_func_array(
						array($languageClass, 'compareVarValue'),
						array("$varName.$f", $option->attr('value'))
					);
					$option->attr('selected', 'selected')->{"if$lang"}($code, true);
					$clone->removeAttr('selected')->{"else$lang"}();
				}
			}
		}
		return $this;
	}
}
