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
	)
);
$postFields = array_keys($posts[0]['Post']);
$commentFields = array_keys($posts[0]['Comment'][0]);
$tagFields = array_keys($posts[0]['Tag'][0]);

/* STEP 1 - set up environment */
require_once('../../src/QueryTemplates.php');
QueryTemplates::$sourcesPath = dirname(__FILE__);
QueryTemplates::$targetsPath = dirname(__FILE__);
// QueryTemplates::$debug = 1;

/* STEP 2 - create template */
$template = template('output')
	->sourceCollect('input.html', 'collect-01')
	->parse()
		->source('collect-01')->returnReplace()
		->find('ul:first > li')
			->loopOne('posts', 'r')
				->find('> .title, > .body')
					// fills matched elements (stack) with $postFields' array content
					->varsToStack('r["Post"]', $postFields)
				->end()
				->find('.comments > li')
					->loopOne('r["Comment"]', 'comment')
						->varsToSelector('comment', $commentFields)
				->end()->end()
				->find('.tags')
					// remove text nodes
					->contents()->not('*')->remove()->end()->end()
					// add space separator
					->find('strong')->after(' ')->end()
					->prependPHP('$tagCount = count($r["Tag"]);')
					->find('a')
						// loopOne with 3 arguments, second is key, as in foreach
						->loopOne('r["Tag"]', 'k', 'tag')
							->attrPHP('href', 'print "tag/{$tag["id"]}"')
							->php('print $tag["tag"];')
							// add comma (,) between tag names
							->afterPHP('if ($k+1 < $tagCount) print ", ";')
					->end()->end()
				->end()
		// no save() used, as it's automatically triggered by require()
;

/* STEP 3 - include generated template */
require($template);