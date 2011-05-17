<?php
/**
 * Class transforms plain HTML forms into CakePHP templates using FormHelper.
 * Implemented as phpQuery plugin.
 * 
 * @TODO multi-add Model.{0}.field
 *
 * @package QueryTemplates
 * @subpackage QueryTemplatesPlugins
 */
abstract class phpQueryObjectPlugin_CakeForms {
	/**
	 * @var unknown_type
	 */
	public static $errorClass = 'errorField';
	/**
	 * @var unknown_type
	 */
	public static $errorWrapper = array('<p class="errorField">', '</p>');
	public static $attrToInherit = array('class', 'id', 'multiple');
	/**
	 * Method converts templates markup into CakePHP form constrols.
	 *
	 * @param $self
	 * @param string|array $modelName
	 * When an array, [0] is $modelName, [1] is form's $options.
	 * @param $formHelper
	 * @param $skipFields
	 * @return unknown_type
	 */
	public static function formToCakeForm($self, $modelName, $formHelper, $skipFields = array()) {
		$self = $self->is('form')
			? $self->filter('form')
			: $self->find('form');
		foreach($self as $form) {
			if (! $form->attr('id'))
				$form->attr('id', 'formID'.substr(md5(microtime()), 10));
			$id = $form->attr('id');
			$loop = $form['>fieldset']->length
				? $form['>fieldset']
				: $form;
			foreach($loop as $fieldset) {
				$removeSkipClass1 = $removeSkipClass2 = null;
				$inputWrappers = $fieldset->find('> *')
					->toReference($removeSkipClass1)
					->not('.formToCakeForm-skip');
				foreach($inputWrappers as $wrapper) {
					$input = $wrapper->find('input, select, textarea')
						->not('.formToCakeForm-skip, [name=_method]');
					if (! $input->size())
						continue;
					self::wrapper($wrapper, $form, $modelName, $formHelper);
				}
				// allow hidden inptus outside wrappers (like IDs)
				$inputsNoWrapper = $fieldset->find('>input[type=hidden]')
					->toReference($removeSkipClass2)
					->not('.formToCakeForm-skip');
				foreach($inputsNoWrapper as $input) {
					self::inputNoWrapper($input, $form, $modelName, $formHelper);
				}
				$removeSkipClass1->add($removeSkipClass2)
					->removeClass('formToCakeForm-skip');
			}
			self::formTagToCake($form, $modelName);
		}
	}
	public static function inputNoWrapper($input, $form, $modelName, $formHelper) {
		$type = $input->attr('type');
		if (! $type)
			$type = 'hidden';
		$fieldName = self::inputName($input);
		$CODE = <<<EOF
			print \$form->$type('$fieldName',
				array(
					'class' => '{$input->attr('class')}',
					'legend' => false,
					'label' => false,
			));
EOF;
		$input->replaceWithPHP($CODE);
	}
	/**
	 * Enter description here...
	 *
	 * @param phpQuery $wrapper
	 * @param phpQuery $form
	 * @param unknown_type $modelName
	 * @param FormHelper $formHelper
	 */
	public static function wrapper($wrapper, $form, $modelName, $formHelper) {
		$input = $wrapper->find('input[type=submit]:first');
		if ($input->length) {
			// support button
			$type = 'submit';
			$CODE = <<<EOF
					print \$form->submit('{$input->attr('value')}', array(
					'class' => '{$input->attr('class')}',
		 		));
EOF;
			$input->replaceWithPHP($CODE);
			return;
		} else {
			if (! $input->length) {
				$input = $wrapper->find('input[name]:first');
				if ($input->length) {
					$type = $input->attr('type');
				} else {
					$input = $wrapper->find('textarea[name]:first');
					if ($input->length)
						$type = 'textarea';
					if (! $input->length) {
						$input = $wrapper->find('select[name]:first');
						if ($input->length)
							$type = 'select';
					}
				}
			}
		}
		// TODO check if correct for select and textarea
		$wrapper->addClass('input');
		$wrapper->addClass($type);
		$fieldName = self::inputName($input);
		if (! $type)
			$type = 'hidden';
		// select field in form helper
		$formHelper->setEntity($fieldName);
		// check if required
		if (in_array($formHelper->field(), $formHelper->fieldset['validates']))
			$wrapper->addClass('required');
//		$input->attr('id', $formHelper->domId());
		if ($input->attr('type') == 'radio') {
			$wrapper->find('label:first')->removeAttr('for');
			// TODO apply inline labels as inner-radio-wrappers
			// count labels in wrapper, if >= count of inputs, that assume each label if for each input
		} else
			// join field with label
			$wrapper->find('label:first')->attr('for', $formHelper->domId());
		$errorOptions = array(
			'before' => self::$errorWrapper[0],
			'class' => self::$errorClass,
			'after' => self::$errorWrapper[1],
		);
		$errorOptions = var_export($errorOptions, true);
		$inputOptions = array(
			'type' => $type,
			'div' => '',
			'legend' => false,
			'label' => false,
		);
		$inputOptions = self::attrInherit($inputOptions, $input);
		if ($input->is(':radio'))
			$inputOptions['options'] = array(
				$input->attr('value') => false,
			);
		$inputOptions = var_export($inputOptions, true);
		$CODE = <<<EOF
			print \$form->error('$fieldName', null, $errorOptions);
			print \$form->input('$fieldName',
				{$inputOptions}
			);
EOF;
//				array(
//					'{$input->attr('value')}' => false
//				),
		$input->replaceWithPHP($CODE);
		if ($input->attr('type') == 'radio') {
			$radios = $wrapper->find('input[type=radio]')
				->not($input);
			foreach($radios as $radio) {
				$inputOptions = array(
					'type' => $type,
					'options' => array(
						$radio->attr('value') => false,
					),
					'div' => '',
					'legend' => false,
					'label' => false,
				);
				$inputOptions = self::attrInherit($inputOptions, $radio);
				$inputOptions = var_export($inputOptions, true);
				$CODE = <<<EOF
					print \$form->input('$fieldName',
						$inputOptions
					);
EOF;
				$radio->replaceWithPHP($CODE);
			}
		}
	}
	protected static function attrInherit($array, $node) {
		foreach(self::$attrToInherit as $attr) {
			$val = $node->attr($attr);
			if ($val !== null)
				$array[$attr] = $val;
		}
		return $array;
	}
	/**
	 * Enter description here...
	 *
	 * @param phpQuery|queryTemplatesFetch|queryTemplatesParse|queryTemplatesPickup $self
	 */
	public static function formRemoveInputs($self, $names) {
		foreach(self::form($self)->find('input') as $input) {
			$name = self::inputName($input);
			if (! in_array($name, $names))
				continue;
			$wrapper = $input;
			while(! ($wrapper->parent()->is('fieldset') || $wrapper->parent()->is('form'))) {
				$wrapper = $wrapper->parent();
			}
			$wrapper->remove();
		}
	}
	protected static function form($self) {
		return $self->is('form')
			? $self->filter('form')
			: $self->find('form');
	}
	protected static function inputName($input) {
		$name = $input->attr('name');
		if (strpos($name, '[')) {
			$match = array();
			preg_match('@\[([^\]]+?)(\](\[\])?$)@', $name, $match);
			$name = $match[1];
		}
		return $name;
	}
	/**
	 * Carefull ! Element is removed !
	 *
	 * @param unknown_type $self
	 * @param string|array $model
	 * When an array, [0] is $model, [1] is $options.
	 */
	static function formTagToCake($self, $model) {
		$self = self::form($self);
		foreach($self as $form) {
			$options = array();
			if ($form->attr('action'))
				$options['url'] = $form->attr('action');
			if ($form->attr('class'))
				$options['class'] = $form->attr('action');
			if (is_array($model)) {
				$options = am($options, $model[1]);
				$model = $model[0];
			}
			$options = var_export($options, true);
			$form->contents()
				->slice(0, 1)
					->beforePHP('echo $form->create("'.$model.'", '.$options.');')
				->end()
				->slice(-1)
					->afterPHP('echo $form->end();')
				->end();
			$form->after($form->contents())
				->remove();
		}
	}
	/**
	 * FROM:
	 * <input name='simple' />
	 * INTO:
	 * <input name='data[Model][simple]' />
	 *
	 * @param unknown_type $self
	 * @param unknown_type $model
	 */
	static function inputNamesToCake($self, $model) {
		$self = $self->is('input')
			? $self->filter('input')
			: $self->find('input');
		foreach($self as $input) {
			$name = pq($input)->attr('name');
			if ($name)
				pq($input)
					->attr('name', "data[$model][$name]");
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param $node phpQuery|queryTemplatesFetch|queryTemplatesParse|queryTemplatesPickup
	 * @TODO should it be here ?
	 */
	static function formMoveInputs($self, $inputNames, $destinationSelector) {
		foreach($inputNames as $name) {
			$self->find("fieldset > *:has(input[name*=$name])")
				->appendTo($destinationSelector);
		}
	}
}