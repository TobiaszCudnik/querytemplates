<?php 
/* STEP 2 - create template */
$template
	->sourceCollect('input.html')
	->parse()
		// query UL before returning markup to the template
		// client-side templates rather doesn't need doctype and <html>
		->source('input.html')->find('ul')->returnReplace()
		->find('ul > li')
			->loopOne('data', 'row')
				->varPrint('row')
			->end()
		->end()
	// save is skipped due to difference in passed arguments in PHP and JS template
//	->save()
;