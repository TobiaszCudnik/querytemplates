<?php
/**
 * Template parsing class.
 *
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
class QueryTemplatesParse
	extends QueryTemplatesSyntax
	implements IQueryTemplatesParse {
	public $dependencies = array();
	public $collected = array();
	/**
	 * Vars avaible in template scope AFTER template parsing process.
	 *
	 * @var unknown_type
	 */
	public $vars = array();
	/**
	 * Enter description here...
	 *
	 * @var QueryTemplatesTemplate
	 */
	public $parent;
	public function __construct($parent, $docId = null) {
		if ($parent instanceof self) {
			// new instance call from phpQuery
			// link all important vars to new object
			$this->parent =& $parent->parent;
			$this->collected =& $parent->collected;
			$this->dependencies =& $parent->dependencies;
			parent::__construct($docId);
		} else {
			$this->parent = $parent;
			$this->parent->parse = $this;
			$this->collectAll();
		}
	}
	public function qt_langCode($type) {
		$languageClass = 'QueryTemplatesLanguage'.$this->qt_lang();
		$params = func_get_args();
		return call_user_func_array(
			array($languageClass, $type),
			array_slice($params, 1)
		);
	}
	public function qt_langCodeRaw($code) {
		$lang = strtolower($this->qt_lang());
		return phpQuery::code($lang, $code);
	}
	public function qt_langMethod($method) {
		$params = func_get_args();
		return call_user_func_array(
			array($this, $method.$this->qt_lang()),
			array_slice($params, 1)
		);
	}
	protected function qt_lang() {
		return strtoupper($this->parent->language);
	}
	private function collectAll() {
		$s = DIRECTORY_SEPARATOR;
		QueryTemplates::$sourcesPath = rtrim(QueryTemplates::$sourcesPath, $s).$s;
		// repeat all method calls stored in replicator to QueryTemplatesSource object
		foreach($this->parent->toFetch['parts'] as $r) {
			// FIXME check if path is valid
			// QueryTemplates::$sourcesPath.$r[0]
			if ($r[0] instanceof Callback) {
				$content = phpQuery::callbackRun($r[0]);
			} else {
				$content = file_get_contents(QueryTemplates::$sourcesPath.$r[0]);
			}
			// PHP file
			if ($r[2])
				$content = phpQuery::markupToPHP($content);
			$fetch = new QueryTemplatesSourceQuery($this, $content);
			foreach($r[1]->calls as $call) {
				$fetch = call_user_method_array($call[0], $fetch, $call[1]);
			}
			$this->dependencies[] = QueryTemplates::$sourcesPath.$r[0];
		}
		foreach($this->parent->toFetch['full'] as $r) {
			if ($r[0] instanceof Callback) {
				if ($r[1])
					$name = $r[1];
				else if ($r[0] instanceof ICallbackNamed && $r[0]->hasName())
					$name = $r[0]->getName();
				else
					throw new Exception('Name needed when using Callback as source');
				$content = phpQuery::callbackRun($r[0]);
			} else {
				$name = $r[1]
					? $r[1]
					: $r[0];
				$content = file_get_contents(QueryTemplates::$sourcesPath.$r[0]);
				$this->dependencies[] = QueryTemplates::$sourcesPath.$r[0];
			}
			$this->collected[$name] = $content;
			if ($r[2]) {
				$this->collected[$name] = phpQuery::markupToPHP(
					$this->collected[$name]
				);
			}
		}
	}
	/**
	 * Save template and return file path ready to include.
	 *
	 * @param $callback
	 * Function to call at the end of template source, with one argument, which
	 * is template function or it's output (depending on $activeCallback).
	 * Supported by following languages: JS
	 *
	 * @param $activeCallback
	 * Determines if argument passed to a $callback is template's function name
	 * or it's result.
	 * Supported by following languages: JS
	 *
	 * @return string
	 */
	public function save($callback = null, $activeCallback = true) {
		if (! $this->parent->cache) {
			$cacheTimeout = QueryTemplates::$cacheTimeout;
			QueryTemplates::$cacheTimeout = -1;
		}
		$params = func_get_args();
		// XXX really, what is this ?
		$params[1] = $activeCallback;
		$return = QueryTemplates::saveTemplate(
			$this->toRoot(),
			$this->dependencies,
			$this->parent->name,
			$this->vars,
			null, true,
			$this->parent->language,
			$params
		);
		if (! $this->parent->cache) {
			QueryTemplates::$cacheTimeout = $cacheTimeout;
		}
		$this->parent->parse = $return;
		return $return;
	}
	public function documentCreate($source) {
		$id = phpQuery::newDocument($source->markupOuter())->getDocumentId();
		parent::__construct($id);
	}
	public function __toString() {
		try {
			return $this->save();
		} catch(Exception $e) {
			return '';
		}
	}
	/**
	 * Iterator override to return extended phpQuery object insted of DOMNode.
	 *
	 * @return unknown
	 * @access private
	 */
	public function current(){
		return $this->newInstance(
			array(parent::current())
		);
	}
	/**
	 * Return collected source as phpQuery object. Returned object is a separate document.
	 * Method must be ended with one of followings:
	 * - QueryTemplatesSource::returnReplace()
	 * - QueryTemplatesSource::returnAppend()
	 * - QueryTemplatesSource::returnPrepend()
	 * - QueryTemplatesSource::returnAfter()
	 * - QueryTemplatesSource::returnBefore()
	 *
	 * @param String $name
	 * @return QueryTemplatesSource
	 * @see QueryTemplatesSource
	 */
	public function source($name) {
		$this->debug("Source pickup: {$name}");
		if (! isset($this->collected[$name]))
			throw new Exception("Source '{$name}' isn\'t loaded.");
//		$this->elements = array($this->root);
		return new QueryTemplatesSource($this, $this->collected[$name]);
	}
	/**
	 * @see QueryTemplatesTemplate::templateCache()
	 * @return QueryTemplatesParse
	 */
	public function templateCache($state = null) {
		return $this->parent->templateCache($state);
	}
	/**
	 * @see QueryTemplatesTemplate::templateName()
	 * @return QueryTemplatesParse
	 */
	public function templateName($newName = null) {
		return $this->parent->templateName($newName);
	}
}