<?php
/* STEP 0 - prepare input data */
$data = array(
	'text-example' => 'new',
  'checkbox-example' => true,
  'radio-example' => 'second',
	'select-example' => 'second',
	'textarea-example' => 'new',
);

/* STEP 1 - set up environment */
require_once('../../src/QueryTemplates.php');
QueryTemplates::$sourcesPath = dirname(__FILE__);
QueryTemplates::$targetsPath = dirname(__FILE__);
//QueryTemplates::$debug = 1;
//QueryTemplates::$cacheTimeout = -1;

/* STEP 2 - create template */
$template = template('output', 'js');
require('template.php');
$template->save('document.write');

/* STEP 3 - include generated template */
header("Content-Type: application/x-javascript");
print 'var data = '.QueryTemplates::toJSON($data).";\n";
print(file_get_contents($template));