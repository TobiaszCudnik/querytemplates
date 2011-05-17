<?php
/**
 * Class used to store calls to source object for later
 * replication.
 *
 * @access private
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
class QueryTemplatesSourceReplicator {
	public $calls = array();
	public $parent;
	public function __construct($parent) {
		$this->parent = $parent;
	}
	public function __call($name, $arguments) {
		$parentMethods = array(
			'source', 'sourceQuery', 'sourcePHP', 'sourceQueryPHP', 'parse', 'noCache'
		);
		if (in_array($name, $parentMethods))
			// call parent method
			return call_user_method_array($name, $this->parent, $arguments);
		$endingMethods = array('sourceEnd');
		if (in_array($name, $endingMethods))
			// we're ended
			return $this->parent;
		// store call
		$this->calls[] = array($name, $arguments);
		return $this;
	}
}