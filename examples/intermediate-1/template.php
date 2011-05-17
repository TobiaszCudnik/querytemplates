<?php
/* STEP 0 - prepare input data */
$data = array(
	array(
		'Model-1' => array(
			'field-1' => 'Model-1-value-1',
			'field-2' => 'Model-1-value-2',
			'field-3' => 'Model-1-value-3',
		),
		'Model-2' => array(
			'field-1' => 'Model-2-value-1',
			'field-2' => 'Model-2-value-2',
			'field-3' => 'Model-2-value-3',
		),
	),
	array(
		'Model-1' => array(
			'field-1' => 'Model-1-value-1',
			'field-2' => 'Model-1-value-2',
			'field-3' => 'Model-1-value-3',
		),
		'Model-2' => array(
			'field-1' => 'Model-2-value-1',
			'field-2' => 'Model-2-value-2',
			'field-3' => 'Model-2-value-3',
		),
	),
);
// model fields are required when there's possibility of empty data
$modelOneFields = array_keys($data[0]['Model-1']);
$modelTwoFields = array_keys($data[0]['Model-2']);

/* STEP 1 - set up environment */
require_once('../../src/QueryTemplates.php');
QueryTemplates::$sourcesPath = dirname(__FILE__);
QueryTemplates::$targetsPath = dirname(__FILE__);
//QueryTemplates::$debug = 1;

/* STEP 2 - create template */
$template = template('output')
	->sourceCollect('input.html')
	->parse()
		->source('input.html')->returnReplace()
		->find('.my-div')
			->find('ul > li')
				->loopOne('data', 'row')
					// using varsToSelector and having 2 models can lead to naming collisions
					// one of possible ways to bypass this problem is reduceing
					// matched nodes to interesting ones
					->find('> *')->slice(1, 3)
						->varsToSelector('row["Model-1"]', $modelOneFields)
					->end()->end()
					// second way is to redefine selector patter in varsToSelector
					// but this requires also changing classes inside template
					->varsToSelector('row["Model-2"]', $modelTwoFields, '.Model-2-%k')
				->end()
			->end()
		->end()
	->save()
;

/* STEP 3 - include generated template */
require($template);