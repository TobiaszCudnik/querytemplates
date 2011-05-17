<?php
/**
 * Class handling template source use.
 * 
 * @abstract
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 * 
 * @TODO multi-returns
 */
class QueryTemplatesSource
	extends phpQueryObject
	implements IQueryTemplatesParseChain {
	/**
	 * Parent template.
	 *
	 * @var QueryTemplatesParse
	 */
	public $parent;
	public function __construct($parent, $src = null) {
		if ($parent instanceof self) {
			// new instance call from phpQuery
			// link all important vars to new object
			$this->parent =& $parent->parent;
			$docId = $src;
			parent::__construct($docId);
		} else {
			$this->parent = $parent;
//			$docId = parent::createDom($src);
			$docId = phpQuery::newDocument($src)->getDocumentID();
			parent::__construct($docId);
			// parse template includes and merge dependencies
			$this->parent->dependencies = array_merge(
				$this->parent->dependencies,
				QueryTemplates::parseIncludes($this)
			);
		}
	}
	/**
	 * Free source DOM from memory and returns parent template.
	 * No more source pickups is possible after this operation.
	 *
	 * @return QueryTemplatesParse
	 */
	public function free() {
		$this->unloadDocument();
		return $this->parent;
	}
	/**
	 * Returns actual source stack as a replacement for templates stack or
	 * nodes matched by optional $cssSelector executed agains templates stack.
	 *
	 * @param unknown_type $cssSelector
	 * @return QueryTemplatesParse
	 */
	public function returnReplace($cssSelector = null) {
		if (! $this->parent->document) {
			$this->parent->documentCreate($this);
		} else {
			if ($cssSelector)
				$this->parent->find($cssSelector)->replaceWith($this);
			else
				$this->parent->documentCreate($this);
		}
		return $this->free();
	}
	/**
	 * Returns actual source stack prepending it to template's stack or
	 * nodes matched by optional $cssSelector executed agains template's stack.
	 *
	 * @param string $cssSelector
	 * @return QueryTemplatesParse Parent template.
	 */
	public function returnPrepend($cssSelector = null) {
		return $this->_returnInsert($cssSelector, 'prependTo');
	}
	/**
	 * Returns actual source stack appending it to template's stack or
	 * nodes matched by optional $cssSelector executed agains template's stack.
	 *
	 * @param string $cssSelector
	 * @return QueryTemplatesParse Parent template.
	 */
	public function returnAppend($cssSelector = null) {
		return $this->_returnInsert($cssSelector, 'appendTo');
	}
	/**
	 * Returns actual source stack inserting it after template's stack or
	 * nodes matched by optional $cssSelector executed agains template's stack.
	 *
	 * @param string $cssSelector
	 * @return QueryTemplatesParse Parent template.
	 */
	public function returnAfter($cssSelector = null) {
		return $this->_returnInsert($cssSelector, 'insertAfter');
	}
	/**
	 * Returns actual source stack inserting it before template's stack or
	 * nodes matched by optional $cssSelector executed agains template's stack.
	 *
	 * @param string $cssSelector
	 * @return QueryTemplatesParse Parent template.
	 */
	public function returnBefore($cssSelector = null) {
		return $this->_returnBefore($cssSelector, 'insertBefore');
	}
	protected function _returnInsert($cssSelector, $method) {
		if (! $this->parent->document)
			$this->parent->documentCreate($this);
		else
			call_user_func_array(
				array($this, $method),
				array(
					$cssSelector
						? $this->parent->newInstance()->toRoot()->find($cssSelector)
						: $this->parent
				)
			);
		return $this->free();
	}
	/**
	 * Saves actual stack using markupOuter() as value of variable named $var.
	 * New variable is available in template's scope.
	 *
	 * @param string $name
	 * @TODO customizable method (markupOuter)
	 * @param string $cssSelector
	 * @return QueryTemplatesParse Parent template.
	 */
	public function saveAsVar($name) {
		$this->parent->vars[$name] = $this->htmlOuter();
		return $this->free();
	}
	public function __toString() {
		return $this->parent->__toString();
	}
	/**
	 * Saves template and returns file path ready to include.
	 *
	 * @return string
	 * @see QueryTemplatesParse::save()
	 */
	public function save($unloadDocument = true) {
		return $this->parent->save($unloadDocument);
	}
	/**
	 * @see QueryTemplatesTemplate::templateCache()
	 * @return QueryTemplatesSource
	 */
	public function templateCache($state = null) {
		$this->parent->templateCache($state);
		return $this;
	}
	/**
	 * @see QueryTemplatesTemplate::templateName()
	 * @return QueryTemplatesSourceQuery
	 */
	public function templateName($newName = null) {
		$this->parent->templateName($state);
		return $this;
	}
}