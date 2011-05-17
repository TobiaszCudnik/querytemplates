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
$template = template('output');
// use same file for JS and PHP template
require('template.php');

/* STEP 3 - include generated template */
require($template);