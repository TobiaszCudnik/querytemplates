<?php
/* STEP 0 - prepare input data */
$posts = array(
	array(
		'Post' => array(
				'title' => 'Im the title',
				'body' => 'Im the body',
		),
		'Comment' => array(
				array(
						'author' => 'Im the 1st comment of 1st post',
						'body' => "This field has same name as post's one",
				),
				array(
						'author' => 'Im the 2nd comment of 1st post',
						'body' => 'So am i',
				),
		),
		'Tag' => array(
				array(
						'tag' => 'Tag1',
						'id' => "1",
				),
				array(
						'tag' => 'Tag2',
						'id' => '2',
				),
		),
	),
	array(
		'Post' => array(
				'title' => 'Second title',
				'body' => 'Second body',
		),
		'Comment' => array(
				array(
						'author' => 'Tip',
						'body' => "There isn't any tags",
				),
		),
	),
);
$postFields = array_keys($posts[0]['Post']);
$commentFields = array_keys($posts[0]['Comment'][0]);
$tagFields = array_keys($posts[0]['Tag'][0]);

/* STEP 1 - set up environment */
$time1 = microtime();
require_once('../../src/QueryTemplates.php');
$time2 = microtime();
QueryTemplates::$sourcesPath = dirname(__FILE__);
QueryTemplates::$targetsPath = dirname(__FILE__);
// QueryTemplates::$debug = 1;
// QueryTemplates::$cacheTimeout = -1;
// Callback example
function myFunction($node) {
	// dump() method var_dumps actual stack and doesn't break the chain
	// use it for debugging
	pq($node)->dump();
}
$myFunctionCallback = new Callback('myFunction');
// intialize variable to be used as reference later
// weird syntax prevents some IDEs from resetting $row type ;)
${'row'} = null;
/** @var QueryTemplatesParse */
$row;

/* STEP 2 - create template */
$template = template('output')
	->sourceCollect('input.html', 'collect-01')
	->sourceQuery('input2.html')
		->find('.fake')->contents()->collect('collect-02')
	->sourceEnd()
	->parse()
		->source('collect-01')->returnReplace()
		// apply callback to all matched elements
		->find('ul:first > li')->each($myFunctionCallback)->end()
		->source('collect-02')->returnAppend('ul:first > li:first')
		->find('ul:first > li')
			->loopOne('posts', 'postNum', 'r')
				// add dynamic class
				->addClassPHP('if (! $postNum) print "first"')
				->find('> .title, > .body')
					->varsToStack('r["Post"]', $postFields)
				->end()
				->dump()
				// toReference saves actual matched elements inside variable
				->toReference($row)
;
// chain can be breaked in any place
$row
	->find('h3:first, .comments')
		// wraps matched elements with an 'if' statement
		// notice it uses one 'if' for 2 elements
		->ifPHP('isset($r["Comment"]) && $r["Comment"]')
	->end()
	->find('.comments > li')
		->loopOne('r["Comment"]', 'comment')
			->varsToSelector('comment', $commentFields)
	->end()->end()
	->find('.tags')
		->ifPHP('isset($r["Tag"]) && $r["Tag"]')
		->contents()->not('*')->remove()->end()->end()
		->find('strong')->after(' ')->end()
		->prependPHP('$tagCount = count($r["Tag"]);')
		->find('a')
			->loopOne('r["Tag"]', 'k', 'tag')
				->attrPHP('href', 'print "tag/{$tag["id"]}"')
				->php('print $tag["tag"];')
				->afterPHP('if ($k+1 < $tagCount) print ", ";')
;

/* STEP 3 - include generated template */
$template->save();
$end1 = microtime();
require($template);
$end2 = microtime();
var_dump(array(
	'time tracked with library includes' => round($end1-$time1, 4),
	'time tracked with library and template includes' => round($end2-$time1, 4),
	'time tracked with template include' => round($end2-$time2, 4),
	'time tracked without includes' => round($end1-$time2, 4),
));