<?php
/**
 * Class handling template source fetching.
 * 
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
class QueryTemplatesSourceQuery
	extends phpQueryObject
	implements IQueryTemplatesTemplateChain,
		IQueryTemplatesTemplateSources,
		IQueryTemplatesTemplateParse {
	/**
	 * Enter description here...
	 *
	 * @var QueryTemplatesTemplate
	 */
	public $parent;
	public function __construct($parent, $src) {
		if ( $parent instanceof self ) {
			// new instance call from phpQuery
			// link all important vars to new object
			$this->parent =& $parent->parent;
			$docId = $src;
		} else {
			$this->parent = $parent;
			$docId = phpQuery::newDocument($src)->getDocumentId();
		}
		parent::__construct($docId);
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $name
	 * @return QueryTemplatesSourceQuery
	 */
	public function collect($name) {
		$this->parent->collected[$name] = $this->markupOuter();
		return $this;
	}
	/**
	 * Enter description here...
	 *
	 * @return QueryTemplatesTemplate
	 */
	public function sourceEnd() {
		$this->unloadDocument();
		return $this->parent;
	}
	/**
	 * Start template parsing stage.
	 *
	 * @return string|QueryTemplatesParse
	 * @see QueryTemplates::parse()
	 */
	public function parse() {
		$this->fetchEnd();
		return $this->parent->parse();
	}
	/**
	 * @see QueryTemplatesTemplate::templateCache()
	 * @return QueryTemplatesSourceQuery
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
	/**
	 * Fetch file or URL.
	 *
	 * @param string $path
	 * @param string $name
	 * @return QueryTemplatesTemplate
	 * @see QueryTemplates::fetch()
	 */
	public function sourceCollect($path, $name = null) {
		$this->sourceEnd();
		$args = func_get_args();
		return call_user_method_array('sourceCollect', $this->parent, $args);
	}
	/**
	 * @param string $path
	 * @return QueryTemplatesTemplate
	 * @see QueryTemplatesTemplate::sourceQuery()
	 */
	public function sourceCollectPHP($path, $name = null) {
		$this->sourceEnd();
		$args = func_get_args();
		return call_user_method_array('sourceCollectPHP', $this->parent, $args);
	}
	/**
	 * Fetch file or URL and return phpQuery object with collect() method to
	 * fetch speficic part(s) of template.
	 *
	 * @param string $path
	 * @return QueryTemplatesSourceQuery
	 * @see QueryTemplatesTemplate::sourceQuery()
	 */
	public function sourceQuery($path) {
		$this->sourceEnd();
		$args = func_get_args();
		return call_user_method_array('sourceQuery', $this->parent, $args);
	}
	/**
	 * @param string $path
	 * @return QueryTemplatesSourceQuery
	 * @see QueryTemplatesTemplate::sourceQuery()
	 */
	public function sourceQueryPHP($path, $name = null) {
		$this->sourceEnd();
		$args = func_get_args();
		return call_user_method_array('sourceQueryPHP', $this->parent, $args);
	}
}