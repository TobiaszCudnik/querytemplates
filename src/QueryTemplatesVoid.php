<?php
/**
 * Fake template chain class.
 * 
 * @access private
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
class QueryTemplatesVoid {
	protected $template;
	protected $endMethod;
	/**
	 * 
	 * @param $template
	 * @param $endMethod
	 * @param $forwardMethods
	 * @return unknown_type
	 * 
	 * @TODO $forwardMethods
	 */
	public function __construct($template, $endMethod, $forwardMethods = null) {
		$this->template = $template;
		$this->endMethod = $endMethod;
	}
	public function __call($method, $arguments) {
		return $method == $this->endMethod
			? $this->template
			: $this;
	}
	public function save() {
		return $this->template->save();
	}
	public function __toString() {
		return $this->template->save();
	}
	public function toReference(&$reference) {
		// XXX: clone ?
		$reference = new QueryTemplatesVoid($this->template, $this->endMethod);
		return $this;
	}
}