<?php
/* STEP 0 - prepare input data */
$data = array(
	'first',
	'second',
	'third'
);

/* STEP 1 - set up enviroment */
require_once('../../src/QueryTemplates.php');
QueryTemplates::$sourcesPath = dirname(__FILE__);
QueryTemplates::$targetsPath = dirname(__FILE__);
//QueryTemplates::$debug = 1;
//QueryTemplates::$cacheTimeout = -1;

/* STEP 2 - create template */
$template = template('output', 'js');
// use same file for JS and PHP template
require('template.php');
$template->save('document.write');

/* STEP 3 - include generated template */
header("Content-Type: application/x-javascript");
print 'var data = '.QueryTemplates::toJSON($data).";\n";
print(file_get_contents($template));