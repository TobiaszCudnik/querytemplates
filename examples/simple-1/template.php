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

/* STEP 2 - create template */
$template = template('output')
	// fetches input.html from $sourcesPath (see above)
	// as source with name same as filename
	->sourceCollect('input.html')
	// parse is delimiter which starts template parsing stage
	// everything between parse()..save() is subject to cache
	->parse()
		// source() inside parse() returns source fetched above
		// returnReplace() pushes sources stack (full source by default)
		// to template, replacing everything
		->source('input.html')->returnReplace()
		->find('ul > li')
			// loopOne means 'use first for a loop and throw away rest
			// of selected elements (ul > li in this case)
			->loopOne('data', 'row')
				// injects php code as innerHTML
				->php('print $row')
			->end()
		->end()
	// save() saves template in $targetsPath
	// every string conversion triggers save, so practically it's optional
	->save()
;

/* STEP 3 - include generated template */
require($template);