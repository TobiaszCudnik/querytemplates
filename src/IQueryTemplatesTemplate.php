<?php
/**
 * Interface for QueryTemplatesTemplate derivatives.
 * 
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
interface IQueryTemplatesTemplateChain {
	public function templateCache($state = null);
	public function templateName($newName = null);
}
/**
 * Interface for QueryTemplatesTemplate derivatives operating on sources.
 * 
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
interface IQueryTemplatesTemplateSources {
	public function sourceCollect($path, $name = null);
	public function sourceCollectPHP($path, $name = null);
	public function sourceQuery($path);
	public function sourceQueryPHP($path, $name = null);
//	public function sourceTemplate($name);
}
/**
 * Interface for QueryTemplatesTemplate derivatives before parsing stage.
 * 
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
interface IQueryTemplatesTemplateParse {
	public function parse();
}
/**
 * Interface for QueryTemplatesTemplate class.
 * 
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
interface IQueryTemplatesTemplate
	extends IQueryTemplatesTemplateChain,
		IQueryTemplatesTemplateSources,
		IQueryTemplatesTemplateParse {
}