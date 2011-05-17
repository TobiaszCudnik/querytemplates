<?php
/**
 * QueryTemplates -  DOM and CSS oriented template engine
 * 
 * PHP based templating engine creating reusable native templates in various
 * languages.
 * 
 * As for today, supported are following sources: HTML, XML, XHTML, PHP and 
 * PHP callbacks which can be processed into PHP or JS template file.
 * 
 * Library uses popular web 2.0 pattern load-traverse-modify thou jQuery like
 * chainable API and provides developer several rapid template filling methods.
 *
 * @version 1.0 beta4
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
require_once(dirname(__FILE__)."/QueryTemplatesTemplate.php");
/**
 * Static methods namespace class.
 *
 * @static
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
abstract class QueryTemplates {
	/**
	 * Turns on/off debug. Includes phpQuery's one.
	 * @param bool
	 * @see phpQuery::$debug
	 */
	public static $debug = false;
	/**
	 * Path to direcotry where cached templates will be stored.
	 * @var string
	 * @see self::$cacheTimeout
	 */
	public static $targetsPath = null;
	/**
	 * Checks if source template file exists and if it has been modified.
	 * Can be turned off for performance reasons on production enviroment.
	 * @var bool
	 */
	public static $monitorTemplateModification = true;
	/**
	 * Checks if source code file has been modified.
	 * Use it only on developement enviroment as it runs debug_backtrace() every time.
	 * @var bool
	 * @TODO
	 */
	public static $monitorCodeModification = false;
	/**
	 * Path prepended to templates names.
	 * @var string
	 */
	public static $sourcesPath = '';
	/**
	 * Timeout for cached templates in minutes.
	 * Generally uneeded because of checking templates modification time.
	 * 
	 * False (default) means no timeout.
	 * -1 turns off cache.
	 * 
	 * @var mixed
	 * @see self::$cache
	 * @see self::$targetsPath
	 */
	public static $cacheTimeout = null;
	/**
	 * Use HTML Tidy for pretty output (if avaible).
	 * Tidy is included with every PHP5 installation, but it has be manually activated in php.ini.
	 * Value == 1 means tidy only partial templates (with no <html> or <body> tags)
	 * 	This is supposed to work quite well.
	 * Value == 2 means tidy all, including files with <head>, and this can fail,
	 * 	if You have <?php tags inside <head> with nested <script> tags.
	 * @todo Strip out HTML/BODY if it's soudlnt be in the template (something what show-body-only should do).
	 * @todo tidy removes empty tags (shouldnt !!!!)
	 * @TODO create formatter API
	 * @var int
	 */
	public static $htmlTidy = false;
	/**
	 * Use tabs insted of spaces for tidy intendation.
	 * @var bool
	 * @TODO create formatter API
	 */
	public static $htmlTidyIntendWithTabs = true;
	/**
   * Config for Tidy.
	 * @link http://tidy.sourceforge.net/docs/quickref.html
	 * @var array
	 * @TODO create formatter API
	 */
	public static $htmlTidyConfig = array(
		'indent' => true,
		'indent-spaces' => 4,
		'wrap' => false,
		'show-body-only' => 'yes',
		'merge-divs' => false,
		'new-inline-tags' => 'php',
		'tab-size' => 4,
		'output-bom' => false,
		// TODO support other charset
		'input-encoding' => 'utf8',
		'output-encoding' => 'utf8',
		'char-encoding' => 'utf8',
	);
	/**
	 * PEAR XML_Beutifuler
	 * 
	 * XML only. No full PHP code support. 
	 * 
	 * @var unknown_type
	 */
	public static $xmlBeautifier = false;
	protected static $xmlBeautifierInstance = null;
	/**
	 * Fixes paths to CSS, JS and image files.
	 * 
	 * @var string
	 */
	public static $fixWebroot = '';
	/**
	 * Returns cached template's path for inclusion.
	 * Returns false if cache isn't up-to-date.
	 *
	 * @return string|false
	 */
	public static function loadTemplate($templateName, $targetsPath = null, $language = 'php') {
		list($cachePath, $cacheDeps) = self::getCachePaths($templateName, $targetsPath, $language);
		$useCache = false;
		if (file_exists($cachePath) && self::$cacheTimeout >= 0) {
			$useCache = true;
			// check dependencies
			if (file_exists($cacheDeps)) {
				foreach(file($cacheDeps) as $line) {
					list($file, $time) = explode("\t", $line);
					if (! file_exists($file)) {
						$useCache = false;
						continue;
			//		debug('cache: template cache unavailable');
					}
					// check if template source have been modified
					if (filemtime($file) > $time ) {
						$useCache = false;
						continue;
			//		debug('cache: template source modified');
					}
					// check timeout (stiff refresh)
					if (self::$cacheTimeout && time()-$time > self::$cacheTimeout ) {
						$useCache = false;
						continue;
			//		debug('cache: cacheTimeout');
					}
				}
			}
			if ($useCache )
				return $cachePath;
		}
		return false;
	}
	/**
	 * Converts $data to JSON format.
	 * 
	 * @param Array|Object $data
	 * @return String
	 */
	public static function toJSON($data) {
		$dir = dirname(__FILE__);
		if (! class_exists('phpQuery')) {
			$included = @include_once('phpQuery.php');
			if (! $included)
				require_once("$dir/phpQuery/phpQuery.php");
		}
		return phpQuery::toJSON($data);
	}
	/**
	 * Converts JSON string to array or object.
	 * 
	 * @param String $data
	 * @return Array|Object
	 */
	public static function parseJSON($json) {
		$dir = dirname(__FILE__);
		if (! class_exists('phpQuery')) {
			$included = @include_once('phpQuery.php');
			if (! $included)
				require_once("$dir/phpQuery/phpQuery.php");
		}
		return phpQuery::parseJSON($json);
	}
	/**
	 * Creates new template and returns it's path.
	 * If You want to use self::$fixWebRoot You have to pass phpQuery object directly.
	 * CAUTION: this method will call unload() on passed phpQuery object, so You cant use it futher. Use $unloadDocument to avoid this.
	 *
	 * @param phpQuery			$_				HTML from object will be fetched by htmlOuter(), so take care of proper stack.
	 * @param string				$templatePath
	 * @param string				$templateName
	 * @param array					$dependencies
	 * @param string				$targetsPath
	 * @param bool					$unloadDocument
	 *
	 * @return string|false
	 */
	public static function saveTemplate($pq, $dependencies = array(), 
			$templateName = null, $vars = null, $targetsPath = null, 
			$unloadDocument = true, $language = 'php', $extraParams = array()) {
		// for performance reasons, cache stuff is checked only when writing
		if (! self::validateCacheSettings() )
			return false;
		if (! self::$monitorTemplateModification)
			$dependencies = array();
		if (! $templateName)
			$templateName = md5(microtime());
		$markup = self::postFilters($pq, $language);
		// needed to avoid conflicts
		if ($unloadDocument)
			$pq->unloadDocument();
		list($cachePath, $cacheDeps) = self::getCachePaths(
			$templateName, $targetsPath, $language);
		$languageClass = 'QueryTemplatesLanguage'.strtoupper($language);
		$fileContent = call_user_func_array(
			array($languageClass, 'templateWrapper'),
			array($markup, $templateName, $vars, $extraParams)
		);
		file_put_contents(
		 	$cachePath,
		 	$fileContent
		);
		$dependencies = array_map(
			array('QueryTemplates', 'mapDepends'),
			$dependencies
		);
		if (self::$monitorCodeModification)
			$dependencies[] = self::mapDepends(self::srcFilePath());
		file_put_contents(
		 	$cacheDeps,
		 	implode("\n", $dependencies)
		);
		return $cachePath;
	}
	/**
	 * Fetches included templates content into parsed one.
	 *
	 * @param	phpQuery	$_
	 * @return	array			Array of dependencies.
	 * @TODO refresh
	 */
	public static function parseIncludes($_) {
//		$rootDir = dirname(self::$sourcesPath.$templatePath).'/';
		$rootDir = self::$sourcesPath;
		// collect included templates name
		$dependencies = array();
		// FIXME change codetype to type (attribute)
		$selector = 'object[type=text/template]';
		foreach( $_->find($selector) as $include ) {
			$include = pq($include, $_->getDocumentID());
			$includePath = $rootDir.$include->attr('data');
			if (strpos($includePath, '{') !== false )
				continue;
			if (file_exists($includePath) ) {
				$_nested = phpQuery::newDocumentFile($includePath);
				$dependencies[] = $includePath;
				$dependencies = array_merge(
					$dependencies,
					self::parseIncludes( $_nested )
				);
				$include
					->after($_nested)
					->remove();
			} else
				throw new Exception("File '{$includePath}' doesn't exists, couldn't include template");
		}
		// remove unincludable includes (with {n} args)
		self::removeIncludes($_);
		// dont loose main template
		phpQuery::selectDocument($_);
		return $dependencies;
	}
	/**
	 * Removes included templates from parsed one.
	 *
	 * @param	phpQuery	$_
	 * @TODO refresh
	 * @return	phpQuery
	 */
	public static function removeIncludes($_) {
		$_->find('object[type=text/template]')
			->remove();
	}
	/**
	 * Clears cache folder. Can be limited to files containing $search in names.
	 * Returns number of deleted files.
	 *
	 * @param	string	$search	Optional. Search pattern, accepts wildcard. @see http://php.net/glob
	 * @return	int
	 */
	public function clearCache($search = '*') {

		$i = 0;
		foreach( glob(self::$targetsPath.$search.'.code.php') as $file ) {
			unlink($file);
			// TODO get cache names from self::getCachePaths() and delete other files
			$i++;
		}
		return $i;
	}
	/**
	 * @TODO refactore it's name 
	 * @param $file
	 * @return unknown_type
	 */
	protected static function mapDepends($file) {
		return $file."\t".filemtime($file);
	}
	protected static function srcFilePath() {
		foreach(array_slice(debug_backtrace(), 2) as $r) {
			$phrase = 'QueryTemplates';
			$filename = substr($r['file'], strrpos($r['file'], '/')+1);
			if (substr($filename, 0, strlen($phrase)) != $phrase)
				return $r['file'];
		}
	}
	protected static function normalizeVarName($string) {
		return preg_replace('@[^\\w$]@i', '_', $string);
	}
	protected static function validateCacheSettings() {
		 if (is_null(self::$targetsPath)) {
		 	die('[QueryTemplates] QueryTemplates::$targetsPath not set; '
		 		.QueryTemplates::$targetsPath);
		 	throw new Exception('QueryTemplates::$targetsPath not set - '
		 		.QueryTemplates::$targetsPath);
		 } else {
			 if (! file_exists(self::$targetsPath)) {
			 	die('[QueryTemplates] Directory QueryTemplates::$targetsPath doesn\'t exist '
			 		.QueryTemplates::$targetsPath);
			 	throw new Exception('Directory QueryTemplates::$targetsPath doesn\'t exist - '
			 		.QueryTemplates::$targetsPath);
			 }
			 if (! is_writable(self::$targetsPath)) {
				 if (! @chmod(self::$targetsPath, 0666)) {
				 	die('[QueryTemplates] Directory QueryTemplates::$targetsPath isn\'t writtable '
				 		.QueryTemplates::$targetsPath);
				 	throw new Exception('Directory QueryTemplates::$targetsPath isn\'t writtable - '
				 		.QueryTemplates::$targetsPath);
				 }
			 }
		 }
		 $s = DIRECTORY_SEPARATOR;
		 self::$targetsPath = rtrim(self::$targetsPath, $s).$s;
		 return true;
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $templateName
	 * @param unknown_type $targetsPath
	 * @return unknown
	 * @TODO refactor to getTargetPaths
	 */
	protected static function getCachePaths($templateName, $targetsPath = null, $language = 'php') {
		$replace = array('/', '\\');
		if (! $targetsPath )
			$targetsPath = self::$targetsPath;
		$clearCacheName = self::cleanCacheName($templateName);
		// TODO ???
		$extension = strtolower($language);
		$s = DIRECTORY_SEPARATOR;
		return array(
			rtrim($targetsPath, $s).$s.$clearCacheName.'.code.'.$extension,
//			$targetsPath.$clearCacheName.'.time.php',
			rtrim($targetsPath, $s).$s.$clearCacheName.".deps-$extension.php",
//			$targetsPath.$clearCacheName.'.src_time.php',
		);
	}
	protected static function cleanCacheName( $name ) {
		return str_replace(array('/', '\\'), '_', $name);
	}
	/**
	 * HTML Tidy
	 * 
	 * @param $markup
	 * @param $isDocumentFragment
	 * @return unknown_type
	 * 
	 * FIXME formatting attr with PHP code (input[value]) changes PHP tags to normal
	 * @TODO use output-html	output-xml output-xhtml
	 */
	public static function htmlTidy($markup, $isDocumentFragment) {
		if ($isDocumentFragment) {
			// if we doesnt want whole doc, but only a part, get <body> content
//			$config = array_merge(
//				self::$htmlTidyConfig,
//				array('show-body-only' => true)
//			);
			$htmlTidy = tidy_parse_string($markup, self::$htmlTidyConfig);
//			$html = tidy_get_output($htmlTidy);
			$markup = '';
			$body = tidy_get_body($htmlTidy);
			if ($body->child)
				foreach($body->child as $node)
					// get outer html
					$markup .= $node->value;
		} else if (self::$useTidy === 2) {
			// adding <php> as new block element destroys tags inside <head>,
			// so we need to mask them for a while
			// this is DIRTY hack and You cant rely on it
		/*	$html = preg_replace("@<\\?php(.+?)(?:\\?>)@", "<script type='text/php'>\\1</script>", $html);*/
			$htmlTidy = tidy_parse_string($markup, self::$htmlTidyConfig);
			$markup = tidy_get_output($htmlTidy);
			// and now backwards...
		/*	$html = preg_replace("@<script type='text/php'>(.+?)(?:</script>)@", "<?php\\1?>", $html);*/
		}
		if (self::$htmlTidyIntendWithTabs) {
			$markup = preg_replace_callback("@(?<=\\n)(\\s+)(?=.)@",
				array('QueryTemplates', 'htmlTidySpacesToTabs'),
				$markup
			);
		}
		return $markup;
	}
	public static function htmlTidySpacesToTabs($matches) {
		$spacesPerTab = self::$htmlTidyConfig['indent-spaces']
			? self::$htmlTidyConfig['indent-spaces']
			: 4;
		return str_repeat("\t", strlen($matches[1])/$spacesPerTab);
	}
	/**
	 * PEAR XML_Beautifier class.
	 * 
	 * Does NOT support <?php tags inside attributes.
	 * 
	 * @link http://pear.php.net/package/XML_Beautifier
	 * @param $markup
	 * @return unknown_type
	 */
	public static function xmlBeautifier($markup) {
		if (self::$xmlBeautifierInstance)
			$fmt = self::$xmlBeautifierInstance;
		else {
			require_once "XML/Beautifier.php";
			$fmt = new XML_Beautifier();
		}
		$markup = $fmt->formatString($markup);
		if (PEAR::isError($result)) {
			self::debug($result->getMessage());
		}
		return $markup;
	}
	/**
	 * Enter description here...
	 *
	 * @param phpQuery $_
	 * @return string
	 */
	protected static function postFilters($dom, $language = 'php') {
		if (self::$fixWebroot)
			$dom->plugin('Scripts')->script('fix_webroot', self::$fixWebroot);
		$languageClass = 'QueryTemplatesLanguage'.strtoupper($language);
		$dom = call_user_func_array(
			array($languageClass, 'postFilterDom'), array($dom)
		);
		$markup = $dom->markupOuter();
		$markup = call_user_func_array(
			array($languageClass, 'postFilterMarkup'),
			array($markup, $dom->documentFragment())
		);
		return $markup;
	}
	public static function addslashes($target, $char = "'") {
		return str_replace($char, '\\'.$char, $target);
	}
}
// set default targetsPath
QueryTemplates::$targetsPath = dirname(__FILE__).'/cache';
