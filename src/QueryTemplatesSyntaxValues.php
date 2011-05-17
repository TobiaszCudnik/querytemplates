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
class QueryTemplatesSyntaxValues extends QueryTemplatesSyntaxDOM {
	/**
	 * Method loops provided $values on actually selected nodes. Each time new row
	 * is inserted, provided callback is triggered with $dataRow, $node and $dataIndex.
	 * Acts as valuesToLoop(), but affects each selected element separately.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * @param Array|Object $values
	 * Associative array or Object.
	 *
	 * @param Callback|String $rowCallback
	 * Callback triggered for every inserted row. Should support following
	 * parameters:
	 * - $dataRow mixed
	 * - $node phpQueryObject
	 * - $dataIndex mixed
	 * 
	 * @param String|phpQueryObject $targetNodeSelector
	 * Selector or direct node used as relative point for inserting new node(s) for 
	 * each record. Defaults to last inserted node which has a parent.
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToLoop()
	 */
	public function valuesToLoopSeparate($values, $rowCallback, $targetNodeSelector) {
		foreach($this->stack() as $node)
			$this->_valuesToLoop($node, $values, $rowCallback, $targetNodeSelector);
		return $this;
	}
	/**
	 * Method loops provided $values on actually selected nodes. Each time new row
	 * is inserted, provided callback is triggered with $dataRow, $node and $dataIndex.
	 * Acts as valuesToLoop(), but loops only first node from stack. Rest is removed
	 * from the DOM.
	 *
	 * Method DOES change selected nodes stack. Returned is first node.
	 *
	 * @param Array|Object $values
	 * Associative array or Object.
	 *
	 * @param Callback|String $rowCallback
	 * Callback triggered for every inserted row. Should support following
	 * parameters:
	 * - $dataRow mixed
	 * - $node phpQueryObject
	 * - $dataIndex mixed
	 * 
	 * @param String|phpQueryObject $targetNodeSelector
	 * Selector or direct node used as relative point for inserting new node(s) for 
	 * each record. Defaults to last inserted node which has a parent.
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToLoop()
	 */
	public function valuesToLoopFirst($values, $rowCallback, $targetNodeSelector) {
		$return = $this->eq(0);
		$this->slice(1)->remove();
		$this->_valuesToLoop($return, $values, $rowCallback, $targetNodeSelector);
		return $return;
	}
	/**
	 * Method loops provided $values on actually selected nodes. Each time new row
	 * is inserted, provided callback is triggered with $dataRow, $node and $dataIndex.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * This example requires PHP 5.3. For versions before, degradate closures to normal functions.
	 *
	 * === Markup ===
	 * <code>
	 * <ul>
	 *      <li class='row'>
	 *	      <span class='name'></span>
	 *	      <ul class='tags'>
	 *		      <li class='tag'>
	 *			      <span class='tag'></span>
	 *		      </li>
	 *	      </ul>
	 *      </li>
	 * </ul>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array(
	 *      array(
	 *	      'User' => array('name' => 'foo'),
	 *	      'Tags' => array(
	 *		      array('tag' => 'php'),
	 *		      array('tag' => 'js'),
	 *	      ),
	 *      ),
	 *      array(
	 *	      'User' => array('name' => 'bar'),
	 *	      'Tags' => array(
	 *		      array('tag' => 'perl'),
	 *	      ),
	 *      ),
	 *      array(
	 *	      'User' => array('name' => 'fooBar'),
	 *	      'Tags' => array(
	 *		      array('tag' => 'php'),
	 *		      array('tag' => 'js'),
	 *		      array('tag' => 'perl'),
	 *	      ),
	 *      ),
	 * );
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template
	 *      ->find('> ul > li')
	 *	      ->valuesToLoop($data, function($row, $li1) {
	 *		      $li1->valuesToSelector($row['User'], 'span.%k')
	 *			      ->find('> ul > li')
	 *				      ->valuesToLoop($row['Tags'], function($tag, $li2) {
	 *					      $li2->valuesToSelector($tag);
	 *				      })
	 *		      ;
	 *	      });
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <ul>
	 * <li class="row">
	 *	      <span class="name">foo</span>
	 *	      <ul class="tags">
	 * <li class="tag">
	 *			      <span class="tag">php</span>
	 *		      </li>
	 * <li class="tag">
	 *			      <span class="tag">js</span>
	 *		      </li>
	 *	      </ul>
	 * </li>
	 * <li class="row">
	 *	      <span class="name">bar</span>
	 *	      <ul class="tags">
	 * <li class="tag">
	 *			      <span class="tag">perl</span>
	 *		      </li>
	 *	      </ul>
	 * </li>
	 * <li class="row">
	 *	      <span class="name">fooBar</span>
	 *	      <ul class="tags">
	 * <li class="tag">
	 *			      <span class="tag">php</span>
	 *		      </li>
	 * <li class="tag">
	 *			      <span class="tag">js</span>
	 *		      </li>
	 * <li class="tag">
	 *			      <span class="tag">perl</span>
	 *		      </li>
	 *	      </ul>
	 * </li>
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
	 *  -  -  -  - span.tag
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * ul
	 *  - li.row
	 *  -  - span.name
	 *  -  -  - Text:foo
	 *  -  - ul.tags
	 *  -  -  - li.tag
	 *  -  -  -  - span.tag
	 *  -  -  -  -  - Text:php
	 *  -  -  - li.tag
	 *  -  -  -  - span.tag
	 *  -  -  -  -  - Text:js
	 *  - li.row
	 *  -  - span.name
	 *  -  -  - Text:bar
	 *  -  - ul.tags
	 *  -  -  - li.tag
	 *  -  -  -  - span.tag
	 *  -  -  -  -  - Text:perl
	 *  - li.row
	 *  -  - span.name
	 *  -  -  - Text:fooBar
	 *  -  - ul.tags
	 *  -  -  - li.tag
	 *  -  -  -  - span.tag
	 *  -  -  -  -  - Text:php
	 *  -  -  - li.tag
	 *  -  -  -  - span.tag
	 *  -  -  -  -  - Text:js
	 *  -  -  - li.tag
	 *  -  -  -  - span.tag
	 *  -  -  -  -  - Text:perl
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object.
	 *
	 * @param Callback|String $rowCallback
	 * Callback triggered for every inserted row. Should support following
	 * parameters:
	 * - $dataRow mixed
	 * - $node phpQueryObject
	 * - $dataIndex mixed
	 * 
	 * @param String|phpQueryObject $targetNodeSelector
	 * Selector or direct node used as relative point for inserting new node(s) for 
	 * each record. Defaults to last inserted node which has a parent.
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToLoop()
	 */
	public function valuesToLoop($values, $rowCallback, $targetNodeSelector = null) {
		return $this->_valuesToLoop($this, $values, $rowCallback, $targetNodeSelector);
	}
	/**
	 * Method loops provided $values on actually selected nodes. Each time new row
	 * is inserted, provided callback is triggered with $dataRow, $node and $dataIndex.
	 * 
	 * Acts as valuesToLoop(), but new nodes are inserted BEFORE target node.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * @param Array|Object $values
	 * Associative array or Object.
	 *
	 * @param Callback|String $rowCallback
	 * Callback triggered for every inserted row. Should support following
	 * parameters:
	 * - $dataRow mixed
	 * - $node phpQueryObject
	 * - $dataIndex mixed
	 * 
	 * @param String|phpQueryObject $targetNodeSelector
	 * Selector or direct node used as relative point for inserting new node(s) for 
	 * each record. Defaults to last inserted node which has a parent.
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToLoop()
	 */
	public function valuesToLoopBefore($values, $rowCallback, $targetNodeSelector = null) {
		return $this->_valuesToLoop($this, $values, $rowCallback, $targetNodeSelector, 'before');
	}
	protected function _valuesToLoop($pq, $values, $rowCallback, $targetNodeSelector = null, $target = 'after') {
		$lastNode = $pq;
		$injectMethod = 'insert'.ucfirst($target);
		foreach($values as $k => $v) {
			$stack = array();
			foreach($lastNode->reverse() as $node) {
				if ($node->parent()->length) {
					$lastNode = $node;
					break;
				}
			}
			if (isset($targetNodeSelector))
				$nodeTarget = $targetNodeSelector instanceof phpQueryObject
					? $targetNodeSelector
					: $lastNode->parent()->find($newNodeTargetSelector);
			else
				$nodeTarget = $lastNode;
			foreach($pq as $node) {
				$stack[] = $node->clone()->$injectMethod($nodeTarget)->get(0);
			}
			$lastNode = $this->newInstance($stack);
			phpQuery::callbackRun($rowCallback, array($v, $lastNode, $k));
		}
		// we used those nodes as template
		$pq->remove();
		return $this;
	}
	/**
	 * Toggles form fields values and selection states according to static values
	 * from $values.
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
	 * Method doesn't change selected elements stack.
	 *
	 * Example
	 * <code>
	 * $data = array(
	 *   'text-example' => 'new',
   *   'checkbox-example' => true,
   *   'radio-example' => 'second',
	 *   'select-example' => 'second',
	 *   'textarea-example' => 'new',
   * );
	 * $template->valuesToForm($data);
	 * </code>
	 *
	 * Source
	 * <code>
	 * <form>
	 *   <input type='text' name='text-example' value='old'>
	 *   <input type='checkbox' name='checkbox-example' value='foo'>
	 *   <input type='radio' name='radio-example' value='first' checked='checked'>
	 *   <input type='radio' name='radio-example' value='second'>
   *   <select name='select-example'>
	 *     <option value='first' selected='selected'>first</option>
	 *     <option value='second'>second</option>
   *   </select>
	 *   <textarea name='textarea-example'>old</textarea>
	 * </form>
	 * </code>
	 *
	 * Result
	 * <code>
	 * <form>
	 *   <input type='text' name='text-example' value='new'>
	 *   <input type='checkbox' name='checkbox-example' value='foo' checked='checked'>
	 *   <input type='radio' name='radio-example' value='first'>
	 *   <input type='radio' name='radio-example' value='second' checked='checked'>
   *   <select name='select-example'>
	 *     <option value='first'>first</option>
	 *     <option value='second' selected='selected'>second</option>
	 *   </select>
	 *   <textarea name='textarea-example'>new</textarea>
	 * </form>
	 * </code>
	 *
	 * @param Array|Object $values
	 *
	 * @param String $selectorPattern
	 * Defines pattern matching form fields.
	 * Defaults to "[name*='%k']", which matches fields containing
	 * $values' key in their names. For example, to match only names starting with
	 * "foo[bar]" change $selectorPattern to "[name^='foo[bar]'][name*='%k']"
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 * @see QueryTemplatesPhpQuery::formFromValues()
	 */
	public function valuesToForm($values, $selectorPattern = "[name*='%k']") {
		$form = $this->is('form')
			? $this->filter('form')
			: $this->find('form');
		foreach($values as $f => $v) {
			// TODO addslashes to $f
			$selector = str_replace(array('%k'), array($f), $selectorPattern);
//			if (is_array($v) || is_object($v))
//				continue;
			$input = $form->find("input$selector");
			if ($input->length) {
				switch($input->attr('type')) {
					case 'checkbox':
						if ($v)
							$input->attr('checked', 'checked');
						else
							$input->removeAttr('checked');
					break;
					case 'radio':
						$inputChecked = null;
						$input
							->filter("[value='{$v}']")
								->toReference($inputChecked)
								->attr('checked', 'checked')
							->end()
							->not($inputChecked)
								->removeAttr('checked')
							->end();
					break;
					default:
						$input->attr('value', $v);
//						$input->val($v);
				}
			}
			$select = $form->find("select$selector");
			if ($select->length) {
				$selected = null;
				$select->find('option')
					->filter("[value='{$v}']")
						->toReference($selected)
						->attr('selected', 'selected')
					->end()
					->not($selected)
						->removeAttr('selected')
					->end();
			}
			$textarea = $form->find("textarea$selector");
			if ($textarea->length)
				$textarea->markup($v);
		}
		return $this;
	}
	/**
	 * Injects markup from $values' content (rows or attributes) inside actually
	 * selected nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	valuesToStack($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><foo></foo></node1><node1><bar></bar></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - foo
	 * node1
	 *  - bar
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToSelector()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToStack($values, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToStack('markup', $values, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) inside actually
	 * selected nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	valuesToStackReplace($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <foo></foo><bar></bar>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
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
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToSelector()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToStackReplace($values, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToStack('replaceWith', $values, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) inside actually
	 * selected nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	valuesToStackBefore($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <foo></foo><node1><node2></node2></node1><bar></bar><node1><node2></node2></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * foo
	 * node1
	 *  - node2
	 * bar
	 * node1
	 *  - node2
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToSelector()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToStackBefore($values, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToStack('before', $values, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) inside actually
	 * selected nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	valuesToStackAfter($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2></node1><foo></foo><node1><node2></node2></node1><bar></bar>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - node2
	 * foo
	 * node1
	 *  - node2
	 * bar
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToSelector()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToStackAfter($values, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToStack('after', $values, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) inside actually
	 * selected nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	valuesToStackPrepend($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><foo></foo><node2></node2></node1><node1><bar></bar><node2></node2></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - foo
	 *  - node2
	 * node1
	 *  - bar
	 *  - node2
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToSelector()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToStackPrepend($values, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToStack('prepend', $values, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) inside actually
	 * selected nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	valuesToStackAppend($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1><node2></node2><foo></foo></node1><node1><node2></node2><bar></bar></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - node2
	 *  - foo
	 * node1
	 *  - node2
	 *  - bar
	 * </code>
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToSelector()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToStackAppend($values, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToStack('append', $values, $skipFields, $fieldCallback);
	}
	/**
	 * Injects markup from $values' content (rows or attributes) inside actually
	 * selected nodes.
	 *
	 * Method doesn't change selected nodes stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 * 	valuesToStackAttr('rel', $values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 *
	 * <node1 rel="&lt;foo/&gt;"><node2></node2></node1><node1 rel="&lt;bar/&gt;"><node2></node2></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - node2
	 * node1
	 *  - node2
	 * </code>
	 *
	 * @param String $attr
	 * Target attribute name.
	 *
	 * @param Array|Object $values
	 * Associative array or Object containing markup, text or instance of Callback.
	 *
	 * @param Callback|string $fieldCallback
	 * Callback triggered after every insertion. Three parameters are passed to
	 * this callback:
	 * - $node phpQueryObject
	 * - $field String
	 * - $target String|array
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::valuesToSelector()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 */
	public function valuesToStackAttr($attr, $values, $skipFields = null, $fieldCallback = null) {
		return $this->_valuesToStack(array('attr', $attr), $values, $skipFields, $fieldCallback);
	}
	protected function _valuesToStack($target, $data, $skipFields, $fieldCallback) {
		$_target = $target;
		$targetData = null;
		if (is_array($target)) {
			$targetData = array_slice($target, 1);
			$target = $target[0];
		}
		$i = 0;
		foreach($data as $k => $v) {
			if ($skipFields && in_array($f, $skipFields))
				continue;
			if ($v instanceof Callback)
				$v = phpQuery::callbackRun($v);
			$node = $this->eq($i++);
			switch($target) {
				case 'attr':
					$node->attr($targetData[0], $v);
					break;
				default:
					$node->$target($v);
			}
			if ($fieldCallback)
				// TODO doc
				phpQuery::callbackRun($fieldCallback, array($node, $v, $_target));
		}
		return $this;
	}
}