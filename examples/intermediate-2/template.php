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
// QueryTemplates::$debug = 1;

/* STEP 2 - create template */
$template = template('output')
	->sourceCollect('input.html')
	->parse()
		->source('input.html')->returnReplace()
		// valuesToForm fills form with passed data (which is not dynamic)
		// notice the difference between 'fill' and 'create'
		// it will select proper select[option], but won't create options inside select
		->valuesToForm($data)
	->save()
;

/* STEP 3 - include generated template */
require($template);