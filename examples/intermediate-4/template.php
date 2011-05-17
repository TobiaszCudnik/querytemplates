<?php
/* STEP 2 - create template */
$template
	->sourceCollect('input.html')
	->parse()
		->source('input.html')->find('form')->returnReplace()
		->varsToForm('data', $data)
		// part below will be executed only for JS template
		->onlyJS()
			->find('legend')
				->text('JS-only label')
			->end()
		->endOnly()
;