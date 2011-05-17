<?php
/**
 * Interface for QueryTemplatesParse derivatives.
 * 
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
interface IQueryTemplatesParseChain
	extends IQueryTemplatesTemplateChain {
	public function save($unloadDocument = true);
}
/**
 * Interface for QueryTemplatesParse class.
 * 
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
interface IQueryTemplatesParse
	extends IQueryTemplatesParseChain {
	/**
	 * @param $name
	 * @return unknown_type
	 */
	public function source($name);
}
