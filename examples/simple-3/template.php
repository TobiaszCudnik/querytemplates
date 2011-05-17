<?php
/* STEP 0 - prepare input data */
$data = array(
	array(
		'field1' => 'value1',
		'field2' => 'value2',
		'field3' => 'value3',
	),
	array(
		'field1' => 'lorem',
		'field2' => 'ipsum',
		'field3' => 'dolor',
	),
);

/* STEP 1 - set up enviroment */
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
			// wraps .my-div with an 'if' statement
			// this can be done easier, as shown in next examples
			->beforePHP('if (isset($data[1])):')
			->afterPHP('endif;')
			->find('ul > li')
				->loopOne('data', 'row')
					->find('.field1')->php('print $row["field1"]')->end()
					->find('.field2')->php('print $row["field2"]')->end()
					->find('.field3')->php('print htmlspecialchars($row["field3"])')->end()
				->end()
			->end()
		->end()
	->save()
;
htmlspecialchars();
/* STEP 3 - include generated template */
require($template);