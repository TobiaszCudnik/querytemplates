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
class QueryTemplatesSyntaxCode extends QueryTemplatesSyntaxValues {
	/**
	 * Injects raw executable code inside nodes matched by selector. Method uses
	 * actually matched nodes as root for the query.
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
	 * $code = array(
	 * 	'field1' => 'print "abba";',
	 * 	'field2' => 'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToSelector($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1"><?php  print "abba";  ?></p>
	 * <p class="field2"><?php  foreach(array(1, 2, 3) as $i) print $i  ?></p>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
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
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 */
	public function codeToSelector($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToSelector('markup', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code replacing nodes matched by selector. Method uses
	 * actually matched nodes as root for the query.
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
	 * $code = array(
	 * 	'field1' => 'print "abba";',
	 * 	'field2' => 'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToSelectorReplace($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <?php  print "abba";  ?>
	 * <?php  foreach(array(1, 2, 3) as $i) print $i  ?>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
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
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 */
	public function codeToSelectorReplace($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToSelector('replaceWith', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code at the end of nodes matched by selector. Method
	 * uses actually matched nodes as root for the query.
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
	 * $code = array(
	 * 	'field1' => 'print "abba";',
	 * 	'field2' => 'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToSelectorAppend($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1">lorem ipsum<?php  print "abba";  ?></p>
	 * <p class="field2">lorem ipsum<?php  foreach(array(1, 2, 3) as $i) print $i  ?></p>
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
	 * @param String $attr
	 * Target attribute name.
	 *
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
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
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 */
	public function codeToSelectorAppend($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToSelector('append', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code at the beggining of nodes matched by selector.
	 * Method uses actually matched nodes as root for the query.
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
	 * $code = array(
	 * 	'field1' => 'print "abba";',
	 * 	'field2' => 'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToSelectorPrepend($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1"><?php  print "abba";  ?>lorem ipsum</p>
	 * <p class="field2"><?php  foreach(array(1, 2, 3) as $i) print $i  ?>lorem ipsum</p>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
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
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 */
	public function codeToSelectorPrepend($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToSelector('prepend', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code after nodes matched by selector. Method uses
	 * actually matched nodes as root for the query.
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
	 * $code = array(
	 * 	'field1' => 'print "abba";',
	 * 	'field2' => 'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToSelectorAfter($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1">lorem ipsum</p>
	 * <?php  print "abba";  ?>
	 * <p class="field2">lorem ipsum</p>
	 * <?php  foreach(array(1, 2, 3) as $i) print $i  ?>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
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
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 */
	public function codeToSelectorAfter($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToSelector('after', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code before nodes matched by selector. Method uses
	 * actually matched nodes as root for the query.
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
	 * $code = array(
	 * 	'field1' => 'print "abba";',
	 * 	'field2' => 'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToSelectorBefore($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <?php  print "abba";  ?><p class="field1">lorem ipsum</p>
	 * <?php  foreach(array(1, 2, 3) as $i) print $i  ?><p class="field2">lorem ipsum</p>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
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
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 */
	public function codeToSelectorBefore($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToSelector('before', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code inside attribute of nodes matched by selector.
	 * Method uses actually matched nodes as root for the query.
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
	 * $code = array(
	 * 	'field1' => 'print "abba";',
	 * 	'field2' => 'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToSelectorAttr('rel', $code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <p class="field1" rel='<?php  print "abba";  ?>'>lorem ipsum</p>
	 * <p class="field2" rel="<?php  foreach(array(1, 2, 3) as $i) print $i  ?>">lorem ipsum</p>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
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
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToStack()
	 */
	public function codeToSelectorAttr($attr, $codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToSelector(array('attr', $attr), $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	protected function _codeToSelector($target, $codeArray, $selectorPattern, $skipFields, $fieldCallback) {
		$_target = $target;
		$targetData = null;
		if (is_array($target)) {
			$targetData = array_slice($target, 1);
			$target = $target[0];
		}
		foreach($codeArray as $f => $code) {
			if ($skipFields && in_array($f, $skipFields))
				continue;
			$selector = str_replace(array('%k'), array($f), $selectorPattern);
			$node = $this->find($selector);
			switch($target) {
				case 'attr':
					$node->qt_langMethod('attr', $targetData[0], $code);
					break;
				default:
					$node->qt_langMethod($target, $code);
			}
			if ($fieldCallback)
				phpQuery::callbackRun($fieldCallback, array($node, $f, $_target));
		}
		return $this;
	}
	/**
	 * Injects raw executable code inside actually matched nodes.
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
	 * $code = array(
	 * 	'print "abba";',
	 * 	'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToStack($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <?php  print "abba";  ?>
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
	 * </code>
	 *
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
	 *
	 * @param Array $skipFields
	 * Array of fields from $codeArray which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::codeToSelector()
	 */
	public function codeToStack($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToStack('markup', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code replacing actually matched nodes.
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
	 * $code = array(
	 * 	'print "abba";',
	 * 	'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToStackReplace($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2></node1><node2></node2><node1><node2></node2></node1>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
	 *
	 * @param Array $skipFields
	 * Array of fields from $codeArray which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::codeToSelector()
	 */
	public function codeToStackReplace($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToStack('replaceWith', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code at the beggining of actually matched nodes.
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
	 * $code = array(
	 * 	'print "abba";',
	 * 	'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToStackAppend($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2></node1><node2></node2><node1><node2></node2></node1><?php  print "abba";  ?>
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
	 * PHP
	 * </code>
	 *
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
	 *
	 * @param Array $skipFields
	 * Array of fields from $codeArray which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::codeToSelector()
	 */
	public function codeToStackAppend($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToStack('append', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code at the end of actually matched nodes.
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
	 * $code = array(
	 * 	'print "abba";',
	 * 	'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToStackPrepend($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <?php  print "abba";  ?>
	 * <node1><node2></node2></node1><node2></node2><node1><node2></node2></node1>
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
	 * node1
	 *  - node2
	 * </code>
	 *
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
	 *
	 * @param Array $skipFields
	 * Array of fields from $codeArray which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::codeToSelector()
	 */
	public function codeToStackPrepend($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToStack('prepend', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code after actually matched nodes.
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
	 * $code = array(
	 * 	'print "abba";',
	 * 	'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToStackAfter($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2></node1><node2></node2><node1><node2></node2></node1>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
	 *
	 * @param Array $skipFields
	 * Array of fields from $codeArray which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::codeToSelector()
	 */
	public function codeToStackAfter($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToStack('after', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code before actually matched nodes.
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
	 * $code = array(
	 * 	'print "abba";',
	 * 	'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToStackBefore($code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2></node1><node2></node2><node1><node2></node2></node1>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
	 *
	 * @param Array $skipFields
	 * Array of fields from $codeArray which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::codeToSelector()
	 */
	public function codeToStackBefore($codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToStack('before', $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	/**
	 * Injects raw executable code inside attribute of actually matched nodes.
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
	 * $code = array(
	 * 	'print "abba";',
	 * 	'foreach(array(1, 2, 3) as $i) print $i'
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->
	 * 	codeToStackAttr('rel', $code)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2></node1><node2></node2><node1><node2></node2></node1>
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
	 * @param String $codeArray
	 * Array of raw code, where key is the field.
	 *
	 * @param Array $skipFields
	 * Array of fields from $codeArray which should be skipped.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::codeToSelector()
	 */
	public function codeToStackAttr($attr, $codeArray, $selectorPattern = '.%k', $skipFields = null, $fieldCallback = null) {
		return $this->_codeToStack(array('attr', $attr), $codeArray, $selectorPattern, $skipFields, $fieldCallback);
	}
	protected function _codeToStack($target, $codeArray, $selectorPattern, $skipFields, $fieldCallback) {
		$_target = $target;
		$targetData = null;
		if (is_array($target)) {
			$targetData = array_slice($target, 1);
			$target = $target[0];
		}
		$i = 0;
		foreach($codeArray as $f => $code) {
			if ($skipFields && in_array($f, $skipFields))
				continue;
			$node = $this->eq($i++);
			switch($target) {
				case 'attr':
					$node->qt_langMethod('attr', $targetData[0], $code);
					break;
				default:
					$node->qt_langMethod($target, $code);
			}
			if ($fieldCallback)
				// TODO doc
				phpQuery::callbackRun($fieldCallback, array($node, $f, $_target));
		}
		return $this;
	}
}